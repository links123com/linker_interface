<?php namespace App\Http\Controllers;

use App\Logic\Friend\FriendLogic;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function create(Request $request)
    {
        $result = FriendLogic::create($request->all());

        if($request) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function update(Request $request, $id)
    {
        $result = FriendLogic::update($id, $request->all());

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