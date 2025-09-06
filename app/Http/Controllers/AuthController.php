<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            "username" => "string|min:4|max:12|required",
            "fullname" => "string|required",
            "email" => "email|unique:users|required",
            "password" => "string|min:6|required",
            "class" => "string|required",
        ]);

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
}
