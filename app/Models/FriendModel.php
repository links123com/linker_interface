<?php namespace App\Models;


class FriendModel extends Mongodb
{
    private $c = 'friend';
    private $schema = array(
        'user_id',         // required|integer|min:1
        'friend_id',       // required|integer|min:1
        'allow_linker',    // required|boolean
        'mark',            // string|size:1
        'is_disable',      // required|boolean
        'is_friend',       // required|boolean
        'special_friend',  // required|boolean
        'background',      // string|size:32
        'view_linker',     // required|boolean
        'create_at',       // required|integer|size:10
        'update_at'        // integer|size:10
    );

    public function __construct()
    {
        parent::__construct($this->c, $this->schema);
    }
}