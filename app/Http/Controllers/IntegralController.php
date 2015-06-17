<?php namespace App\Http\Controllers;

use App\Logic\Integral\IntegralLogic;
use Illuminate\Http\Request;

class IntegralController extends Controller
{
    public function create($userId)
    {
        $data = json_decode(file_get_contents("php://input"),true);
        $data['user_id'] = $userId;

        $result = IntegralLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function read(Request $request, $userId)
    {
        $data = $request->all();
        $data['user_id'] = $userId;
        $result = IntegralLogic::read($data);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}