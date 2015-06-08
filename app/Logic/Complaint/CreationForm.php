<?php namespace App\Logic\Complaint;

use Illuminate\Support\Facades\Validator;

class CreationForm extends Validator
{
    public static function validate($data)
    {
        $validator = Validator::make($data, [
            'plaintiff'     => 'required|integer|min:1',
            'defendant'     => 'required|integer|min:1',
            'type'          => 'required|integer|in:1,2,3,4,5',
            'description'   => 'string'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        return self::cookData($data);
    }

    private static function cookData($data)
    {
        $temp = [
            'plaintiff' => intval($data['plaintiff']),
            'defendant' => intval($data['defendant']),
            'type'      => intval($data['type']),
            'status'    => 0,
            'create_at' => time()
        ];

        if(!empty($data['description'])) {
            $temp['description'] = $data['description'];
        }

        return $temp;
    }
}