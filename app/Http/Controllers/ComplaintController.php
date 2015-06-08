<?php namespace App\Http\Controllers;

use App\Logic\Complaint\ComplaintLogic;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function create(Request $request)
    {
        $result = ComplaintLogic::create($request->all());

        if($request) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function update($id, Request $request)
    {
        $postData = $request->all();
        $postData['_id'] = $id;
        $result = ComplaintLogic::update($postData);

        if($request) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}