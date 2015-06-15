<?php namespace App\Http\Controllers;

use App\Logic\Post\LaudLogic;
use App\Logic\Post\PostLogic;

class PostController extends Controller
{
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"),true);
        $result = PostLogic::create($data);

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

    public function laud($id)
    {
        $data = json_decode(file_get_contents("php://input"),true);
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