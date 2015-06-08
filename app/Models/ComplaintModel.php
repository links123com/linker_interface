<?php namespace App\Models;


class ComplaintModel extends Mongodb
{
    private $c = 'complaint';
    private $schema     = array(
        'plaintiff',       // required|integer|min:1 原告
        'defendant',       // required|integer|min:1 被告
        'type',            // required|integer|in:1,2,3,4,5 投诉类型（1：色情; 2:谣言；3:恶意营销; 4：诱导分享；5：诅咒谩骂）
        'description',     // string|size:1
        'status',          // required|boolean 投诉是否已经处理
        'create_at',       // required|integer|size:10
        'update_at'        // integer|size:10
    );

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }
}