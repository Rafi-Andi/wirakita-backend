<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(Request $request, $id)
    {

        try {
            $user = User::findOrFail($id);

            $responseData = [
                "id" => $user->id,
                "username" => $user->username,
                "fullname" => $user->fullname,
                "class" => $user->class,
            ];

            $authUser = auth('sanctum')->user();
            if($authUser && $authUser->id === $user->id){
                $responseData['email'] = $user->email;
            }

            return response()->json([
                "status" => true,
                "message" => "Berhasil mendapatkan profil",
                "data" => $responseData
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal mendapatkan detail akun",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ], 500);
        }
    }
}
