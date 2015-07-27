<?php namespace App\Http\Controllers;

use App\Logic\Group\GroupLogic;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create(Request $request)
    {
        $data   = json_decode(file_get_contents("php://input"),true);
        $result = GroupLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}