<?php namespace App\Logic\Complaint;

use Illuminate\Support\Facades\Validator;

class UpdateForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            '_id'    => 'required|string|size:24',
            'status' => 'required|boolean'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return self::cookData($data);
    }

    private static function cookData($data)
    {
        return array(
            'status'    => intval($data['status']),
            'update_at' => time()
        );
    }
}