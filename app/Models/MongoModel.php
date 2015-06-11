<?php namespace App\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MongoModel
{
    private static $collection = null;
    private static $collectionName = '';
    private static $schema = [];

    public static function connection()
    {
        if(is_null(self::$collection))
        {
            $config = Config::get('database.connections.mongodb');
            $connectionString = 'mongodb://'.$config['username'].':'.$config['password'].'@'.$config['host'] . ":".$config['port'].'/'.$config['database'];
            $mongo  = new \MongoClient($connectionString);
            $db = $mongo->selectDB($config['database']);
            self::$collection = $db->selectCollection(static::$collectionName);
        }

        return self::$collection;
    }

    public static function insert($data, $option=array())
    {
        $fields = self::filterField(static::$schema, $data);

        try {
            self::connection()->insert($fields, $option);
            return $fields['_id'];
        } catch(\MongoException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public static function batchInsert($data, $option=array())
    {
        return self::connection()->batchInsert($data, $option);
    }

    public static function update($criteria, $new_object, $options=array())
    {
        $fields = self::filterField(static::$schema, $new_object);

        try {
            $result = self::connection()->update($criteria, ['$set'=>$fields], $options);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public static function delete($criteria, $option=array())
    {
        try {
            $result = self::connection()->remove($criteria, $option);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    private static function filterField(array $schema, array $fields)
    {
        foreach($fields as $key => $value) {
            if(!in_array($key, $schema)) {
                unset($fields[$key]);
            }
        }
        return $fields;
    }
}