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
        Schema::create('books', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('isbn', 30)->unique('isbn');
            $table->string('volume_id', 14)->nullable()->comment('Google Book ID');
            $table->string('title', 128);
            $table->double('retail_price', null, 0)->nullable();
            $table->double('fixed_value', null, 0)->nullable();
            $table->string('author', 128)->nullable();
            $table->string('categories', 128)->nullable();
            $table->smallInteger('page_count')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
