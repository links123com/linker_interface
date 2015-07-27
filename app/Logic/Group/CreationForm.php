<?php namespace App\Logic\Group;

use Illuminate\Support\Facades\Validator;

class CreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'name'           => 'required|string|min:3',
            'description'    => 'required|string|min:16',
            'notify'         => 'string|max:120',
            'owner'          => 'required|integer|min:1',
            'administrator'  => 'array',
            'condition'      => 'required|integer|in:1,2,3'
        ]);
        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }
        return self::switchType($data);
    }

    private static function switchType($data)
    {
        $data['name']          = htmlspecialchars($data['name']);
        $data['description']   = htmlspecialchars($data['description']);
        $data['owner']         = intval($data['owner']);
        $data['status']        = 1;
        $data['condition']     = intval($data['condition']);
        $data['create_at']     = time();

        return $data;
    }
}