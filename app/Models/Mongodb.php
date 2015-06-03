<?php namespace App\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Mongodb
{
    private $schema = array();
    public  $db = null;
    public  $collection = null;

    public function __construct($collection, $schema=array())
    {
        if(is_null($this->db)) {
            $config = Config::get('database.connections.mongodb');
            $connectionString = 'mongodb://'.$config['username'].':'.$config['password'].'@'.$config['host'] . ":".$config['port'].'/'.$config['database'];
            $mongo  = new \MongoClient($connectionString);
            $this->db = $mongo->selectDB($config['database']);
        }

        if(is_null($this->collection))
        {
            $this->collection = $this->db->selectCollection($collection);
        }

        $this->schema = $schema;
    }

    public function insert($data, $option=array())
    {
        $fields = $this->filterField($this->schema, $data);
        $fields['create_at'] = time();

        try {
            $this->collection->insert($fields, $option);
            return $fields['_id'];
        } catch(\MongoException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function batchInsert($data, $option=array())
    {
        $this->collection->batchInsert($data, $option);
    }

    public function update($criteria, $new_object, $options=array())
    {
        $fields = $this->filterField($this->schema, $new_object);
        $fields['update_at'] = time();

        try {
            $result = $this->collection->update($criteria, ['$set'=>$fields], $options);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function delete($criteria, $option=array())
    {
        try {
            $result = $this->collection->remove($criteria, $option);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    protected function filterField(array $schema, array $fields)
    {
        foreach($fields as $key => $value) {
            if(!in_array($key, $schema)) {
                unset($fields[$key]);
            }
        }
        return $fields;
    }
}