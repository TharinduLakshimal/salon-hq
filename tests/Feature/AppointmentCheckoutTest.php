<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_completing_appointment_via_status_patch_increments_stylist_metrics(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
        ]);

        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker = User::factory()->create(['name' => 'Amaya Jay', 'role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Hair Specialist',
            'serves_gender' => 'both',
            'availability_status' => 'in_session',
            'experience' => '5 Years',
            'commission_percentage' => 20.00,
            'completed_works' => 5,
            'monthly_revenue' => 15000.00,
        ]);

        $appointment = Appointment::factory()->create([
            'client_id' => null,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Keratin Hair Treatment',
            'price' => 3500.00,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($owner)->patchJson(route('appointments.update-status', $appointment), [
            'status' => 'completed',
        ]);

        $response->assertOk();
        $response->assertJson([
            'appointment' => [
                'id' => $appointment->id,
                'status' => 'completed',
            ],
        ]);

        $staff->refresh();
        $this->assertEquals(6, $staff->completed_works);
        $this->assertEquals(18500.00, (float) $staff->monthly_revenue);
    }

    public function test_changing_appointment_to_non_completed_status_does_not_increment_metrics(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker = User::factory()->create(['role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 15.00,
            'completed_works' => 10,
            'monthly_revenue' => 20000.00,
        ]);

        $appointment = Appointment::factory()->create([
            'client_id' => null,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'price' => 4500.00,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($owner)->patchJson(route('appointments.update-status', $appointment), [
            'status' => 'confirmed',
        ]);

        $response->assertOk();

        $staff->refresh();
        $this->assertEquals(10, $staff->completed_works);
        $this->assertEquals(20000.00, (float) $staff->monthly_revenue);
    }

    public function test_worker_can_complete_own_appointment_and_trigger_metrics_update(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker = User::factory()->create(['name' => 'Worker Stylist', 'role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'in_session',
            'experience' => '3 Years',
            'commission_percentage' => 15.00,
            'completed_works' => 2,
            'monthly_revenue' => 5000.00,
        ]);

        $appointment = Appointment::factory()->create([
            'client_id' => null,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'price' => 2500.00,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($worker)->patchJson(route('appointments.update-status', $appointment), [
            'status' => 'completed',
        ]);

        $response->assertOk();

        $staff->refresh();
        $this->assertEquals(3, $staff->completed_works);
        $this->assertEquals(7500.00, (float) $staff->monthly_revenue);
    }
}
