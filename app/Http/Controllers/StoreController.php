<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            "store_name" => "string|required|unique:stores",
            "description" => "string|required",
            "profile_url" => "required|image|mimes:png,jpg,jpeng|max:3000",
            "banner_url" => "required|image|mimes:png,jpg,jpeng|max:3000"
        ]);
        try {
            $user = $request->user();

            if ($user->store) {
                return response()->json([
                    "status" => false,
                    "message" => "Anda sudah memiliki toko. Setiap user hanya boleh memiliki satu toko."
                ], 409);
            }

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

    public function show($slug)
    {
        try {
            $store = Store::where('slug', $slug)->first();
            if (!$store) {
                return response()->json([
                    "status" => false,
                    "message" => "Toko tidak tersedia",
                    "data" => []
                ], 404);
            }

            $reviews = $store->reviews;
            $product = $store->products;

            $rating_average = $reviews->avg('rating');
            $rating_count = $reviews->count();
            $rating_average = round($rating_average, 1);

            return response()->json([
                "status" => true,
                "message" => 'Berhasil mendapatkan detail toko',
                "data" => [
                    "rating_count" => $rating_count,
                    "rating_average" => $rating_average,
                    "store" => new StoreResource($store),                     
                    "products" => ProductResource::collection($product)
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal mendapatkan detail toko",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ], 500);
        }
    }
}
