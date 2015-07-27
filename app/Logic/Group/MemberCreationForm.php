<?php namespace App\Logic\Group;

use Illuminate\Support\Facades\Validator;

class MemberCreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'     => 'required|integer|min:1',
            'group_id'    => 'required|string|size:24'
        ]);
        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }
        return self::switchType($data);
    }

    private static function switchType($data)
    {
        $data['user_id']       = intval($data['user_id']);
        $data['create_at']     = time();

        return $data;
    }
}