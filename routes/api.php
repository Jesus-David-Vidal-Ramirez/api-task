<?php

use App\Http\Controllers\StatusTaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;
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

    Route::apiResource('/status-task', StatusTaskController::class);
    Route::apiResource('/task', TaskController::class);
    Route::put('/task-status/{id}', [TaskController::class, 'updatedStatusTask']);
});
