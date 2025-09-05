<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('store_id')->constrained();
            $table->timestamp('order_date');
            $table->integer('total_amount');
            $table->enum('status', ["pending","accepted","processed","completed","cancelled"]);
            $table->enum('payment_method', ['cod', 'bank_transfer']);
            $table->enum('payment_status', ['unpaid','paid', 'refund']);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
