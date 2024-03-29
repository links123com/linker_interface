<?php namespace App\Logic\Post;

use Illuminate\Support\Facades\Validator;

class CreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'user_id'    => 'required|integer|min:1',
            'user_name'  => 'required|string|min:1',
            'title'      => 'required_if:type,6',
            'content'    => 'required_without_all:images,video,audio,forward,url|string',
            'position'   => 'required|string',
            'type'       => 'required|integer',
            'device'     => 'required|string',
            'notify'     => 'string',
            'images'     => 'required_if:type,1|array|max:9',
            'video'      => 'required_if:type,2|string',
            'audio'      => 'required_if:type,4|string',
            'laud'       => 'array',
            'comment'    => 'array',
            'forward_id' => 'required_if:type,7|string|size:24',
            'url'        => 'required_if:type,8|url',
            'university'     => 'string|min:1',
            'high_school'    => 'string|min:1',
            'middle_school'  => 'string|min:1',
            'primary_school' => 'string|min:1'
        ]);
        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }
        return self::switchType($data);
    }

    private static function switchType($data)
    {
        $data['user_id']   = intval($data['user_id']);
        $data['user_name'] = htmlspecialchars($data['user_name']);
        $data['type']      = intval($data['type']);
        $data['status']    = 1;
        $data['create_at'] = time();

        return $data;
    }
}