<?php namespace App\Logic\Timeline;

use Illuminate\Support\Facades\Validator;

class CreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'  =>'required|integer|min:1',
            'post_id'    =>'required|string|size:24',
            'type'  =>'required|integer|in:1,2,3',
            'status'   =>'required|boolean',
            'is_at'  =>'required|boolean'
        ]);
        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }
        return self::switchType($data);
    }

    private static function switchType($data)
    {
        $data['user_id'] = intval($data['user_id']);
        $data['post_id'] = strval($data['post_id']);
        $data['type']    = intval($data['type']);

        return $data;
    }
}