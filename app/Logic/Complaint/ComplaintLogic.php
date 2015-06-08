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
}