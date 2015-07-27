<?php

namespace App\Models;


class GroupMemberModel extends MongoModel
{
    public static $collectionName = 'group_member';

    public static $schema = [
        'user_id',         // 用户id|required|integer|min:1
        'group_id',        // 群id|required|string|size:24
        'background',      // 聊天背景|string|size:32
        'integral',        // 群成员积分|integer|min:1
        'create_at',       // 创建时间|required|integer
        'update_at'        // 更新时间|integer
    ];
}