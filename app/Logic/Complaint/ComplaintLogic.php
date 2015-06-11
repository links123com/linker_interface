<?php namespace App\Logic\Complaint;

use App\Models\ComplaintModel;

class ComplaintLogic
{
    public static function create(array $data)
    {
        $validatedData = CreationForm::validate($data);

        return ComplaintModel::insert($validatedData);
    }

    public static function update(array $data)
    {
        $validatedData = UpdateForm::validate($data);
        $id = new \MongoId($data['_id']);

        return ComplaintModel::update(['_id' => $id], $validatedData);
    }
}