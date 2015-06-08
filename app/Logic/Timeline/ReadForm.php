<?php namespace App\Logic\Timeline;

use Illuminate\Support\Facades\Validator;

class ReadForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'         => 'required|integer|min:1'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return self::cookData($data);
    }

    private static function cookData($data)
    {
        $temp = ['user_id' => $data['user_id']];

        if(isset($data['allow_linker'])) {
            $temp['allow_linker'] = intval($data['allow_linker']);
        }

        return $temp;
    }
}