<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->store) {
                return response()->json([
                    "status" => false,
                    "message" => "Anda sudah memiliki toko. Setiap user hanya boleh memiliki satu toko."
                ], 409);
            }
            $validatedRequest = $request->validate([
                "store_name" => "string|required|unique:stores",
                "description" => "string|required",
                "profile_url" => "required|image|mimes:png,jpg,jpeng|max:3000",
                "banner_url" => "required|image|mimes:png,jpg,jpeng|max:3000"
            ]);

            $profile_path = $request->file('profile_url')->store('img', 'public');
            $profile_url = url(Storage::url($profile_path));

            $banner_path = $request->file('banner_url')->store('img', 'public');
            $banner_url = url(Storage::url($banner_path));

            $storeUser = Store::create([
                "user_id" => $user->id,
                "profile_url" => $profile_url,
                "banner_url" => $banner_url,
                "store_name" => $validatedRequest['store_name'],
                "description" => $validatedRequest['description'],
                "slug" => Str::slug($validatedRequest['store_name'])
            ]);

            return response()->json([
                "status" => true,
                "message" => "Berhasil mendaftarkan toko",
                "data" => [
                    "store_name" => $storeUser->store_name,
                    "user_id" => $storeUser->user_id
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal mendaftarkan toko",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ], 500);
        }
    }
}
