<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Pest\Plugins\Only;

class AuthController extends Controller
{
    public function register(RegisterAuthRequest $validatedData)
    {
        try {
            $user = User::create([
                "username" => $validatedData['username'],
                "fullname" => $validatedData['fullname'],
                "email" => $validatedData['email'],
                "password" => Hash::make($validatedData['password']),
                "class" => $validatedData['class']
            ]);

            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                "status" => true,
                "message" => "berhasil mendaftarkan akun",
                "data" => [
                    "token" => $token,
                    "username" => $user->username
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal register akun",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ], 500);
        }
    }

    public function login(LoginAuthRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    "status" => false,
                    "message" => "Email / Password salah"
                ], 401);
            }

            $user = User::where('email', $request['email'])->first();
            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                "status" => true,
                "message" => "berhasil login akun",
                "data" => [
                    "token" => $token,
                    "username" => $user->username
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal login akun",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            return response()->json([
                "status" => true,
                "message" => "Berhasil logout",
                "data" => []
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal logout akun",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ], 500);
        }
    }
}
