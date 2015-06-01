<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class PostModel extends Mongodb
{
    private $c = 'post';
    private $schema = array('user_id', 'title', 'content',
        'position', 'type', 'device', 'notify', 'images', 'video',
        'audio', 'status', 'laud', 'comment'
    );

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }

    /**
     * 对另客圈状态点赞
     *
     * @param $id string 另客圈状态id
     * @param $userId integer 点赞的用户id
     * @return bool 点赞失败返回false
     */
    public function createLaud($id, $userId)
    {
        $collection = $this->collection;
        $where = array('_id'=>new \MongoId($id));
        $param = array('$addToSet'=>array('laud'=>$userId));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * 删除用户对另客圈状态的赞
     *
     * @param $id string 另客圈状态id
     * @param $userId integer 用户id
     * @return bool 如果删除失败返回false，否则返回mongodb操作结果
     */
    public function deleteLaud($id,$userId)
    {
        $collection = $this->collection;
        $where = array('_id'=>new \MongoId($id));
        $param = array('$pull'=>array('laud'=>$userId));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}