<?php namespace App\Http\Controllers;

use App\Models\GroupApplicationModel;
use App\Models\GroupMemberModel;
use Illuminate\Http\Request;
use App\Logic\Group\GroupLogic;
use App\Logic\Group\GroupMemberLogic;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function create()
    {
        $data   = json_decode(file_get_contents("php://input"),true);
        $result = GroupLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function addMember($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $data['group_id'] = $id;
        $result = GroupMemberLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function readMember($id)
    {
        $validator = Validator::make(['id'=>$id], [
            'id' => 'required|string|size:24'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        $cursor = GroupMemberModel::connection()->find(['group_id'=>$id]);

        return response()->json(iterator_to_array($cursor, false), 200);
    }

    public function updateMember($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $data['group_id'] = $id;
        $validator = Validator::make($data, [
            'user_id'    => 'required|integer|min:1',
            'background' => 'string|size:32,',
            'integral'   => 'integer|min:1'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }

        if(isset($data['background'])) {
            $param['background'] = htmlspecialchars($data['background']);
        }

        if(isset($data['integral'])) {
            $param['integral'] = intval($data['integral']);
        }
        $param['update_at'] = time();
        $where = ['group_id'=>$data['group_id'], 'user_id'=>$data['user_id']];
        $result = GroupMemberModel::update($where, $param);

        return response()->json($result, 200);
    }

    public function delete($id)
    {
        $data['id'] = $id;
        $result = GroupLogic::delete($data);
        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function search(Request $request)
    {
        $data = $request->all();
        $result = GroupLogic::search($data);

        return response()->json($result, 200);
    }

    public function apply($id)
    {
        $cursor = GroupApplicationModel::connection()->find(
            [
                'group_id'=>$id,
                'status'=> 0
            ])
            ->sort(['create_at'=> -1 ]);

        return response()->json(iterator_to_array($cursor, false));
    }

    public function updateApplication($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $data['id'] = $id;
        $validator = Validator::make($data, [
            'id'         => 'required|string|size:24',
            'user_id'    => 'required|integer|min:1',
            'admin_id'   => 'required|integer|min:1',
            'status'     => 'integer|min:1'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit();
        }
        $param['admin_id'] = $data['admin_id'];
        $param['status']   = 1;
        $param['update_at'] = time();
        $where = ['_id'=>new \MongoId($id)];
        $result = GroupMemberModel::update($where, $param);

        if($result) {
            $result = GroupMemberLogic::create([
                'user_id'   => intval($data['user_id']),
                'group_id'  => $data['id'],
                'condition' => 1
            ]);
        }

        return response()->json($result, 200);
    }
}