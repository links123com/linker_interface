<?php namespace App\Logic\School;

use App\Models\SchoolModel;
use Illuminate\Support\Facades\Validator;

class SchoolLogic
{
    public static function read($data)
    {
        $validator = Validator::make($data, [
            'keyword'       => 'required|string|min:1',
            'type'          => 'integer|in:1,2,3,4'
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

    public static function create($data)
    {
        $validator = Validator::make($data, [
            'name'       => 'required|string|min:1',
            'type'       => 'required|integer|in:1,2,3,4',
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit;
        }

        return SchoolModel::insert([
            'name'   =>htmlspecialchars($data['name']),
            'type'   => intval($data['type']),
            'status' => 0
        ]);
    }

    public static function update($data)
    {
        $validator = Validator::make($data, [
            'id'        => 'required|string|size:24',
            'name'       => 'required|string|min:1',
            'status'     => 'required|boolean',
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit;
        }
        $id = new \MongoId($data['id']);
        $data = ['name' => htmlspecialchars($data['name']), 'status'=>intval($data['status'])];

        return SchoolModel::update(['_id' => $id], $data);
    }
}