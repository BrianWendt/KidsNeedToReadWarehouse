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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('organization_id');
            $table->date('received_date')->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('address_id')->nullable();
            $table->integer('email_id')->nullable();
            $table->integer('telephone_id')->nullable();
            $table->text('note')->nullable();
            $table->string('book_condition', 20)->default('new');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('archived_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
