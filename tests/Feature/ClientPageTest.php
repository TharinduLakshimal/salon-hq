<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_visiting_clients(): void
    {
        $response = $this->get(route('clients.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_clients_page_loads_real_client_users_and_service_history_from_database(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $category = Category::create([
            'name' => 'Bridal & Makeup',
            'slug' => 'bridal-makeup',
        ]);
        $stylistUser = User::factory()->create([
            'name' => 'Kavindi Senanayake',
            'role' => 'worker',
        ]);
        $staff = Staff::create([
            'user_id' => $stylistUser->id,
            'category_id' => $category->id,
            'job_title' => 'Lead Bridal Stylist',
            'serves_gender' => 'female',
            'availability_status' => 'available',
            'experience' => '7 Years',
            'commission_percentage' => 25.00,
        ]);

        $clientUser = User::factory()->create([
            'name' => 'Senuri Perera',
            'email' => 'senuri@example.com',
            'phone_number' => '077 500 6000',
            'gender' => 'female',
            'role' => 'client',
        ]);

        Appointment::factory()->create([
            'client_id' => $clientUser->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Bridal HD Airbrush Package',
            'appointment_date' => '2026-10-28',
            'start_time' => '10:00',
            'end_time' => '13:00',
            'duration_minutes' => 180,
            'price' => 30000.00,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($owner)->get(route('clients.index'));

        $response->assertViewIs('clients.index');
        $response->assertSee('Senuri Perera');
        $response->assertSee('Bridal HD Airbrush Package');
        $response->assertViewHas('clients', function ($clients) use ($clientUser): bool {
            $client = $clients->first();

            return $clients->count() === 1
                && $client->id === $clientUser->id
                && (int) $client->total_visits === 1
                && (float) $client->total_spent === 30000.0
                && (int) $client->loyalty_points === 600
                && $client->membership_tier === 'VIP Platinum'
                && $client->preferred_stylist === 'Kavindi Senanayake';
        });
    }

    public function test_authenticated_user_can_create_client_in_database(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->post(route('clients.store'), [
            'name' => 'Ashen Fernando',
            'email' => 'ashen.fdo@example.com',
            'phone_number' => '(071) 888-9999',
            'gender' => 'male',
        ]);

        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Ashen Fernando',
            'email' => 'ashen.fdo@example.com',
            'phone_number' => '0718889999',
            'gender' => 'male',
            'role' => 'client',
        ]);
    }

    public function test_client_store_rejects_invalid_sri_lankan_phone_numbers(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->post(route('clients.store'), [
            'name' => 'Invalid Phone User',
            'email' => 'invalid.phone@example.com',
            'phone_number' => '0112345678',
            'gender' => 'female',
        ]);

        $response->assertSessionHasErrors('phone_number');
        $this->assertDatabaseMissing('users', [
            'email' => 'invalid.phone@example.com',
        ]);
    }

    public function test_authenticated_user_can_update_client_but_not_non_client_accounts(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $anotherOwner = User::factory()->create([
            'name' => 'Protected Owner',
            'role' => 'owner',
        ]);
        $client = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'phone_number' => '077 111 2222',
            'gender' => 'female',
            'role' => 'client',
        ]);

        $response = $this->actingAs($owner)->put(route('clients.update', $client), [
            'name' => 'Updated Client Name',
            'email' => 'updated@example.com',
            'phone_number' => '+94 77 333 4444',
            'gender' => 'female',
        ]);

        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('users', [
            'id' => $client->id,
            'name' => 'Updated Client Name',
            'email' => 'updated@example.com',
            'phone_number' => '+94773334444',
        ]);

        $this->actingAs($owner)
            ->put(route('clients.update', $anotherOwner), [
                'name' => 'Tampered Name',
                'email' => 'tampered@example.com',
                'phone_number' => '077 000 0000',
                'gender' => 'male',
            ])
            ->assertNotFound();
    }

    public function test_only_owner_can_delete_client_from_database(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $worker = User::factory()->create(['role' => 'worker']);
        $client = User::factory()->create(['role' => 'client']);

        $this->actingAs($worker)
            ->delete(route('clients.destroy', $client))
            ->assertForbidden();

        $this->assertModelExists($client);

        $this->actingAs($owner)
            ->delete(route('clients.destroy', $client))
            ->assertRedirect(route('clients.index'));

        $this->assertModelMissing($client);
    }
}
