<?php

namespace App\Models;


class GroupApplicationModel extends MongoModel
{
    public static $collectionName = 'group_application';

    public static $schema = [
        'user_id',         // 用户id|required|integer|min:1
        'admin_id',        // 处理加群信息的管理员id|integer|min:1
        'group_id',        // 群id|required|string|size:24
        'description',     // 申请加入群的申请消息|string|min:1
        'owner',           // 群主|integer|min:1
        'create_at',       // 创建时间|required|integer
        'update_at'        // 更新时间|integer
    ];
}