<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_dynamic_dashboard_with_kpi_metrics_and_schedule(): void
    {
        $owner = User::factory()->create([
            'name' => 'Salon Owner',
            'role' => 'owner',
        ]);

        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker1 = User::factory()->create(['name' => 'Amaya Jay', 'role' => 'worker']);
        $staff1 = Staff::create([
            'user_id' => $worker1->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Hair Specialist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '5 Years',
            'commission_percentage' => 20.00,
        ]);

        $worker2 = User::factory()->create(['name' => 'Roshan Silva', 'role' => 'worker']);
        $staff2 = Staff::create([
            'user_id' => $worker2->id,
            'category_id' => $category->id,
            'job_title' => 'Master Barber',
            'serves_gender' => 'male',
            'availability_status' => 'in_session',
            'experience' => '4 Years',
            'commission_percentage' => 20.00,
        ]);

        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        // 3 appointments today: 1 confirmed, 1 completed, 1 cancelled
        $confirmedBooking = Appointment::factory()->create([
            'client_id' => null,
            'staff_id' => $staff1->id,
            'category_id' => $category->id,
            'client_name' => 'Nethmi Perera',
            'service_name' => 'Bridal Hair & Makeup',
            'appointment_date' => $today,
            'start_time' => '09:30:00',
            'price' => 3500.00,
            'status' => 'confirmed',
        ]);

        $completedBooking = Appointment::factory()->create([
            'client_id' => null,
            'staff_id' => $staff2->id,
            'category_id' => $category->id,
            'client_name' => 'Dilshan Silva',
            'service_name' => 'Haircut & Beard Styling',
            'appointment_date' => $today,
            'start_time' => '11:00:00',
            'price' => 5000.00,
            'status' => 'completed',
        ]);

        $cancelledBooking = Appointment::factory()->create([
            'client_id' => null,
            'staff_id' => $staff1->id,
            'category_id' => $category->id,
            'client_name' => 'Cancelled Client',
            'service_name' => 'Quick Trim',
            'appointment_date' => $today,
            'start_time' => '14:00:00',
            'price' => 1200.00,
            'status' => 'cancelled',
        ]);

        // 1 appointment tomorrow (must be excluded from today's feed and KPI)
        Appointment::factory()->create([
            'client_id' => null,
            'staff_id' => $staff1->id,
            'category_id' => $category->id,
            'client_name' => 'Tomorrow Client',
            'appointment_date' => $tomorrow,
            'start_time' => '10:00:00',
            'price' => 9000.00,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($owner)->get(route('dashboard'));

        $response->assertOk();

        // Verify view variables
        $response->assertViewHas('todayAppointmentsCount', 3);
        $response->assertViewHas('todayExpectedRevenue', 8500.00); // 3500 + 5000 (excluding 1200 cancelled)
        $response->assertViewHas('todayCompletedAppointmentsCount', 1);
        $response->assertViewHas('availableStaffCount', 1);

        // Verify KPI card elements in rendered HTML
        $response->assertSee('Rs. 8,500');
        $response->assertSee('Today\'s Bookings', false);
        $response->assertSee('1 completed today');

        // Verify today's appointments in chronological feed
        $response->assertSee('Nethmi Perera');
        $response->assertSee('Bridal Hair & Makeup');
        $response->assertSee('Dilshan Silva');
        $response->assertSee('Haircut & Beard Styling');
        $response->assertDontSee('Tomorrow Client');

        // Verify stylist status widget
        $response->assertSee('Amaya Jay');
        $response->assertSee('Roshan Silva');
        $response->assertSee('Available');
        $response->assertSee('In Session');
    }

    public function test_dashboard_handles_empty_state_gracefully(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('todayAppointmentsCount', 0);
        $response->assertViewHas('todayExpectedRevenue', 0.0);
        $response->assertViewHas('todayCompletedAppointmentsCount', 0);

        $response->assertSee('No appointments scheduled for today');
        $response->assertSee('No stylists registered yet.');
    }

    public function test_workers_and_guests_cannot_access_owner_dashboard(): void
    {
        $guestResponse = $this->get(route('dashboard'));
        $guestResponse->assertRedirect(route('login'));

        $worker = User::factory()->create(['role' => 'worker']);
        $workerResponse = $this->actingAs($worker)->get(route('dashboard'));
        $workerResponse->assertForbidden();
    }
}
