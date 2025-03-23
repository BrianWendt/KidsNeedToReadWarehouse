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
        Schema::create('addresses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('contact_id');
            $table->string('name', 128);
            $table->string('street1', 128)->nullable();
            $table->string('street2', 128)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zipcode', 5)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
