<?php namespace App\Http\Controllers;

use App\Logic\Forms\LaudForm;
use App\Logic\Forms\PostDeletionForm;
use App\Models\PostModel;
use App\Models\TimelineModel;
use Illuminate\Http\Request;
use App\Logic\Forms\PostCreationForm;

class PostController extends Controller
{
    private $postModel = null;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

    public function create(Request $request, PostCreationForm $postCreationForm)
    {
        $postData = $postCreationForm->validate($request->all());
        $postModel = $this->postModel;
        $result = $postModel->insert($postData);

        if($result) {
            // 根据客户端传递的参数确定post类型进行对应操作
            switch($postData['visibility']) {
                case 1 :
                    // 所有人都可见
                    break;
                case 2 :
                    // 仅自己可见
                    $timelineModel = new TimelineModel();
                    $timelineModel->batchInsert(
                        [
                            'user_id'=>$postData['user_id'],
                            'post_id'=>$result,
                            'status'=>0,
                            'is_at'=>0
                        ]);
                    break;
                case 3 :
                    // 谁可以看
                    $whoCan = $postData['who_can'];
                    foreach($whoCan as $key => $value) {
                        $data[] = ['user_id' => $value, 'post_id' => $result, 'status'=>0, 'is_at'=>0];
                    }
                    $timelineModel = new TimelineModel();
                    $timelineModel->batchInsert($data);
                    break;
                case 4 :
                    // 谁不可以看
                    break;
                case 5 :
                    // 提醒别人看
                    $mention = $postData['mention'];
                    foreach($mention as $key => $value) {
                        $data[] = ['user_id' => $value, 'post_id' => $result, 'status'=>0, 'is_at'=>1];
                    }
                    $timelineModel = new TimelineModel();
                    $timelineModel->batchInsert($data);
                    break;
                default :
            }
            exit;
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function delete(Request $request, PostDeletionForm $postDeletionForm)
    {
        $postData = $postDeletionForm->validate($request->all());
        $id = new \MongoId($postData['id']);
        $postModel = $this->postModel;
        $result = $postModel->update(array('_id'=>$id), array('status'=>0));

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function laud(Request $request, LaudForm $laudForm)
    {
        $postData = $laudForm->validate($request->all());
        $id     = $postData['id'];
        $userId = $postData['user_id'];

        $postModel = $this->postModel;
        $result = $postModel->createLaud($id,$userId);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function deleteLaud(Request $request, LaudForm $laudForm)
    {
        $postData = $laudForm->validate($request->all());
        $id     = $postData['id'];
        $userId = $postData['user_id'];

        $postModel = $this->postModel;
        $result = $postModel->deleteLaud($id, $userId);

        if($request) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}