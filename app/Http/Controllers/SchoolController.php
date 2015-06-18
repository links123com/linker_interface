<?php namespace App\Http\Controllers;

use App\Logic\School\SchoolLogic;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function read(Request $request)
    {
        $data = $request->all();
        $result = SchoolLogic::read($data);

        return response()->json($result, 200);
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = SchoolLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $data['id'] = $id;
        $result = SchoolLogic::update($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}