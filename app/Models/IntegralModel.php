<?php

namespace App\Models;


class IntegralModel extends MongoModel
{
    public static $collectionName = 'integral';

    public static $schema = [
        'user_id',         // 用户id|required|integer|min:1
        'ruler',           // 积分规则|required|string|size:24
        'create_at',       // 创建时间|required|integer
    ];
}