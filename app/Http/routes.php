<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->group(['namespace'=>'App\Http\Controllers'], function($app) {
    $app->post('/laud', 'PostController@laud');
    $app->post('/comment', 'CommentController@create');
    $app->post('/reply', 'CommentController@reply');

    $app->delete('/laud/', 'PostController@deleteLaud');
    $app->delete('/comment', 'CommentController@delete');
    $app->delete('/reply', 'CommentController@deleteReply');

});

// 发布另客圈状态、赞、评论
$app->post('/post', 'App\Http\Controllers\PostController@create');
$app->delete('/post/{id}', 'App\Http\Controllers\PostController@delete');

// 好友接口
$app->post('friend', 'App\Http\Controllers\FriendController@create');
$app->patch('friend/{id}', 'App\Http\Controllers\FriendController@update');
$app->delete('friend/{id}', 'App\Http\Controllers\FriendController@delete');
$app->get('user/{id}/friend', 'App\Http\Controllers\FriendController@read');

// 读取用户关注的和校友的另客圈状态
$app->get('timeline/{id}', 'App\Http\Controllers\TimelineController@read');
$app->get('timeline/{id}/alumnus', 'App\Http\Controllers\TimelineController@read');

// 举报接口
$app->post('/complaint', 'App\Http\Controllers\ComplaintController@create');
$app->patch('/complaint/{id}', 'App\Http\Controllers\ComplaintController@create');