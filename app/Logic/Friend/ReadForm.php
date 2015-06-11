<?php namespace App\Logic\Friend;

use Illuminate\Support\Facades\Validator;

class ReadForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'         => 'required|integer|min:1',
            'allow_linker'    => 'boolean',
            'is_disable'      => 'boolean',
            'is_friend'       => 'boolean',
            'view_linker'     => 'boolean'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return self::cookData($data);
    }

    private static function cookData($data)
    {
        $temp = ['user_id' => intval($data['user_id'])];

        if(isset($data['allow_linker'])) {
            $temp['allow_linker'] = intval($data['allow_linker']);
        }

        if(isset($data['is_disable'])) {
            $temp['is_disable'] = intval($data['is_disable']);
        }

        if(isset($data['is_friend'])) {
            $temp['is_friend'] = intval($data['is_friend']);
        }

        if(isset($data['view_linker'])) {
            $temp['view_linker'] = intval($data['view_linker']);
        }

        return $temp;
    }
}