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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 30)->unique();

            // Linked Records (client_id is nullable to support Walk-In & Phone Desk guests)
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // Client Snapshot & Unisex Classification
            $table->string('client_name', 150);
            $table->string('client_phone', 25)->index();
            $table->enum('client_gender', ['female', 'male', 'other'])->default('female')->index();

            // Booking Source & Service Details
            $table->enum('booking_type', ['walk_in', 'phone_desk', 'online_booking'])
                ->default('walk_in')
                ->index();
            $table->string('service_name', 150);
            $table->unsignedSmallInteger('duration_minutes')->default(60);

            // Chair Schedule & Pricing
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('price', 10, 2)->default(0.00);

            // Lifecycle Status & Consultation Notes
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])
                ->default('confirmed')
                ->index();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['appointment_date', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
