<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

// use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        return User::register($request );
    }

    // User login
    public function login(Request $request)
    {
        return User::login($request);
    }

    // Get authenticated user
    public function getUser()
    {
        return User::getUser();
    }

    // User logout
    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
    
}
