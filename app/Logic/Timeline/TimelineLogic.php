<?php namespace App\Logic\Timeline;

use App\Models\CommentModel;
use App\Models\PostModel;
use App\Models\TimelineModel;

class TimelineLogic
{
    public static function read(array $where)
    {
        $timeline = [];
        $postModel     = new PostModel();
        $commentModel  = new CommentModel();
        $validatedData = ReadForm::validate($where);
        $offset = 10;
        $skip = ($where['page'] -1) * $offset;
        $cursor = TimelineModel::connection()->find($validatedData)->sort(['create_at'=> -1 ])->skip($skip)->limit($offset);

        if(!empty($cursor)) {
            foreach($cursor as $key => $document) {
                $postId = new \MongoId($document['post_id']);
                $document['post'] = $postModel->collection->findOne(['_id' => $postId]);
                $comment = $commentModel->collection->find(['post_id'=>$document['post_id']])->sort(['create_at'=>1]);
                $document['post']['comment'] = iterator_to_array($comment);
                unset($document['post_id']);
                unset($document['post']['user_id']);
                $timeline[] = $document;
            }
        }

        return $timeline;
    }
}