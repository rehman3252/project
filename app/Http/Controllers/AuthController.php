<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all(),
            ], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

        ]);
        return response()->json([
            'status' => true,
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication Fail',
                'errors' => $validateUser->errors()->all(),
            ], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $Authuser = Auth::user();

            if (!$Authuser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Authenticated but user not found.',
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'User login successfully.',
                'token' => $Authuser->createToken('API Token')->plainTextToken,
                'token_type' => 'bearer',
                'user' => $Authuser,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Email & Password Not Matched',
        ], 401);
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Logout Successfully',
        ], 200);
    }
}
// AuthControler
