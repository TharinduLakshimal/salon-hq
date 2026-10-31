<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['female', 'male']);
        $startHour = fake()->numberBetween(9, 16);
        $durationMinutes = fake()->randomElement([45, 60, 75, 90, 120]);
        $startTime = sprintf('%02d:00:00', $startHour);
        $endTimestamp = strtotime($startTime) + ($durationMinutes * 60);

        return [
            'booking_code' => 'BK-'.now()->format('Y').'-'.fake()->unique()->numerify('####'),
            'client_id' => User::factory()->state(['gender' => $gender, 'role' => 'client']),
            'staff_id' => function (): int {
                $existingStaffId = Staff::query()->value('id');
                if ($existingStaffId !== null) {
                    return (int) $existingStaffId;
                }

                $category = Category::firstOrCreate(
                    ['slug' => 'hair-stylists'],
                    ['name' => 'Hair Stylists', 'description' => 'Hair Stylists department at Salon HQ.']
                );

                $worker = User::factory()->create([
                    'role' => 'worker',
                ]);

                return Staff::create([
                    'user_id' => $worker->id,
                    'category_id' => $category->id,
                    'job_title' => 'Senior Hair Stylist',
                    'serves_gender' => 'both',
                    'availability_status' => 'available',
                ])->id;
            },
            'category_id' => fn (): int => Category::firstOrCreate(
                ['slug' => Str::slug('Hair Stylists')],
                ['name' => 'Hair Stylists', 'description' => 'Hair Stylists department at Salon HQ.']
            )->id,
            'client_name' => fake()->name($gender),
            'client_phone' => fake()->numerify('077 ### ####'),
            'client_gender' => $gender,
            'booking_type' => fake()->randomElement(['walk_in', 'phone_desk', 'online_booking']),
            'service_name' => fake()->randomElement([
                'Butterfly Layer Cut & Blowout',
                'Brazilian Keratin Hair Treatment',
                'Executive Skin Fade & Beard Styling',
                '7-Step Platinum HydraFacial',
            ]),
            'duration_minutes' => $durationMinutes,
            'appointment_date' => now()->toDateString(),
            'start_time' => $startTime,
            'end_time' => date('H:i:s', $endTimestamp),
            'price' => fake()->randomElement([3200, 4500, 7500, 14000, 18500]),
            'status' => 'confirmed',
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the appointment is for a walk-in guest without a registered user account.
     */
    public function walkInGuest(): static
    {
        return $this->state(fn (): array => [
            'client_id' => null,
            'booking_type' => 'walk_in',
        ]);
    }
}
