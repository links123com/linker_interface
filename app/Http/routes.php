<?php

// 发布另客圈状态
$app->post('/post', 'App\Http\Controllers\PostController@create');
$app->delete('/post/{id}', 'App\Http\Controllers\PostController@delete');
$app->get('/post', 'App\Http\Controllers\PostController@read');

// 赞接口
$app->post('/post/{id}/laud', 'App\Http\Controllers\PostController@laud');
$app->delete('/post/{id}/laud/{userId}', 'App\Http\Controllers\PostController@deleteLaud');

// 发表评论
$app->post('/comment', 'App\Http\Controllers\CommentController@create');
$app->delete('/comment/{id}', 'App\Http\Controllers\CommentController@delete');

// 回复评论
$app->post('/comment/{id}/reply', 'App\Http\Controllers\CommentController@reply');
$app->delete('/comment/{id}/reply/{rid}', 'App\Http\Controllers\CommentController@deleteReply');

// 好友接口
$app->post('friend', 'App\Http\Controllers\FriendController@create');
$app->put('friend/{id}', 'App\Http\Controllers\FriendController@update');
$app->delete('friend/{id}', 'App\Http\Controllers\FriendController@delete');
$app->get('user/{id}/friend', 'App\Http\Controllers\FriendController@read');
$app->get('friend', 'App\Http\Controllers\FriendController@search');

// 读取用户关注的和校友的另客圈状态
$app->get('timeline/{id}', 'App\Http\Controllers\TimelineController@read');
$app->get('timeline/{id}/alumnus', 'App\Http\Controllers\TimelineController@read');

// 举报接口
$app->post('/complaint', 'App\Http\Controllers\ComplaintController@create');
$app->put('/complaint/{id}', 'App\Http\Controllers\ComplaintController@update');

// 积分接口
$app->post('user/{id}/integral','App\Http\Controllers\IntegralController@create');
$app->get('user/{id}/integral','App\Http\Controllers\IntegralController@read');

// 学校模糊匹配接口
$app->post('school', 'App\Http\Controllers\SchoolController@create');
$app->put('school/{id}', 'App\Http\Controllers\SchoolController@update');
$app->get('school', 'App\Http\Controllers\SchoolController@read');

// 外教相关接口
$app->get('/teacher', 'App\Http\Controllers\TeacherController@read');
$app->get('/teacher/{id}/group', 'App\Http\Controllers\TeacherController@group');
$app->delete('teacher/{id}', 'App\Http\Controllers\TeacherController@delete');

// 群相关接口
$app->get("/user/{id}/group", 'App\Http\Controllers\UserController@group');
$app->get("/group", 'App\Http\Controllers\GroupController@search');
$app->delete("/group/{id}", 'App\Http\Controllers\GroupController@delete');
$app->post("/group", 'App\Http\Controllers\GroupController@create');
$app->post('/group/{id}/member', 'App\Http\Controllers\GroupController@addMember');
$app->get('/group/{id}/member', 'App\Http\Controllers\GroupController@readMember');
$app->put('/group/{id}/member', 'App\Http\Controllers\GroupController@updateMember');
$app->get('/group/{id}/application', 'App\Http\Controllers\GroupController@apply');
$app->put('/group/{id}/application', 'App\Http\Controllers\GroupController@updateApplication');

// 获取用户在web上的session
$app->get("/user/{id}/session", 'App\Http\Controllers\UserController@session');
