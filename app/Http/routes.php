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
    $app->post('/post', 'PostController@create');
    $app->post('/laud', 'PostController@laud');
    $app->post('/comment', 'CommentController@create');
    $app->post('/reply', 'CommentController@reply');

    $app->delete('/post/', 'PostController@delete');
    $app->delete('/laud/', 'PostController@deleteLaud');
    $app->delete('/comment', 'CommentController@delete');
    $app->delete('/reply', 'CommentController@deleteReply');

    $app->get('timeline/{id}', 'Timeline@showPost');
    $app->get('timeline/{id}/alumnus', '');

    $app->post('friend', 'FriendController@create');
    $app->patch('friend/{id}', 'FriendController@update');
    $app->delete('friend/{id}', 'FriendController@delete');

    $app->get('user/{id}/friend', 'FriendController@read');

    $app->get('timeline/{id}', 'TimelineController@read');

    $app->post('/complaint', 'ComplaintController@create');
});