<?php namespace App\Logic\Post;

use App\Logic\Timeline\TimelineLogic;
use App\Models\PostModel;
use App\Models\CommentModel;

class PostLogic
{
    public static function create($data)
    {
        $validatedData = CreationForm::validate($data);
        $result = PostModel::insert($validatedData);

        if($result) {
            // 根据客户端传递的参数确定post类型进行对应操作
            $validatedData['post_id'] = $result;
            TimelineLogic::create($validatedData);
        }

        return $result;
    }

    public static function delete($id)
    {
        return PostModel::update(array('_id'=>new \MongoId($id)), array('status'=>0, 'update_at'=>time()));
    }

    public static function read($where)
    {
        $post = [];
        $validatedData = ReadForm::validate($where);
        $skip = ($where['page'] -1) * 10;
        $documents = PostModel::connection()
            ->find($validatedData)
            ->sort(['create_at'=> -1 ])
            ->skip($skip)
            ->limit(10);
        foreach($documents as $document) {
            // 只显示未删除的另客圈状态
            if($document['status'] == 1) {
                $document['_id'] = strval($document['_id']);
                //@todo 评论分页
                $comment = CommentModel::connection()->find(['post_id'=>$document['_id']])->sort(['create_at'=>1]);
                $document['comment'] = iterator_to_array($comment, false);
                switch($document['type']) {
                    case 7 :
                        // 转发另客圈状态
                        $forwardId = new \MongoId($document['forward_id']);
                        $forward = PostModel::connection()->findOne(['_id' => $forwardId]);
                        if($forward['status'] == 1) {
                            // 如果最初发布者删除，或违规信息被删除，则全网都不显示
                            $forward['_id'] = strval($forward['_id']);
                            $document['forward'] = $forward;
                            unset($document['forward_id']);
                        }
                        break;
                    case 8 :
                        // 分享链接
                        $link['url'] = $document['url'];
                        $content = file_get_contents($link['url']);
                        preg_match('/<title>(.*)<\/title>/i',$content, $matches);
                        if($matches) {
                            $link['title'] = $matches[1];
                        }
                        preg_match('/<img.*src\s*=\s*[\"|\']?\s*([^>\"\'\s]*)/i',$content, $matches);
                        if($matches) {
                            $link['image'] = $matches[1];
                        }
                        $document['link'] = $link;
                        unset($document['url']);
                        break;
                }
            }
            $post[] = $document;
        }
        return $post;
    }
}