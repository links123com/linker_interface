<?php namespace App\Http\Controllers;


use App\Logic\Post\LaudLogic;
use App\Logic\Post\PostLogic;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $result = PostLogic::create($request->all());

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function delete($id)
    {
        $result = PostLogic::delete($id);
        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function laud($id, Request $request)
    {
        $data = $request->all();
        $data['id'] = $id;

        $result = LaudLogic::create($data);
        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function deleteLaud($id, $userId)
    {
        $data = ['id'=>$id, 'user_id'=>$userId];
        $result = LaudLogic::delete($data);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}