<?php namespace App\Http\Controllers;

use App\Logic\Forward\ForwardLogic;

class ForwardController extends Controller
{
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = ForwardLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}