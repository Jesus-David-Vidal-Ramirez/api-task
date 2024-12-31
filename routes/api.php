<?php

use App\Http\Controllers\StatusTaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\EnsureTokenIsValidSecond;
use App\Http\Middleware\JwtMiddleware;
use App\Models\StatusTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return 'API ON - IN THE PORT ' . $_ENV['APP_PORT'];
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/getUserByToken', [UserController::class, 'getUser']);

    Route::apiResource('/status-task', StatusTaskController::class)->middleware(CheckRole::class . ':admin,publisher');

    Route::apiResource('/task', TaskController::class)->names([
        'index'   => 'task.index',
        'store'   => 'task.store',
        'show'    => 'task.show',
        'update'  => 'task.update',
        'destroy' => 'task.destroy',
    ]);
});

// Route::get('/user/{category}', [TaskController::class, 'test'])->whereIn('category', ['id' => '[A-Z+a-z]+']);
Route::get('/user/{category}', [TaskController::class, 'test'])->where('category', '.*')->name('profile');

Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::get('/midel', function () {
        return 'midel';
    });

    Route::get('/users/{task}', function (Task $task) {
        // Uses first & second middleware...
        return $task;
    })->withoutMiddleware([EnsureTokenIsValid::class]);
});

// Route::get('/usuarios/{task}', [TaskController::class, 'test'])->withoutMiddleware([EnsureTokenIsValid::class]);
Route::get('/usuarioss/{task}/statusTask/{statusTask}', function (Task $task, StatusTask $statusTask) {

    
    return $task;
})->scopeBindings();
