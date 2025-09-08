<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderItemResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Mengimpor DB untuk transaction
use Exception;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        $validatedRequest = $request->validate([
            "store_id" => "required|numeric|exists:stores,id",
            "payment_method" => [
                "string",
                "required",
                Rule::in('cod', 'transfer')
            ],
            "items" => "required|array",
            "items.*.product_id" => "required|numeric|exists:products,id",
            "items.*.quantity" => "required|numeric|min:1"
        ]);
        DB::beginTransaction();
        try {

            $user = $request->user();
            $totalAmount = 0;
            $orderItems = collect();

            foreach ($validatedRequest['items'] as $item) {
                $product = Product::find($item['product_id']);

                $sub_total = $product->price * $item['quantity'];
                $totalAmount += $sub_total;

                $orderItems->push(new OrderItem([
                    "product_id" => $item['product_id'],
                    "product_name" => $product->product_name,
                    "quantity" => $item['quantity'],
                    "price_per_item" => $product->price
                ]));
            }

            $newOrder = Order::create([
                "user_id" => $user->id,
                "store_id" => $validatedRequest['store_id'],
                "total_amount" => $totalAmount,
                "status" => "pending",
                "payment_method" => $validatedRequest['payment_method']
            ]);

            $newOrder->orderItems()->saveMany($orderItems);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Berhasil menambahkan orderan",
                "data" => [
                    "order_id" => $newOrder->id,
                    "store_id" => $newOrder->store_id,
                    "user_id" => $user->id,
                    "total_amount" => $totalAmount,
                    "status" => $newOrder->status,
                    "payment_method" => $newOrder->payment_method,
                    "order_items" => OrderItemResource::collection($newOrder->orderItems)
                ]
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => false,
                "message" => "Gagal menambahkan order",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ], 500);
        }
    }
}
