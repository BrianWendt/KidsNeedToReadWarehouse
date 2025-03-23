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
        Schema::create('telephones', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('contact_id');
            $table->string('name', 64);
            $table->string('number', 20);
            $table->string('extension', 20)->nullable();
            $table->string('note', 200);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telephones');
    }
};
