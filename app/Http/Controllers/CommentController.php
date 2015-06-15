<?php namespace App\Http\Controllers;

use App\Logic\Comment\CommentLogic;
use App\Logic\Comment\ReplyLogic;
use App\Logic\Forms\ReplyCreationForm;
use App\Logic\Forms\ReplyDeletionForm;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create()
    {
        $data   = json_decode(file_get_contents("php://input"),true);
        $result = CommentLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function delete($id)
    {
        $data['id'] = $id;
        $result = CommentLogic::delete($data);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function reply($id)
    {
        $data = json_decode(file_get_contents("php://input"),true);
        $data['id'] = $id;
        $result = ReplyLogic::create($data);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function deleteReply($id, $rid)
    {
        $data = ['id' => $id, 'rid' => $rid];
        $result = ReplyLogic::delete($data);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}