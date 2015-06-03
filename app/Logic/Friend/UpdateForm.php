<?php namespace App\Logic\Friend;

use Illuminate\Support\Facades\Validator;

class UpdateForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'id'              => 'string|size:24',
            'allow_linker'    => 'boolean',
            'mark'            => 'string|size:1',
            'is_disable'      => 'boolean',
            'is_friend'       => 'boolean',
            'special_friend'  => 'boolean',
            'background'      => 'string|size:32',
            'view_linker'     => 'boolean',
            'update_at'       => 'integer|size:10'
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

        if(isset($data['allow_linker'])) {
            $temp['allow_linker'] = intval($data['allow_linker']);
        }

        if(isset($data['mark'])) {
            $temp['mark'] = htmlspecialchars($data['mark']);
        }

        if(isset($data['is_disable'])) {
            $temp['is_disable'] = intval($data['is_disable']);
        }

        if(isset($data['is_friend'])) {
            $temp['is_friend'] = intval($data['is_friend']);
        }

        if(isset($data['special_friend'])) {
            $temp['special_friend'] = intval($data['special_friend']);
        }

        if(isset($data['background'])) {
            $temp['background'] = htmlspecialchars($data['background']);
        }

        if(isset($data['view_linker'])) {
            $temp['view_linker'] = intval($data['view_linker']);
        }

        $temp['update_at'] = time();

        return $temp;
    }
}