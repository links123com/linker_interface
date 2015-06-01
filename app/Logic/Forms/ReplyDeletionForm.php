<?php namespace App\Logic\Forms;

use Illuminate\Support\Facades\Validator;

class ReplyDeletionForm extends Validator
{
    public function validate($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|string|size:24',
            'rid'=> 'required|string|size:32'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return $this->switchType($data);
    }

    private function switchType($data)
    {
        $data['id']    = strval($data['id']);
        $data['rid']   = strval($data['rid']);

        return $data;
    }
}