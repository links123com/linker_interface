<?php namespace App\Models;


class ComplaintModel extends Mongodb
{
    private $c = 'complaint';
    private $schema     = array(
        'plaintiff',       // 投诉人|required|integer|min:1 原告
        'defendant',       // 被投诉人|required|integer|min:1 被告
        'type',            // 投诉类型|required|integer|in:1,2,3,4,5 投诉类型（1：色情; 2:谣言；3:恶意营销; 4：诱导分享；5：诅咒谩骂）
        'description',     // 投诉详细描述信息|string|size:1
        'status',          // 投诉处理状态|required|boolean 投诉是否已经处理
        'create_at',       // 投诉创建时间|required|integer|size:10
        'update_at'        // 投诉处理时间integer|size:10
    );

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }
}