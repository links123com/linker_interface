<?php

namespace App\Models;


class TimelineModel extends Mongodb
{
    private $c = 'timeline';
    private $schema = array('user_id', 'post_id', 'type', 'status', 'is_at', 'create_at', 'update_at');

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }
}