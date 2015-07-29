<?php namespace App\Logic\Group;

use App\Models\GroupApplicationModel;

class ApplicationLogic
{
    public static function create($data)
    {
        $validatedData = ApplicationCreationForm::validate($data);
        $result = GroupApplicationModel::insert($validatedData);

        return $result;
    }
}