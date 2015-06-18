<?php namespace App\Logic\School;

use App\Models\SchoolModel;
use Illuminate\Support\Facades\Validator;

class SchoolLogic
{
    public static function read($data)
    {
        $validator = Validator::make($data, [
            'keyword'       => 'required|string|min:1',
            'type'          => 'integer|in:1,2,3'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit;
        }

        $keyword = strval($data['keyword']);
        if(isset($data['type'])) {
            $where = ['status' => 1, 'type'=> intval($data['type']), 'name' => new \MongoRegex("/^$keyword/i")];

        } else {
            $where = ['status' => 1, 'name' => new \MongoRegex("/^$keyword/i")];
        }

        $cursor = SchoolModel::connection()->find($where);

        return iterator_to_array($cursor, false);
    }
}