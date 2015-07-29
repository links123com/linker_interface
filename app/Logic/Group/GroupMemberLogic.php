<?php namespace App\Logic\Group;

use App\Models\GroupMemberModel;

class GroupMemberLogic
{
    public static function create($data)
    {
        $validatedData = MemberCreationForm::validate($data);

        // 判断是否需要经过验证才能加群
        switch($validatedData['condition']) {
            case 1 :
                $result = GroupMemberModel::insert($validatedData);
                break;
            case 2 :
                // 禁止任何人加入群
                $result = ['message' => 'anybody can\'t join'];
                break;
            case 3 :
                // 需要经过验证才能加入群
                $result = ApplicationLogic::create([
                    'user_id'     => $validatedData['user_id'],
                    'group_id'    => $validatedData['group_id'],
                    'description' => $validatedData['description']
                ]);
                break;
            default :
                die(__FILE__.__LINE__);
        }

        return $result;
    }
}