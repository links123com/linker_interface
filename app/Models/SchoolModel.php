<?php namespace App\Models;


class SchoolModel extends MongoModel
{
    public static $collectionName = 'school';
    public static $schema     = [
        'name',            // 学校名|required|string|min:1
        'status',          // 学校是否审核通过|required|boolean
    ];
}