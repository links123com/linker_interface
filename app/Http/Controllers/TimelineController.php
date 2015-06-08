<?php namespace App\Http\Controllers;

use App\Logic\Timeline\TimelineLogic;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function read($id, Request $request)
    {
        $data = $request->all();
        $data['user_id'] = $id;
        $result = TimelineLogic::read($data);

        return response()->json($result, 200);
    }
}