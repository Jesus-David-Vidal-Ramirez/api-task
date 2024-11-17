<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return 'API ON - IN THE PORT '. $_ENV['APP_PORT'];
});
