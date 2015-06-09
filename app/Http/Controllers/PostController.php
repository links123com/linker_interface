<?php namespace App\Http\Controllers;

use App\Logic\Forms\LaudForm;
use App\Logic\Forms\PostDeletionForm;
use App\Logic\Friend\FriendLogic;
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
                    $friends = FriendLogic::read([
                        'user_id'      =>$postData['user_id'],
                        'is_friend'    => 1,
                        'is_disable'   => 0,
                        'allow_linker' =>1
                    ]);

                    foreach($friends as $friend) {
                        $data[] = ['user_id' => intval($friend['friend_id']), 'post_id' => $result, 'status'=>0, 'is_at'=>0, 'create_at'=>time()];
                    }

                    if(!empty($data)) {
                        $timelineModel = new TimelineModel();
                        $timelineModel->batchInsert($data);
                    }
                    break;
                case 2 :
                    // 仅自己可见
                    $timelineModel = new TimelineModel();
                    $timelineModel->insert(
                        [
                            'user_id'=>intval($postData['user_id']),
                            'post_id'=>$result,
                            'status'=>0,
                            'is_at'=>0,
                            'create_at'=>time()
                        ]);
                    break;
                case 3 :
                    // 谁可以看
                    $whoCan = $postData['who_can'];
                    foreach($whoCan as $key => $value) {
                        $data[] = ['user_id' => intval($value), 'post_id' => $result, 'status'=>0, 'is_at'=>0, 'create_at'=>time()];
                    }
                    if(!empty($data)) {
                        $timelineModel = new TimelineModel();
                        $timelineModel->batchInsert($data);
                    }
                    break;
                case 4 :
                    // 谁不可以看
                    $who_can_not = $postData['who_can_not'];
                    $friends = FriendLogic::read([
                        'user_id'      =>$postData['user_id'],
                        'is_friend'    => 1,
                        'is_disable'   => 0,
                        'allow_linker' =>1
                    ]);

                    foreach($friends as $friend) {
                        if(!in_array($friend['friend_id'], $who_can_not)) {
                            $data[] = ['user_id' => intval($friend['friend_id']), 'post_id' => $result, 'status'=>0, 'is_at'=>0, 'create_at'=>time()];
                        }
                    }

                    if(!empty($data)) {
                        $timelineModel = new TimelineModel();
                        $timelineModel->batchInsert($data);
                    }
                    break;
                case 5 :
                    // 提醒别人看
                    $mention = $postData['mention'];
                    foreach($mention as $key => $value) {
                        $data[] = ['user_id' => intval($value), 'post_id' => $result, 'status'=>0, 'is_at'=>1, 'create_at'=>time()];
                    }
                    if(!empty($data)) {
                        $timelineModel = new TimelineModel();
                        $timelineModel->batchInsert($data);
                    }
                    break;
                default :
            }
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function delete($id, Request $request, PostDeletionForm $postDeletionForm)
    {
        $data = $request->all();
        $data['id'] = $id;
        $postDeletionForm->validate($data);
        $id = new \MongoId($data['id']);
        $postModel = $this->postModel;
        $result = $postModel->update(array('_id'=>$id), array('status'=>0));

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function laud($id, Request $request, LaudForm $laudForm)
    {
        $data = $request->all();
        $data['id'] = $id;
        $postData = $laudForm->validate($data);
        $id     = $postData['id'];
        $userId = $postData['user_id'];

        $postModel = $this->postModel;
        $result = $postModel->createLaud($id,$userId);

        if($result) {
            return response()->json($result, 201);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }

    public function deleteLaud($id, $userId, LaudForm $laudForm)
    {
        $data['id'] = $id;
        $data['user_id'] = $userId;
        $postData = $laudForm->validate($data);
        $id     = $postData['id'];
        $userId = $postData['user_id'];

        $postModel = $this->postModel;
        $result = $postModel->deleteLaud($id, $userId);

        if($result) {
            return response()->json($result, 200);
        }

        return response()->json(array('message'=>'Server internal error'), 500);
    }
}