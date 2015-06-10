<?php namespace App\Models;


class FriendModel extends Mongodb
{
    private $c = 'friend';
    private $schema = array(
        'user_id',         // 用户id|required|integer|min:1
        'friend_id',       // 好友id|required|integer|min:1
        'allow_linker',    // 是否允许好友查看自己的另客圈|required|boolean
        'mark',            // 好友标记|string|size:1
        'is_disable',      // 是否在黑名单|required|boolean
        'is_friend',       // 是否是好友|required|boolean
        'special_friend',  // 是否是特殊好友|required|boolean
        'background',      // 聊天背景图像|string|size:32
        'view_linker',     // 是否查看好友另客圈|required|boolean
        'create_at',       // 好友添加时间|required|integer|size:10
        'update_at'        // 好友信息更新时间|integer|size:10
    );

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }
}