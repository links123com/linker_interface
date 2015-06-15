<?php namespace App\Http\Controllers;

use App\Logic\Friend\FriendLogic;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function create()
    {
        $data   = json_decode(file_get_contents("php://input"),true);
        $result = FriendLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function update($id)
    {
        $data   = json_decode(file_get_contents("php://input"),true);
        $result = FriendLogic::update($id, $data);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function delete($id)
    {
        $result = FriendLogic::delete($id);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function read($id, Request $request)
    {
        $data = $request->all();
        $data['user_id'] = $id;
        $result = FriendLogic::read($data);

        return response()->json($result, 200);
    }
}