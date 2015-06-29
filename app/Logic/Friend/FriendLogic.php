<?php namespace App\Logic\Friend;

use App\Models\FriendModel;

class FriendLogic
{
    /**
     * 发送添加好友请求
     *
     * @param $data array 从客户端传递过来的数据
     * @return bool
     */
    public static function create($data)
    {
        $validatedData = CreationForm::validate($data);

        return FriendModel::batchInsert($validatedData);
    }

    /**
     * 更新好友配置信息
     *
     * @param $id object mongodb中的文档id
     * @param $data array 文档中需要更新的数据
     * @return bool
     */
    public static function update($id, $data)
    {
        $id = new \MongoId($id);
        $validatedData = UpdateForm::validate($data);

        // 这是确认添加好友的逻辑,验证好友的时候需要把主动添加的一方is_friend设置为1
        if(isset($validatedData['is_friend']) && $validatedData['is_friend'] == 1) {
            // 查找添加该用户为好友的用户
            $user   = FriendModel::connection()->findOne(['_id'=>$id]);
            FriendModel::update(
                ['user_id'=>$user['friend_id'], 'friend_id'=>$user['user_id']],
                ['is_friend'=>1]
            );
        }
        return FriendModel::update(array('_id' => $id), $validatedData);
    }

    /**
     * 删除好友信息文档中的一条文档
     *
     * @param $id 文档主键id
     * @return array|bool
     */
    public static function delete($id)
    {
        $id = new \MongoId($id);

        return FriendModel::delete(array('_id' => $id));
    }

    public static function read(array $where)
    {
        $validatedData = ReadForm::validate($where);
        $cursor = FriendModel::connection()->find($validatedData);

        return iterator_to_array($cursor, false);
    }

    public static function search($data)
    {
        $keyword = SearchForm::validate($data);

        $member = \DB::table('member')
            ->where('status', 1)
            ->where('from', 2)
            ->where('phone', $keyword)
            ->orWhere('linker', $keyword)
            ->first(['id', 'nickname', 'phone', 'email', 'linker', 'create_time', 'update_time']);
        return $member;
    }
}