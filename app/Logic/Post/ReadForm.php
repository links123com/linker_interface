<?php namespace App\Logic\Post;

use Illuminate\Support\Facades\Validator;

class ReadForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'         => 'required_without_all:high_school,middle_school,primary_school|integer|min:1',
            'high_school'     => 'required_without_all:user_id,middle_school,primary_school|string|min:1',
            'middle_school'   => 'required_without_all:user_id,high_school,primary_school|string|min:1',
            'primary_school'  => 'required_without_all:user_id,high_school,middle_school|string|min:1',
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
        $temp = [];
        if(isset($data['user_id'])) {
            $temp['user_id'] = intval($data['user_id']);
        }

        if(isset($data['high_school'])) {
            $temp['high_school'] = $data['high_school'];
        }

        if(isset($data['middle_school'])) {
            $temp['middle_school'] = $data['middle_school'];
        }

        if(isset($data['primary_school'])) {
            $temp['primary_school'] = $data['primary_school'];
        }

        return $temp;
    }
}