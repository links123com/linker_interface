<?php namespace App\Models;


class SchoolModel extends MongoModel
{
    public static $collectionName = 'school';
    public static $schema     = [
        'name',            // 学校名|required|string|min:1
        'status',          // 学校是否审核通过|required|boolean
        'type'             // 学校类型{1:大学;2高中;3:初中;4:小学}|required|integer|in:1,2,3,4
    ];
}