<?php namespace App\Logic\Forward;

use Illuminate\Support\Facades\Validator;

class CreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required|integer|min:1',
            'type'    => 'required|integer|in:2,3', // 1：普通；2：分享链接；3：转发post
            'post_id' => 'required_if:type,3|string|size:24',
            'url'     => 'required_if:type,2|url'
        ]);
        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }
        return self::switchType($data);
    }

    private static function switchType($data)
    {
        $temp = [
            'user_id' => intval($data['user_id']),
            'type'    => intval($data['type'])
        ];

        if(isset($data['url'])) {
            $temp['url'] = $data['url'];
        }

        if(isset($data['post_id'])) {
            $temp['post_id'] = $data['post_id'];
        }

        return $temp;
    }
}