<?php

namespace App\Models;


class TimelineModel extends Mongodb
{
    private $c = 'timeline';
    private $schema = array(
        'user_id',         // 用户id|required|integer|min:1
        'post_id',         // 另客圈状态id|required|string|size:24
        'type',            // 类型（1：转发链接；2：评论；3：话题|required|integer
        'status',          // 是否已读（0：未读；1：已读|boolean
        'is_at',           // 是否为@消息(0:不是；1是)|boolean
        'create_at',       // 创建时间|required|integer
        'update_at'        // 更新时间|integer
    );

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }
}