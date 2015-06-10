<?php namespace App\Logic\Comment;

use App\Models\CommentModel;

class CommentLogic
{
    public static function create($data)
    {
        $validatedData = CreationForm::validate($data);
        $model = new CommentModel();

        return $model->insert($validatedData);
    }

    public static function delete($data)
    {
        $validatedData = DeletionForm::validate($data);
        $model = new CommentModel();
        return $model->update(array('_id'=>$validatedData['id']), array('status'=>0));
    }
}