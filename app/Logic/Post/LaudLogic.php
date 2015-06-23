<?php namespace App\Logic\Post;

use App\Models\PostModel;
use Illuminate\Support\Facades\Log;

class LaudLogic
{
    public static function create($data)
    {
        $validatedData = LaudForm::validate($data);
        $collection = PostModel::connection();
        $where = array('_id'=> new \MongoId($data['id']));
        $param = array('$addToSet'=>array('laud'=>$validatedData));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public static function delete($data)
    {
        $validatedData = LaudForm::validate($data);
        $collection = PostModel::connection();
        $where = array('_id'=>new \MongoId($validatedData['id']));
        $param = array('$pull'=>array('laud'=>$validatedData['user_id']));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}