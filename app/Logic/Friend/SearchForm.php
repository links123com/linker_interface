<?php namespace App\Logic\Friend;

use Illuminate\Support\Facades\Validator;

class SearchForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'keyword'       => 'required|string|min:1'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return strval($data['keyword']);
    }
}