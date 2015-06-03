<?php namespace App\Logic\Friend;

use App\Models\FriendModel;

class FriendLogic
{
    private static $friendModel = null;

    /**
     * 发送添加好友请求
     *
     * @param $data array 从客户端传递过来的数据
     * @return bool
     */
    public static function create($data)
    {
        self::$friendModel = new FriendModel();
        $validatedData = CreationForm::validate($data);

        return self::$friendModel->collection->batchInsert($validatedData);
    }

    /**
     * 更新好像配置信息
     *
     * @param $id object mongodb中的文档id
     * @param $data array 文档中需要更新的数据
     * @return bool
     */
    public static function update($id, $data)
    {
        $id = new \MongoId($id);
        self::$friendModel = new FriendModel();
        $validatedData = UpdateForm::validate($data);

        return self::$friendModel->update(array('_id' => $id), $validatedData);
    }

    /**
     * 删除好友信息文档中的一条文档
     *
     * @param $id 文档主键id
     * @return array|bool
     */
    public static function delete($id)
    {
        self::$friendModel = new FriendModel();
        $id = new \MongoId($id);

        return self::$friendModel->delete(array('_id' => $id));
    }

    public static function read(array $where)
    {
        self::$friendModel = new FriendModel();
        $validatedData = ReadForm::validate($where);
        $cursor = self::$friendModel->collection->find($validatedData);

        return iterator_to_array($cursor);
    }
}