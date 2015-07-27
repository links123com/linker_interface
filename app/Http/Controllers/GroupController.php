<?php namespace App\Http\Controllers;

use App\Logic\Group\GroupLogic;
use App\Logic\Group\GroupMemberLogic;

class GroupController extends Controller
{
    public function create()
    {
        $data   = json_decode(file_get_contents("php://input"),true);
        $result = GroupLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function addMember($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $data['group_id'] = $id;
        $result = GroupMemberLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}