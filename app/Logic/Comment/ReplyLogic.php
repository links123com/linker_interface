<?php namespace App\Logic\Comment;

use App\Models\CommentModel;
use Illuminate\Support\Facades\Log;

class ReplyLogic
{
    public static function create($data)
    {
        $validatedData = ReplyCreationForm::validate($data);
        $collection = (new CommentModel())->collection;
        $where = array('_id'=> new \MongoId($data['id']));
        $param = array('$addToSet'=>array('reply'=>$validatedData));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public static function delete($data)
    {
        $validatedData = ReplyDeletionForm::validate($data);
        $collection = (new CommentModel())->collection;
        $where = array('reply.rid'=>$validatedData['rid'], '_id'=> new \MongoId($validatedData['id']));
        $param = array('$set'=>array('reply.$.status'=> 0));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}