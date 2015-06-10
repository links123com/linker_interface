<?php

namespace App\Models;

class CommentModel extends Mongodb
{
    private $c = 'comment';
    private $schema = array(
        'user_id',         // 用户id|required|integer|min:1
        'post_id',         // 另客圈状态id|required|string|size:24
        'content',         // 评论内容|required|string
        'status',          // 评论是否删除|required|boolean
        'reply'=>array(    // 回复|array
            'rid',         // 回复id(md5(user_id.to.content))|required|string|size:32
            'user_id',     // 发表回复的用户id|required|integer|min:1
            'to',          // 接收回复的用户|required|integer|min:1
            'status',      // 回复是否删除required|boolean
            'content',     // 回复内容|required|string
            'create_at',   // 回复发布时间|required|integer
            'update_at'    // 回复状态更新时间|integer
        ),
        'create_at',       // 评论发布时间|required|integer
        'update_at'        // 评论状态更新时间|integer
    );

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }
}