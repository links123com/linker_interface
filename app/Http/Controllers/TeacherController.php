<?php namespace App\Http\Controllers;

use App\Models\TeacherModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function read(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'page'         => 'required|integer|min:1',
            'per_page'      => 'required|integer|min:1',
            'teacher_type' => 'required|integer'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit;
        }

        $teachers = TeacherModel::with(["member"=>function($query){
            $query->select(['id', 'nickname', 'phone', 'email', 'linker', 'create_time']);
            $query->where('status', '=', 1);
            $query->orderBy('create_time', 'desc');
        }]) ->where('tip_school', '=', $data['teacher_type'])
            ->get([
                'user_id',
                'gender',
                'birthday_year',
                'birthday_month',
                'birthday_day',
                'zodiac',
                'signature',
                'constellation'
            ])
            ->forPage($data['page'],$data['per_page']);

        return response()->json($teachers, 200);
    }
}