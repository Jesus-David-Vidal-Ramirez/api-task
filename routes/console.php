<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Corriendo un crob cada cierto tiempo
Schedule::call(function () {
    $data = DB::table('users')->get();
    $login = Http::post('http://localhost/api/login',[
	    "email" => "ramirez_vidal@outlook.com",
        "password" => "123456789"
        
    ]);
    $response = Http::withToken($login['access_token'])->get('http://localhost/api/task');
    Log::info('Console Crob ' . json_encode($data));
    Log::info('Console Crob login' . $login);
    Log::info('Console Crob response' . $response);
})->dailyAt('12:36');


