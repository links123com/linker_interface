<?php namespace App\Logic\Post;

use App\Models\PostModel;
use Illuminate\Support\Facades\Log;

class LaudLogic
{
    public static function create($data)
    {
        $validatedData = CreationLaudForm::validate($data);
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
        $validatedData = DeletionLaudForm::validate($data);
        $collection = PostModel::connection();
        $where = array('_id'=>new \MongoId($validatedData['id']));
        // 数组内嵌对象时需要用array结构，array结构中匹配需要删除的对象
        $param = array('$pull'=>array('laud'=>array('user_id'=>$validatedData['user_id'])));

        try {
            $result = $collection->update($where, $param);
            return $result;
        } catch(\MongoCursorException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}