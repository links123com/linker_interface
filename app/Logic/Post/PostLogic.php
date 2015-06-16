<?php namespace App\Logic\Post;

use App\Logic\Timeline\TimelineLogic;
use App\Models\PostModel;

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
        return PostModel::update(array('_id'=>new \MongoId($id)), array('status'=>0));
    }
}