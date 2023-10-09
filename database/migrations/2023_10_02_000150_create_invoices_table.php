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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->string('cust_name')->nullable();
            $table->unsignedBigInteger('cust_number');
            $table->json('products');
            $table->integer('total_products');
            $table->integer('total_price');
            $table->integer('total_quantity');
            $table->integer('total_cost');
            $table->integer('borrow_amount')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('updated_date')->nullable();
            $table->string('created_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
