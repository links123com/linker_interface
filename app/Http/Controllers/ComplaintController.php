<?php namespace App\Http\Controllers;

use App\Logic\Complaint\ComplaintLogic;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"),true);
        $result = ComplaintLogic::create($data);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function update($id)
    {
        $postData = json_decode(file_get_contents("php://input"),true);
        $postData['_id'] = $id;
        $result = ComplaintLogic::update($postData);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}