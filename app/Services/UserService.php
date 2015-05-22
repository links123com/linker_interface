<?php namespace App\Services;

use Illuminate\Support\Facades\Redis;

class UserService
{
    private $redis = null;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }
}