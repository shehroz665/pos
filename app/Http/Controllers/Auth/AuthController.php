<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
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
            ]);   
            $password= Hash::make($request->password);
            $user = User::where('email', $request->email)->first();
            if (!$user || !password_verify($request->password, $user->password)) {
                return ApiResponse::error(false,'Invalid credentials',[],500);
            }
            $data= [
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ];
            return ApiResponse::success(true,'User Logged In Successfully',$data,200);      
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
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
}
