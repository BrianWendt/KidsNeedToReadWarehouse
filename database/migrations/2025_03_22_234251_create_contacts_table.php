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
        Schema::create('contacts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('organization_id')->index('organization');
            $table->string('first_name', 64)->nullable();
            $table->string('last_name', 64)->nullable();
            $table->string('preferred_name', 128)->nullable();
            $table->string('title', 128)->nullable();
            $table->string('ein', 20)->nullable();
            $table->string('note', 600)->nullable();
            $table->integer('primary_address_id')->nullable();
            $table->integer('primary_email_id')->nullable();
            $table->integer('primary_telephone_id')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
