<?php

namespace App\Models;


/**
 * 会员积分规则文档
 *
 * 为方便对历史记录进行回溯，会员积分规则只允许新增，不允许修改。
 *
 * Class IntegralRulerModel
 * @package App\Models
 */
class IntegralRulerModel extends MongoModel
{
    public static $collectionName = 'integral_ruler';

    public static $schema = [
        'name',            // 积分规则名|required|string
        'description',     // 积分规则描述|required|string
        'score',           // 积分|required|integer
        'status',          // 积分规则是否审核通过
        'create_at',       // 创建时间|required|integer
        'update_at'        // 更新时间|required|integer
    ];
}