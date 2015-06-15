<?php namespace App\Logic\Forward;

use App\Logic\Timeline\TimelineLogic;

class ForwardLogic
{
    public static function create($data)
    {
        $validatedData = CreationForm::validate($data);

        switch($validatedData['type']) {
            case 2 :
                // 分享链接
                break;
            case 3 :
                // 转发另客圈状态
                return TimelineLogic::create($data);
            default;
        }
    }
}