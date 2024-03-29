<?php namespace App\Http\Controllers;

use App\Models\GroupModel;
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
            'per_page'     => 'required|integer|min:1',
            'teacher_type' => 'required|integer'
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit;
        }

        $teachers = TeacherModel::with(["member"=>function($query){
            $query->select(['id', 'nickname', 'phone', 'email', 'linker', 'create_time']);
            $query->where('status', '=', 1);
            $query->where('from', '=', 2);
            $query->orderBy('create_time', 'desc');
        }]) ->where('is_teacher', '=', 1)
            ->where('teacher_type', '=', $data['teacher_type'])
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

    public function delete($id)
    {
        /**
         * @var $teacher TeacherModel
         */
        $teacher = TeacherModel::find($id);
        $teacher->is_teacher = 0;
        $result = $teacher->save();

        if($result) {
            return response()->json(['updatedExisting'=>true, 'n'=>1, 'err'=>null, 'ok'=>1], 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function group($id)
    {
        $data = ['owner'=> intval($id)];
        $validator = Validator::make($data, [
            'owner'         => 'required|integer|min:1',
        ]);

        if($validator->fails()) {
            response()->json($validator->messages(), 422)->send();
            exit;
        }

        $cursor = GroupModel::connection()->find(['owner'=> $data['owner']]);

        return json_encode(iterator_to_array($cursor, false));
    }
}