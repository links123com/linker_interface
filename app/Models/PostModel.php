<?php

namespace App\Models;

class PostModel extends MongoModel
{
    public static $collectionName = 'post';
    public static $schema = array(
        'user_id',               // '发布状态的用户id|required|integer|min:1'
        'title',                 // '话题标题|required_if:type,6'
        'content',               // '发布状态内容|required_without_all:images,video,audio|string'
        'position',              // '发布状态时的位置required|string'
        'type',                  // '状态类型（1：图片；2：小视频；3：纯文本；4：语音；5：秘密；6：话题（话题由客户端匹配#话题标题#得出话题类型)；7:转发post;8:转发公众账号里面的url|required|integer'
        'device',                // '发布状态的设备|required|string'
        'notify',                // '通知给谁看的用户id列表|string'
        'images',                // '图像|required_if:type,1|array|max:9'
        'video',                 // '小视频|required_if:type,2|string'
        'audio',                 // '音频|required_if:type,4|string'
        'status',                // '状态是否删除|required|boolean'
        'laud',                  // '赞|array'
        'comment',               // '评论|array'
        'forward_id',            // '转发的内容id|string|size:24'
        'url'                    // '分享链接的链接地址|url'
    );
}