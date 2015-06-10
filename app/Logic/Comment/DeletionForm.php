<?php namespace App\Logic\Comment;

use Illuminate\Support\Facades\Validator;

class DeletionForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|string|size:24'
        ]);
        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }
        return self::switchType($data);
    }

    private static function switchType($data)
    {
        $data['id'] = new \MongoId($data['id']);
        return $data;
    }
}