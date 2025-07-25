<?php

use App\Events\NewTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('new-task', function(){
    $task = [
        'title' => 'Event',
        'description' => 'Task Event',
        'user_id' => 1,
        // 'title' => ,
    ];
    NewTask::dispatch('title','mensaje');
});