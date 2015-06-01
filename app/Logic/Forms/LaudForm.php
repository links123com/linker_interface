<?php namespace App\Logic\Forms;

use Illuminate\Support\Facades\Validator;

class LaudForm extends Validator
{
    public function validate($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|string|size:24',
            'user_id'  =>'required|integer|min:1'
        ]);
        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return $this->switchType($data);
    }

    private function switchType($data)
    {
        $data['user_id'] = intval($data['user_id']);
        $data['id']    = strval($data['id']);

        return $data;
    }
}