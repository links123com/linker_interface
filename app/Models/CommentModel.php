<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class CommentModel extends Mongodb
{
    private $c = 'comment';
    private $schema = array('user_id', 'post_id', 'content', 'status', 'reply', 'create_at', 'update_at');
    private $replySchema = array('rid', 'user_id', 'to', 'status', 'content','create_at', 'update_at');

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }

    /**
     * 对另客圈状态进行评论
     *
     * @param $id string 另客圈状态id
     * @param $data array 评论对象的字段内容
     * @return bool 评论失败返回false
     */
    public function createReply($id, $data)
    {
        $collection = $this->collection;
        $where = array('_id'=>new \MongoId($id));
        $data['create_at'] = time();
        $data['status'] = 1;
        $param = array('$addToSet'=>array('reply'=>$this->filterField($this->replySchema, $data)));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * 删除用户对另客圈状态的评论
     *
     * @param $id string 另客圈状态id
     * @param $rid string 另客圈评论回复id
     * @return bool 如果删除失败返回false，否则返回mongodb操作结果
     */
    public function deleteReply($id, $rid)
    {
        $collection = $this->collection;
        $where = array('reply.rid'=>$rid, '_id'=> new \MongoId($id));
        $param = array('$set'=>array('reply.$.status'=> 0));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}