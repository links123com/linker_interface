<?php

namespace App\Models;


class GroupModel extends MongoModel
{
    public static $collectionName = 'group_conflict';

    public static $schema = [
        'name',            // 群名称|required|string|min:3
        'description',     // 群描述|required|string|min:16
        'notify',          // 群公告|string|max:120
        'owner',           // 群主|integer|min:1
        'administrator',   // 管理员|array
        'condition',       // 加群条件|required|in:1,2,3(1：允许任何人加群；2：不允许任何人加群；3：需要通过验证才能加群)
        'status',          // 群状态|required|bool
        'recommendation',  // 是否为推荐群|required|boolean(0:不是;1:是)
        'create_at',       // 创建时间|required|integer
        'update_at'        // 更新时间|integer
    ];
}