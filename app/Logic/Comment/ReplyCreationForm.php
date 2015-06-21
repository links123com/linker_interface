<?php namespace App\Logic\Comment;

use Illuminate\Support\Facades\Validator;

class ReplyCreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'id'        => 'required|string|size:24',
            'rid'       => 'required|string|size:32',
            'user_id'   => 'required|integer|min:1',
            'user_name' => 'required|string|min:1',
            'content'   => 'required|string',
            'to'        => 'required|integer|min:1',
            'to_name'   => 'required|string|min:1',
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return self::switchType($data);
    }

    private static function switchType($data)
    {
        $data['rid']         = strval($data['rid']);
        $data['user_id']     = intval($data['user_id']);
        $data['user_name']   = htmlspecialchars($data['user_name']);
        $data['content']     = htmlspecialchars($data['content']);
        $data['to']          = intval($data['to']);
        $data['to_name']     = htmlspecialchars($data['to_name']);
        $data['status']      = 1;
        $data['create_time'] = time();

        return $data;
    }
}