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

        Schema::create('delivery_boys', function (Blueprint $table) {
            $table->id();
            $table->enum('role' , ['delivery_boy']);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('altNumber')->nullable();
            $table->enum('gender' , ['male','female','other']);
            $table->string('adhar');
            $table->string('image')->nullable();
            $table->string('drivingLicence');
            $table->enum('deliveryBoyType' , ['restaurantDeliveryBoy','speedieDeliveryBoy']);
            $table->string('locationId')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_boys');
    }
};
