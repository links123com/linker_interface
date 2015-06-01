<?php namespace App\Logic\Forms;

use Illuminate\Support\Facades\Validator;

class ReplyCreationForm extends Validator
{
    public function validate($data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|string|size:24',
            'rid'=> 'required|string|size:32',
            'user_id' =>'required|integer|min:1',
            'content' => 'required|string',
            'to'      => 'required|integer|min:1'
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
        $data['user_id'] = intval($data['user_id']);
        $data['content'] = htmlspecialchars($data['content']);
        $data['to'] = intval($data['to']);

        return $data;
    }
}