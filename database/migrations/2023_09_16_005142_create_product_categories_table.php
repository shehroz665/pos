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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id('cat_id'); 
            $table->string('cat_name');
            $table->string('updated_date')->nullable();
            $table->string('created_date')->nullable();
            $table->unsignedBigInteger('user_id');     
            $table->unsignedBigInteger('added_by'); 
            $table->unsignedBigInteger('modified_by');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
