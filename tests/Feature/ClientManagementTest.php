<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_index_correctly_calculates_total_visits_and_total_spend_from_appointments(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $category = Category::create([
            'name' => 'Hair Styling',
            'slug' => 'hair-styling',
        ]);

        $stylist = User::factory()->create(['role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $stylist->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Hair Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '5 Years',
            'commission_percentage' => 20.00,
        ]);

        $client = User::factory()->create([
            'name' => 'Kasun Perera',
            'email' => 'kasun@example.com',
            'phone_number' => '077 123 4567',
            'gender' => 'male',
            'role' => 'client',
        ]);

        // Completed appointment 1 (Price: 6000.00) -> counted
        Appointment::factory()->create([
            'client_id' => $client->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Signature Haircut',
            'appointment_date' => '2026-10-15',
            'start_time' => '10:00',
            'end_time' => '10:45',
            'price' => 6000.00,
            'status' => 'completed',
        ]);

        // Completed appointment 2 (Price: 4000.00) -> counted
        Appointment::factory()->create([
            'client_id' => $client->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Beard Grooming & Scalp Massage',
            'appointment_date' => '2026-10-20',
            'start_time' => '11:00',
            'end_time' => '11:45',
            'price' => 4000.00,
            'status' => 'completed',
        ]);

        // Pending appointment (Price: 5000.00) -> should NOT be counted in completed visits or spend
        Appointment::factory()->create([
            'client_id' => $client->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Future Hair Treatment',
            'appointment_date' => '2026-11-05',
            'start_time' => '14:00',
            'end_time' => '15:00',
            'price' => 5000.00,
            'status' => 'pending',
        ]);

        // Cancelled appointment (Price: 3000.00) -> should NOT be counted in completed visits or spend
        Appointment::factory()->create([
            'client_id' => $client->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Cancelled Hair Color',
            'appointment_date' => '2026-10-10',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'price' => 3000.00,
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($owner)->get(route('clients.index'));

        $response->assertOk();
        $response->assertViewIs('clients.index');
        $response->assertSee('Kasun Perera');
        $response->assertSee('10,000.00');

        $response->assertViewHas('clients', function ($clients) use ($client): bool {
            $matched = $clients->firstWhere('id', $client->id);

            return $matched !== null
                && (int) $matched->total_visits === 2
                && (float) $matched->total_spend === 10000.00
                && (float) $matched->total_spent === 10000.00
                && $matched->membership_tier === 'Gold Member'
                && $matched->loyalty_points === 200;
        });
    }

    public function test_client_show_successfully_loads_appointment_history_ordered_chronologically(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $category = Category::create([
            'name' => 'Nail Studio',
            'slug' => 'nail-studio',
        ]);

        $stylist = User::factory()->create([
            'name' => 'Amali Fernando',
            'role' => 'worker',
        ]);

        $staff = Staff::create([
            'user_id' => $stylist->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Nail Artist',
            'serves_gender' => 'female',
            'availability_status' => 'available',
            'experience' => '4 Years',
            'commission_percentage' => 25.00,
        ]);

        $client = User::factory()->create([
            'name' => 'Devindi Jayasuriya',
            'email' => 'devindi@example.com',
            'phone_number' => '077 789 1234',
            'gender' => 'female',
            'role' => 'client',
        ]);

        // Older appointment
        $olderApt = Appointment::factory()->create([
            'client_id' => $client->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Gel Nail Art Extension',
            'appointment_date' => '2026-10-10',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'price' => 8500.00,
            'status' => 'completed',
        ]);

        // Newer appointment
        $newerApt = Appointment::factory()->create([
            'client_id' => $client->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Classic French Manicure',
            'appointment_date' => '2026-10-25',
            'start_time' => '14:00',
            'end_time' => '14:45',
            'price' => 4500.00,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($owner)->get(route('clients.show', $client));

        $response->assertOk();
        $response->assertViewIs('clients.show');
        $response->assertSee('Devindi Jayasuriya');
        $response->assertSee('Amali Fernando');
        $response->assertSee('Gel Nail Art Extension');
        $response->assertSee('Classic French Manicure');
        $response->assertSee('8,500.00');
        $response->assertSee('4,500.00');

        $response->assertViewHas('client', function (User $loadedClient) use ($newerApt, $olderApt): bool {
            $appointments = $loadedClient->appointments;

            return $appointments->count() === 2
                && $appointments->first()->id === $newerApt->id
                && $appointments->last()->id === $olderApt->id;
        });

        $response->assertViewHas('totalVisits', 1);
        $response->assertViewHas('totalSpend', 8500.00);
    }

    public function test_client_show_aborts_404_if_user_is_not_a_client(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $worker = User::factory()->create(['role' => 'worker']);

        $response = $this->actingAs($owner)->get(route('clients.show', $worker));

        $response->assertNotFound();
    }

    public function test_worker_cannot_access_client_show(): void
    {
        $worker = User::factory()->create(['role' => 'worker']);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($worker)->get(route('clients.show', $client));

        $response->assertForbidden();
    }
}
