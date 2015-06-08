<?php namespace App\Logic\Timeline;

use App\Models\CommentModel;
use App\Models\PostModel;
use App\Models\TimelineModel;

class TimelineLogic
{
    public static function read(array $where)
    {
        $timeline = [];
        $timelineModel = new TimelineModel();
        $postModel     = new PostModel();
        $commentModel  = new CommentModel();
        $validatedData = ReadForm::validate($where);
        $cursor = $timelineModel->collection->find($validatedData)->sort(['create_at'=> -1 ]);

        if(!empty($cursor)) {
            foreach($cursor as $key => $document) {
                $postId = new \MongoId($document['post_id']);
                $document['post'] = $postModel->collection->findOne(['_id' => $postId]);
                $comment = $commentModel->collection->find(['post_id'=>$document['post_id']]);
                $document['post']['comment'] = iterator_to_array($comment);
                unset($document['post_id']);
                unset($document['post']['user_id']);
                $timeline[] = $document;
            }
        }

        // 更新好友状态的最后拉取时间
        return $timeline;
    }
}