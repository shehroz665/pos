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
        Schema::create('products', function (Blueprint $table) {
            $table->id('prod_id');
            $table->string('prod_name');
            $table->unsignedBigInteger('prod_sup_id');
            // $table->string('prod_sup_name');
            $table->unsignedBigInteger('prod_cat_id');
            // $table->string('prod_cat_name');
            $table->string('updated_date')->nullable();
            $table->string('created_date')->nullable();
            $table->string('added_by');
            $table->string('modified_by')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->decimal('prod_cost', 10, 2);
            $table->decimal('prod_selling_price', 10, 2);
            $table->string('image')->nullable();
            $table->decimal('prod_quantity', 10, 1);
            $table->unsignedBigInteger('prod_size_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
