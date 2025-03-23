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
        Schema::create('fulfillment_inventory', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('fulfillment_id');
            $table->string('isbn', 20);
            $table->enum('book_condition', ['new', 'like_new', 'used', 'rb_petsmart', 'rb_purchased', 'rb_handmade']);
            $table->integer('quantity');
            $table->double('fixed_value', null, 0)->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fulfillment_inventory');
    }
};
