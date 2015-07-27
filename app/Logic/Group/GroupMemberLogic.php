<?php namespace App\Logic\Group;

use App\Models\GroupMemberModel;

class GroupMemberLogic
{
    public static function create($data)
    {
        $validatedData = MemberCreationForm::validate($data);

        $result = GroupMemberModel::insert($validatedData);

        return $result;
    }
}