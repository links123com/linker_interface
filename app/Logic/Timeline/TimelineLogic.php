<?php namespace App\Logic\Timeline;

use App\Models\CommentModel;
use App\Models\PostModel;
use App\Models\TimelineModel;
use App\Logic\Friend\FriendLogic;

class TimelineLogic
{
    public static function create(array $parameter)
    {
        $validatedData = CreationForm::validate($parameter);
        switch($validatedData['visibility']) {
            case 1 :
                // 所有人都可见
                $friends = FriendLogic::read([
                    'user_id'      =>$validatedData['user_id'],
                    'is_friend'    => 1,
                    'is_disable'   => 0,
                    'allow_linker' =>1
                ]);

                $data [] = [
                    'user_id'  => intval($validatedData['user_id']),
                    'post_id'  => $validatedData['post_id'],
                    'status'   => 0,
                    'is_at'    => 0,
                    'create_at'=>time()
                ];

                foreach($friends as $friend) {
                    $data[] = [
                        'user_id'   => intval($friend['friend_id']),
                        'post_id'   => $validatedData['post_id'],
                        'status'    => 0,
                        'is_at'     => 0,
                        'create_at' => time()
                    ];
                }

                if(!empty($data)) {
                    return TimelineModel::batchInsert($data);
                }
                break;
            case 2 :
                // 仅自己可见
                return TimelineModel::insert(
                    [
                        'user_id'   => intval($validatedData['user_id']),
                        'post_id'   => $validatedData['post_id'],
                        'status'    => 0,
                        'is_at'     => 0,
                        'create_at' => time()
                    ]);
                break;
            case 3 :
                // 谁可以看
                $whoCan = $validatedData['who_can'];
                $data [] = [
                    'user_id'   => intval($validatedData['user_id']),
                    'post_id'   => $validatedData['post_id'],
                    'status'    => 0,
                    'is_at'     => 0,
                    'create_at' => time()
                ];

                foreach($whoCan as $key => $value) {
                    $data[] = [
                        'user_id'   => intval($value),
                        'post_id'   => $validatedData['post_id'],
                        'status'    => 0,
                        'is_at'     => 0,
                        'create_at' => time()
                    ];
                }
                if(!empty($data)) {
                    return TimelineModel::batchInsert($data);
                }
                break;
            case 4 :
                // 谁不可以看
                $who_can_not = $validatedData['who_can_not'];
                $friends = FriendLogic::read([
                    'user_id'      =>$validatedData['user_id'],
                    'is_friend'    => 1,
                    'is_disable'   => 0,
                    'allow_linker' =>1
                ]);

                $data [] = [
                    'user_id'   => intval($validatedData['user_id']),
                    'post_id'   => $validatedData['post_id'],
                    'status'    => 0,
                    'is_at'     => 0,
                    'create_at' => time()
                ];
                foreach($friends as $friend) {
                    if(!in_array($friend['friend_id'], $who_can_not)) {
                        $data[] = [
                            'user_id'   => intval($friend['friend_id']),
                            'post_id'   => $validatedData['post_id'],
                            'status'    => 0,
                            'is_at'     => 0,
                            'create_at' => time()
                        ];
                    }
                }

                if(!empty($data)) {
                    return TimelineModel::batchInsert($data);
                }
                break;
            case 5 :
                // 提醒别人看
                $mention = $validatedData['mention'];

                $data [] = [
                    'user_id'   => intval($validatedData['user_id']),
                    'post_id'   => $validatedData['post_id'],
                    'status'    => 0,
                    'is_at'     => 0,
                    'create_at' => time()
                ];

                foreach($mention as $key => $value) {
                    $data[] = [
                        'user_id'   => intval($value),
                        'post_id'   => $validatedData['post_id'],
                        'status'    => 0,
                        'is_at'     => 1,
                        'create_at' => time()
                    ];
                }

                if(!empty($data)) {
                    return TimelineModel::batchInsert($data);
                }

                break;
        }
    }

    public static function read(array $where)
    {
        $timeline = [];
        $validatedData = ReadForm::validate($where);
        $offset = 10;
        $skip = ($where['page'] -1) * $offset;
        $cursor = TimelineModel::connection()->find($validatedData)->sort(['create_at'=> -1 ])->skip($skip)->limit($offset);

        if(!empty($cursor)) {
            foreach($cursor as $key => $document) {
                $document['_id'] = strval($document['_id']);
                $postId = new \MongoId($document['post_id']);
                $document['post'] = PostModel::connection()->findOne(['_id' => $postId]);
                // 如果另客圈状态发布者删除，则所有订阅者都不显示
                if($document['post']['status'] == 1) {
                    $document['post']['_id'] = strval($document['post']['_id']);
                    //@todo 评论分页
                    $comment = CommentModel::connection()->find(['post_id'=>$document['post_id']])->sort(['create_at'=>1]);
                    $document['post']['comment'] = iterator_to_array($comment);
                    unset($document['post_id']);
                    unset($document['post']['user_id']);
                    switch($document['post']['type']) {
                        case 7 :
                            // 转发另客圈状态
                            $forwardId = new \MongoId($document['post']['forward_id']);
                            $forward = PostModel::connection()->findOne(['_id' => $forwardId]);
                            if($forward['status'] == 1) {
                                // 如果最初发布者删除，或违规信息被删除，则全网都不显示
                                $forward['_id'] = strval($forward['_id']);
                                $document['post']['forward'] = $forward;
                                unset($document['post']['forward_id']);
                            }
                            break;
                        case 8 :
                            // 分享链接
                            break;
                    }
                }
                $timeline[] = $document;
            }
        }

        return $timeline;
    }
}