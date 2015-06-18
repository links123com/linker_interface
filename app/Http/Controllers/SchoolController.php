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
}