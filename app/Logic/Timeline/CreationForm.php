<?php namespace App\Logic\Timeline;

use Illuminate\Support\Facades\Validator;

class CreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'  =>'required|integer|min:1',
            'post_id'    =>'required|size:24',
            'type'  =>'required|integer|in:1,2,3',
            //1:所有人可见;2:仅自己;3:谁可以看;4:谁不可以看;5:提醒给谁看
            'visibility' => 'required|integer|in:1,2,3,4,5',
            'who_can'    => 'required_if:visibility,3|array',
            'who_can_not'=> 'required_if:visibility,4|array',
            'mention'    => 'required_if:visibility,5|array',
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
        $data['status']  = 0;

        return $data;
    }
}