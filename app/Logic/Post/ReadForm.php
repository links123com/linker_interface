<?php namespace App\Logic\Post;

use Illuminate\Support\Facades\Validator;

class ReadForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'         => 'required_without_all:university,high_school,middle_school,primary_school|integer|min:1',
            'university'      => 'required_without_all:user_id,high_school,middle_school,primary_school|string|min:1',
            'high_school'     => 'required_without_all:user_id,university,middle_school,primary_school|string|min:1',
            'middle_school'   => 'required_without_all:user_id,university,high_school,primary_school|string|min:1',
            'primary_school'  => 'required_without_all:user_id,university,high_school,middle_school|string|min:1',
            'page'            => 'required|integer|min:1',
            'last_pull'       => 'required_with:toward|integer|min:1431705600',
            'toward'          => 'required|string|in:up,down'
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

        if(isset($data['university'])) {
            $temp['university'] = $data['university'];
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

        if(isset($data['toward'])) {
            if($data['toward'] == 'up') {
                $temp['create_at'] = array('$lte' => intval($data['last_pull']));
            }

            if($data['toward'] == 'down') {
                $temp['create_at'] = array('$gte' => intval($data['last_pull']));
            }
        }

        return $temp;
    }
}