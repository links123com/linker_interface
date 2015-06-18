<?php namespace App\Http\Controllers;

use App\Models\SchoolModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function read(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'keyword'       => 'required|string|min:1'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages(), 422)->send();
        }

        $keyword = $data['keyword'];
        $cursor = SchoolModel::connection()->find(['name' => new \MongoRegex("/^$keyword/i")]);
        return response()->json(iterator_to_array($cursor, false));
    }
}