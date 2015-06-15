<?php

namespace App\Models;


class TimelineModel extends MongoModel
{
    public static $collectionName = 'timeline';

    public static $schema = [
        'user_id',         // 用户id|required|integer|min:1
        'post_id',         // 另客圈状态id|required|string|size:24
        'type',            // 类型（1：普通；2：分享链接；3：转发post|required|integer
        'status',          // 是否已读（0：未读；1：已读|boolean
        'is_at',           // 是否为@消息(0:不是；1是)|boolean
        'create_at',       // 创建时间|required|integer
        'update_at'        // 更新时间|integer
    ];
}