<?php namespace App\Logic\Timeline;

use Illuminate\Support\Facades\Validator;

class ReadForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'         => 'required|integer|min:1',
            'last_pull'       => 'integer|min:1431705600'
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

        if(isset($data['last_pull'])) {
            $temp['create_at'] = array('$gte' => intval($data['last_pull']));
        }

        return $temp;
    }
}