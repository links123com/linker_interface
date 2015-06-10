<?php namespace App\Logic\Comment;

use Illuminate\Support\Facades\Validator;

class ReplyDeletionForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|string|size:24',
            'rid'=> 'required|string|size:32'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return $data;
    }
}