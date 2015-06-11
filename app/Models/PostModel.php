<?php

namespace App\Models;

class PostModel extends Mongodb
{
    private $c = 'post';
    private $schema = array('user_id', 'title', 'content',
        'position', 'type', 'device', 'notify', 'images', 'video',
        'audio', 'status', 'laud', 'comment'
    );

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }
}