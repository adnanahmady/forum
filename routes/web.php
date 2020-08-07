<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@update')->name('register.confirm');

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/threads', 'ThreadController@store')->middleware('most-confirm-email');
Route::get('/threads/create', 'ThreadController@create')->middleware('most-confirm-email');
Route::get('/threads/search', 'SearchThreadController@index');
Route::get('/threads/{channel?}', 'ThreadController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::patch('/threads/{channel}/{thread}', 'ThreadController@update');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::delete('/threads/{channel}/{thread}/subscribes', 'SubscribeController@destroy');
Route::post('/threads/{channel}/{thread}/subscribes', 'SubscribeController@store');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');

Route::post('/threads/{thread}/lock', 'LockThreadController@store')->name('thread.lock');
Route::delete('/threads/{thread}/lock', 'LockThreadController@destroy')->name('thread.unlock');

Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('best-replies.store');

Route::post('/replies/{reply}/favorites', 'FavoriteController@store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('reply.destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');

Route::get('profiles/notifications', 'UserNotificationController@index');
Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');
Route::delete('profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');

Route::get('/api/users', 'Api\UserController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar');
