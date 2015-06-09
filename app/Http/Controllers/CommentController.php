<?php namespace App\Http\Controllers;

use App\Logic\Forms\CommentCreationForm;
use App\Logic\Forms\CommentDeletionForm;
use App\Logic\Forms\ReplyCreationForm;
use App\Logic\Forms\ReplyDeletionForm;
use App\Models\CommentModel;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $model = null;

    public function __construct()
    {
        $this->model = new CommentModel();
    }

    public function create(Request $request, CommentCreationForm $commentCreationForm)
    {
        $postData = $commentCreationForm->validate($request->all());
        $model = $this->model;
        $result = $model->insert($postData);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function delete($id, Request $request, CommentDeletionForm $commentDeletionForm)
    {
        $data = $request->all();
        $data['id'] = $id;
        $postData = $commentDeletionForm->validate($data);
        $id = new \MongoId($postData['id']);
        $model = $this->model;
        $result = $model->update(array('_id'=>$id), array('status'=>0));

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function reply(Request $request, ReplyCreationForm $replyCreationForm)
    {
        $postData = $replyCreationForm->validate($request->all());
        $id     = $postData['id'];

        $model = $this->model;
        $result = $model->createReply($id, $postData);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function deleteReply(Request $request, ReplyDeletionForm $replyDeletionForm)
    {
        $postData = $replyDeletionForm->validate($request->all());

        $model = $this->model;
        $result = $model->deleteReply($postData['id'], $postData['rid']);

        if($request) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}