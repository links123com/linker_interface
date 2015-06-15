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
                    'type'     => $validatedData['type'],
                    'status'   => 0,
                    'is_at'    => 0,
                    'create_at'=>time()
                ];

                foreach($friends as $friend) {
                    $data[] = [
                        'user_id'   => intval($friend['friend_id']),
                        'post_id'   => $validatedData['post_id'],
                        'type'      => $validatedData['type'],
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
                        'type'      => $validatedData['type'],
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
                    'type'      => $validatedData['type'],
                    'status'    => 0,
                    'is_at'     => 0,
                    'create_at' => time()
                ];

                foreach($whoCan as $key => $value) {
                    $data[] = [
                        'user_id'   => intval($value),
                        'post_id'   => $validatedData['post_id'],
                        'type'      => $validatedData['type'],
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
                    'type'      => $validatedData['type'],
                    'status'    => 0,
                    'is_at'     => 0,
                    'create_at' => time()
                ];
                foreach($friends as $friend) {
                    if(!in_array($friend['friend_id'], $who_can_not)) {
                        $data[] = [
                            'user_id'   => intval($friend['friend_id']),
                            'post_id'   => $validatedData['post_id'],
                            'type'      => $validatedData['type'],
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
                    'type'      => $validatedData['type'],
                    'status'    => 0,
                    'is_at'     => 0,
                    'create_at' => time()
                ];

                foreach($mention as $key => $value) {
                    $data[] = [
                        'user_id'   => intval($value),
                        'post_id'   => $validatedData['post_id'],
                        'type'      => $validatedData['type'],
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
                $postId = new \MongoId($document['post_id']);
                $document['post'] = PostModel::connection()->findOne(['_id' => $postId]);
                $comment = CommentModel::connection()->find(['post_id'=>$document['post_id']])->sort(['create_at'=>1]);
                $document['post']['comment'] = iterator_to_array($comment);
                unset($document['post_id']);
                unset($document['post']['user_id']);
                $timeline[] = $document;
            }
        }

        return $timeline;
    }
}