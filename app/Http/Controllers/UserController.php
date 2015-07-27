<?php
namespace App\Http\Controllers;

use App\Models\GroupMemberModel;
use App\Models\GroupModel;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function group($id)
    {
        $validator = Validator::make(['user_id'=>$id], [
            'user_id' => 'required|integer|min:1'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        $cursor = GroupMemberModel::connection()->find(['user_id'=>intval($id)]);

        $temp = [];
        foreach($cursor as $document) {
            $id    = new \MongoId($document['group_id']);
            $group = GroupModel::connection()->findOne(['_id'=>$id, 'status' => 1]);
            if ($group) {
                $group['_id']      = strval($group['_id']);
                $temp[] = $group;
            }
        }

        return response()->json($temp, 200);
    }
}