<?php

namespace App\Models;

class PostModel extends MongoModel
{
    public static $collectionName = 'post';
    public static $schema = array(
        'user_id',               // '用户id|required|integer|min:1'
        'title',                 // '话题标题|required_if:type,6'
        'content',               // '发布状态内容|required_without_all:images,video,audio|string'
        'position',              // '发布状态时的位置required|string'
        'type',                  // '状态类型|required|integer'
        'device',                // '发布状态的设备|required|string'
        'notify',                // '通知给谁看的用户id列表|string'
        'images',                // '图像|required_if:type,1|array|max:9'
        'video',                 // '小视频|required_if:type,2|string'
        'audio',                 // '音频|required_if:type,4|string'
        'status',                // '状态是否删除|required|boolean'
        'laud',                  // '赞|array'
        'comment'                // '评论|array'
    );
}