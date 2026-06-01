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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->foreignId('sender_id')->index();
            $table->foreignId('recipient_id')->index();
            $table->foreignId('assigned_messenger_id')->index();
            $table->foreignId('origin_agency_id')->index();
            $table->foreignId('destination_agency_id')->index();
            $table->foreignId('current_custodian_id')->index();
            $table->foreignId('package_type_id')->constrained('package_types'); 
            $table->foreignId('trip_id')->nullable()->constrained('trips');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
