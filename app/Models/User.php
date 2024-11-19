<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->belongsTo(role::class);
    }

    // Verifica si el usuario tiene el rol específico
    public function hasRole($role)
    {
        return $this->role->name === $role;
    }

    /// Services
    public static function login( $request ){
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) return response()->json(['error' => 'Incorrect credentials', 'status' => 'error'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token.', 'status' => 'error'], 500);
        }
        $oUser = Auth::user();

        try {
            if($oUser->status !== 0) return response()->json(['error' => 'Inactive user', 'status' => 'error'], Response::HTTP_BAD_REQUEST);
            
            //TODO Spatie
            // Obtener roles del usuario
            // $roles = $oUser->getRoleNames(); // Esto devolverá una colección de nombres de roles

            // // Obtener permisos del usuario
            // $permissions = $oUser->getAllPermissions(); // Esto devolverá una colección de permisos
            // $activity = [
            //     'method' => 'POST',
            //     'action' => 'Login',
            //     'description' => "El usuario inicia sesión: {$oUser->name}"
            // ];
        } catch (JWTException $th) {
            return response()->json(['error' => 'Could not create token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $oUser->access_token = $token;
        // $oUser->aParametros = ParametroController::getAll();

        return response()->json($oUser, Response::HTTP_OK);
    }

    public static function getUser(): JsonResponse {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(compact('user'));
    }

    public static function register($request): JsonResponse {
        // Se puede separa la logica en las rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'), Response::HTTP_CREATED);
    }

}
