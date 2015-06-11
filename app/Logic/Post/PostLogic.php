<?php namespace App\Logic\Post;

use App\Models\PostModel;
use App\Logic\Friend\FriendLogic;
use App\Models\TimelineModel;

class PostLogic
{
    public static function create($data)
    {
        $validatedData = CreationForm::validate($data);
        $result = PostModel::insert($validatedData);

        if($result) {
            // 根据客户端传递的参数确定post类型进行对应操作
            switch($validatedData['visibility']) {
                case 1 :
                    // 所有人都可见
                    $friends = FriendLogic::read([
                        'user_id'      =>$validatedData['user_id'],
                        'is_friend'    => 1,
                        'is_disable'   => 0,
                        'allow_linker' =>1
                    ]);

                    foreach($friends as $friend) {
                        $data[] = ['user_id' => intval($friend['friend_id']), 'post_id' => $result, 'status'=>0, 'is_at'=>0, 'create_at'=>time()];
                    }

                    if(!empty($data)) {
                        TimelineModel::batchInsert($data);
                    }
                    break;
                case 2 :
                    // 仅自己可见
                    TimelineModel::insert(
                        [
                            'user_id'=>intval($validatedData['user_id']),
                            'post_id'=>$result,
                            'status'=>0,
                            'is_at'=>0,
                            'create_at'=>time()
                        ]);
                    break;
                case 3 :
                    // 谁可以看
                    $whoCan = $validatedData['who_can'];
                    foreach($whoCan as $key => $value) {
                        $data[] = ['user_id' => intval($value), 'post_id' => $result, 'status'=>0, 'is_at'=>0, 'create_at'=>time()];
                    }
                    if(!empty($data)) {
                        TimelineModel::batchInsert($data);
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

                    foreach($friends as $friend) {
                        if(!in_array($friend['friend_id'], $who_can_not)) {
                            $data[] = ['user_id' => intval($friend['friend_id']), 'post_id' => $result, 'status'=>0, 'is_at'=>0, 'create_at'=>time()];
                        }
                    }

                    if(!empty($data)) {
                        TimelineModel::batchInsert($data);
                    }
                    break;
                case 5 :
                    // 提醒别人看
                    $mention = $validatedData['mention'];
                    foreach($mention as $key => $value) {
                        $data[] = ['user_id' => intval($value), 'post_id' => $result, 'status'=>0, 'is_at'=>1, 'create_at'=>time()];
                    }
                    if(!empty($data)) {
                        TimelineModel::batchInsert($data);
                    }
                    break;
            }
        }
        return $result;
    }

    public static function delete($id)
    {
        return PostModel::update(array('_id'=>new \MongoId($id)), array('status'=>0));
    }
}