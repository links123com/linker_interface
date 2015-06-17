<?php namespace App\Logic\Post;

use Illuminate\Support\Facades\Validator;

class ReadForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'         => 'required|integer|min:1',
            'page'            => 'required|integer|min:1',
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return self::cookData($data);
    }

    private static function cookData($data)
    {
        return [
            'user_id' => intval($data['user_id']),
            'page'    => intval($data['page'])
        ];
    }
}