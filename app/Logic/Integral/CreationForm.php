<?php namespace App\Logic\Integral;

use Illuminate\Support\Facades\Validator;

class CreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'       => 'required|integer|min:1',
            'user_name'     => 'required|string|min:1',
            'ruler'         => 'required|string|size:24'
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
            'user_id'   => intval($data['user_id']),
            'user_name' => htmlspecialchars($data['user_name']),
            'ruler'     => strval($data['ruler']),
            'create_at' => time()
        ];
    }
}