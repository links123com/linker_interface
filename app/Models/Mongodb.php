<?php namespace App\Models;

use Illuminate\Support\Facades\Config;

class Mongodb
{
    protected $db = null;

    public function __construct()
    {
        if(is_null($this->db)) {
            $config = Config::get('database.connections.mongodb');
            $schema = 'mongodb://'.$config['username'].':'.$config['password'].'@'.$config['host'] . ":".$config['port'].'/'.$config['database'];
            $mongo  = new \MongoClient($schema);
            $this->db = $mongo->selectDB($config['database']);
        }
        return $this->db;
    }
}