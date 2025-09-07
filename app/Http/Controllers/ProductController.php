<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // $products = Product::
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $validatedRequest = $request->validate([
                "product_name"  => "required|string|max:255",
                "description"  => "required|string|max:255",
                "price"         => "required|integer|min:0",
                "image_url"     => "required|image|mimes:png,jpg,jpeg|max:3000",
                "product_stock" => "required|integer|min:0",
                "category"      => [
                    "required",
                    "string",
                    Rule::in(['Makanan', 'Minuman', 'Alat Tulis', 'Barang/Produk', 'Jasa'])
                ]
            ]);

            $user = $request->user();
            $store = $user->store;

            if(!$store){
                return response()->json([
                    "status"=> false,
                    "message"=> "anda tidak mempunyai toko"
                ], 403);
            }

            $imagePath = $request->file('image_url')->store('img', 'public');
            $imageUrl = url(Storage::url($imagePath));

            $product = Product::create([
                'store_id' => $store->id,
                'product_name' => $validatedRequest['product_name'],
                'description' => $validatedRequest['description'],
                'price' => $validatedRequest['price'],
                'image_url' => $imageUrl,
                'product_stock' => $validatedRequest['product_stock'],
                'category' => $validatedRequest['category']
            ]);

            return response()->json([
                "status" => true,
                "message" => "Berhasil menambahkan produk",
                "data" => [
                    "id" => $product->id,
                    "product_name" => $product->product_name
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal menambahkan produk",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
