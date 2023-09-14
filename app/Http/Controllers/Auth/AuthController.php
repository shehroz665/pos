<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller
{
    use HasApiTokens;
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'remember_me' => 'boolean',
            ]);   
            // $user = User::where('email', $request->email)->first();
            // if (!$user || !password_verify($request->password, $user->password)) {
            //     return ApiResponse::error(false,'Invalid credentials',[],500);
            // }
            if (!Auth::attempt($request->only('email', 'password'))) {
                return ApiResponse::error(false, 'Invalid credentials', [], 401);
            }
            $user = Auth::user();
            $token = $request->boolean('remember_me') ?
            $user->createToken("API TOKEN", ['*'])->plainTextToken :
            $user->createToken("API TOKEN")->plainTextToken;

            $data = [
                'token' => $token,
            ];
            session(['api_token' => $token]);
            return ApiResponse::success(true,'User Logged In Successfully',$data,200);      
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],401);
        }
    }
    
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
            ]);
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            DB::commit();
            return ApiResponse::success(true,'User registered successfully',$user,200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        }

    }  
    public function logout(Request $request)
    {
        try {
           $user = auth()->user();
            if (!$user) {
                return ApiResponse::error(false, 'User not authenticated', [], 401);
            }
            $user->tokens()->delete();
            return ApiResponse::success(true, 'User logged out successfully');
        } catch (\Throwable $e) {
            return ApiResponse::error(false, $e->getMessage(), [], 500);
        }
    }
}
