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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique(); // e.g., Bridal & Makeup, Hair Stylists, Barbers, Estheticians & Nails
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // Salon Role & Unisex Classification
            $table->string('job_title', 120);
            $table->enum('serves_gender', ['both', 'female', 'male'])->default('both')->index();

            // Live Status & Experience
            $table->enum('availability_status', ['available', 'in_session', 'on_break', 'off_duty'])
                ->default('available')
                ->index();
            $table->string('experience', 50)->default('1 Year');
            $table->string('joined_date', 50)->nullable();

            // Payroll, Performance & Stats (Strict Numeric Types)
            $table->decimal('commission_percentage', 5, 2)->default(0.00);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedInteger('completed_works')->default(0);
            $table->unsignedInteger('today_bookings')->default(0);
            $table->decimal('monthly_revenue', 10, 2)->default(0.00);
            $table->string('repeat_clients', 20)->default('0%');

            // Profile Details
            $table->string('avatar_gradient')->default('linear-gradient(135deg, #4f46e5 0%, #6366f1 100%)');
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable();
            $table->json('schedule')->nullable();

            $table->timestamps();
        });

        Schema::create('staff_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            $table->string('title', 150);
            $table->string('client_name', 120)->nullable();
            $table->string('performed_date', 50)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->string('accent_color', 30)->default('#4f46e5');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_works');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('categories');
    }
};
