<?php namespace App\Logic\Complaint;

use App\Models\ComplaintModel;

class ComplaintLogic
{
    public static function create(array $data)
    {
        $validatedData = CreationForm::validate($data);
        $complaintModel = new ComplaintModel();
        return $complaintModel->insert($validatedData);
    }

    public static function update(array $data)
    {
        $validatedData = UpdateForm::validate($data);
        $id = new \MongoId($data['_id']);
        $complaintModel = new ComplaintModel();
        return $complaintModel->update(['_id' => $id], $validatedData);
    }
}