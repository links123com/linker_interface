<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function delete($id)
    {
        $data['id'] = $id;
        $result = GroupLogic::delete($data);
        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function search(Request $request)
    {
        $data = $request->all();
        $result = GroupLogic::search($data);

        return response()->json($result, 200);
    }
}