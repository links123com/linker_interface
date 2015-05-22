<?php

namespace App\Models;

class PostModel extends Mongodb
{
    private $collection = null;

    public function __construct()
    {
        parent::__construct();
        if(is_null($this->collection)) {
            $this->collection = parent::$db->selectCollection('post');
        }
    }
}