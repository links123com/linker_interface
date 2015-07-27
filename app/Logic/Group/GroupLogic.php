<?php namespace App\Logic\Group;

use App\Models\GroupModel;

class GroupLogic
{
    public static function create($data)
    {
        $validatedData = CreationForm::validate($data);

        $result = GroupModel::insert($validatedData);

        // todo:软事物处理
        if($result) {
            // 把群主添加到群中
            GroupMemberLogic::create(['user_id'=>$data['owner'], 'group_id'=>strval($result)]);
        }

        return $result;
    }

    public static function delete($data)
    {
        $validatedData = DeletionForm::validate($data);
        $where  = array('_id'=>new \MongoId($validatedData['id']));
        $param  = array('status'=>0);
        $result = GroupModel::update($where, $param);

        return $result;
    }
}