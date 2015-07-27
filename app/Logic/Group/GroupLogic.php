<?php namespace App\Logic\Group;

use App\Models\GroupModel;

class GroupLogic
{
    public static function create($data)
    {
        $validatedData = CreationForm::validate($data);

        $result = GroupModel::insert($validatedData);

        // todo:软事物处理
        if($result) {
            // 把群主添加到群中
            GroupMemberLogic::create(['user_id'=>$data['owner'], 'group_id'=>strval($result)]);
        }

        return $result;
    }

    public static function delete($data)
    {
        $validatedData = DeletionForm::validate($data);
        $where  = array('_id'=>new \MongoId($validatedData['id']));
        $param  = array('status'=>0);
        $result = GroupModel::update($where, $param);

        return $result;
    }

    public static function search($data)
    {
        $validator = Validator::make($data, [
            'keyword'       => 'required|string|min:1',
            'page'          => 'integer|min:1'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit;
        }

        $keyword = strval($data['keyword']);
        $where = ['status' => 1, 'name' => new \MongoRegex("/^$keyword/i")];

        $offset = 10;
        $skip = isset($validator['page'])?($where['$validator'] -1) * $offset:0;
        $cursor = GroupModel::connection()->find($where)->sort(['create_at'=> -1 ])->skip($skip)->limit($offset);

        return iterator_to_array($cursor, false);
    }
}