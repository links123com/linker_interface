<?php namespace App\Logic\Friend;

use Illuminate\Support\Facades\Validator;

class CreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'       => 'required|integer|min:1',
            'friend_id'     => 'required|integer|min:1'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return self::cookData($data);
    }

    private static function cookData($data)
    {
        return array(
            array(
                'user_id'        =>intval($data['user_id']),
                'friend_id'      =>intval($data['friend_id']),
                'allow_linker'   => 1,
                'is_disable'     => 0,
                'is_friend'      => 1,
                'special_friend' => 0,
                'view_linker'    => 1,
                'create_at'      =>time()
            ),
            array(
                'user_id'        => intval($data['friend_id']),
                'friend_id'      => intval($data['user_id']),
                'allow_linker'   => 1,
                'is_disable'     => 0,
                'is_friend'      => 0,
                'special_friend' => 0,
                'view_linker'    => 1,
                'create_at'      =>time()
            )
        );
    }
}