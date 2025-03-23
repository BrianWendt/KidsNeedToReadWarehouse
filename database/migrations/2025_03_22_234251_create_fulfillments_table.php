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
        Schema::create('fulfillments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('status', ['new', 'preparing', 'pending_shipment', 'shipped', 'cancelled'])->default('new');
            $table->integer('program_id')->default(1);
            $table->integer('initiative_id')->nullable();
            $table->integer('children_served')->default(0);
            $table->integer('organization_id');
            $table->integer('contact_id')->nullable();
            $table->integer('shipping_contact_id')->nullable();
            $table->integer('shipping_address_id')->nullable();
            $table->string('tracking', 20)->nullable();
            $table->string('description', 600)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fulfillments');
    }
};
