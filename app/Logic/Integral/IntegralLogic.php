<?php namespace App\Logic\Integral;

use App\Models\IntegralModel;
use App\Models\IntegralRulerModel;

class IntegralLogic
{
    public static function create(array $data)
    {
        $validatedData = CreationForm::validate($data);

        return IntegralModel::insert($validatedData);
    }

    public static function read(array $data)
    {
        $validatedData = ReadForm::validate($data);
        $skip = ($validatedData['page'] -1) * 10;
        $documents = IntegralModel::connection()
            ->find(['user_id' => $validatedData['user_id']])->sort(['create_at' => -1])->skip($skip)->limit(10);
        $integral = [];

        foreach($documents as $document) {
            $document['_id']          = strval($document['_id']);
            $document['ruler']        = IntegralRulerModel::connection()
                ->findOne(['_id' => new \MongoId($document['ruler'])]);
            $document['ruler']['_id'] = strval($document['ruler']['_id']);
            $integral[] = $document;
        }

        return $integral;
    }
}