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
        Schema::create('inventory', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('isbn', 30)->index('isbn');
            $table->enum('book_condition', ['new', 'like_new', 'used', 'rb_petsmart', 'rb_purchased', 'rb_handmade']);
            $table->integer('quantity');
            $table->double('fixed_value', null, 0)->nullable();
            $table->enum('entity_type', ['po', 'fulfillment'])->default('po');
            $table->integer('entity_id')->default(1);
            $table->string('note', 200)->nullable();
            $table->smallInteger('inventory_year')->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
