<?php

namespace App\Models;

class PostModel extends MongoModel
{
    public static $collectionName = 'post';
    public static $schema = array(
        'user_id',
        'title',
        'content',
        'position',
        'type',
        'device',
        'notify',
        'images',
        'video',
        'audio',
        'status',
        'laud',
        'comment'
    );
}