<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Database\Seeders\AppointmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_visiting_appointments(): void
    {
        $response = $this->get(route('appointments.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_appointments_page_with_database_stylists(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $category = Category::create([
            'name' => 'Bridal & Makeup',
            'slug' => 'bridal-makeup',
        ]);
        $stylistUser = User::factory()->create([
            'name' => 'Oshadi Wickramaratne',
            'role' => 'worker',
        ]);
        Staff::create([
            'user_id' => $stylistUser->id,
            'category_id' => $category->id,
            'job_title' => 'Lead Bridal Stylist',
            'serves_gender' => 'female',
            'availability_status' => 'available',
            'experience' => '7 Years',
            'commission_percentage' => 25.00,
        ]);

        $response = $this->actingAs($owner)->get(route('appointments.index'));

        $response->assertViewIs('appointments.index');
        $response->assertViewHas('openModalOnLoad', false);
        $response->assertSee('Appointments & Bookings');
        $response->assertSee('Oshadi Wickramaratne');
    }

    public function test_new_query_parameter_enables_booking_modal_flag(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->get(route('appointments.index', ['new' => 1]));

        $response->assertOk();
        $response->assertViewHas('openModalOnLoad', true);
    }

    public function test_appointment_model_persists_relations_labels_and_nulls_deleted_client(): void
    {
        $client = User::factory()->create([
            'name' => 'Nethmi Perera',
            'role' => 'client',
        ]);

        $appointment = Appointment::factory()->for($client, 'client')->create([
            'client_name' => 'Snapshot Guest Name',
            'booking_type' => 'online_booking',
            'start_time' => '09:30:00',
            'end_time' => '13:00:00',
            'price' => 18500.00,
            'status' => 'in_progress',
        ]);

        $this->assertModelExists($appointment);
        $this->assertSame($client->id, $appointment->client?->id);
        $this->assertSame('Nethmi Perera', $appointment->display_name);
        $this->assertNotNull($appointment->staff);
        $this->assertSame('Online Booking', $appointment->booking_type_label);
        $this->assertSame('In Progress', $appointment->status_label);
        $this->assertSame('09:30 AM', $appointment->formatted_start_time);
        $this->assertSame('01:00 PM', $appointment->formatted_end_time);
        $this->assertSame('09:00 AM', $appointment->hour_key);
        $this->assertSame('Rs. 18,500', $appointment->formatted_price);

        $client->delete();

        $freshAppointment = $appointment->fresh();
        $this->assertNull($freshAppointment?->client_id);
        $this->assertSame('Snapshot Guest Name', $freshAppointment?->display_name);
        $this->assertModelExists($appointment);
    }

    public function test_walk_in_appointment_display_name_returns_client_name_when_client_id_is_null(): void
    {
        $walkInAppointment = Appointment::factory()->walkInGuest()->create([
            'client_name' => 'Senuri Gamage',
            'client_phone' => '(077) 450-9821',
        ]);

        $this->assertNull($walkInAppointment->client_id);
        $this->assertSame('0774509821', $walkInAppointment->client_phone);
        $this->assertSame('Senuri Gamage', $walkInAppointment->display_name);
    }

    public function test_creating_user_automatically_links_matching_walk_in_appointments_via_observer(): void
    {
        $matchingFirst = Appointment::factory()->walkInGuest()->create([
            'client_name' => 'Walk-In Guest',
            'client_phone' => '077 450 9821',
        ]);
        $matchingSecond = Appointment::factory()->walkInGuest()->create([
            'client_name' => 'Walk-In Guest Repeat',
            'client_phone' => '(077)-450-9821',
        ]);
        $otherWalkIn = Appointment::factory()->walkInGuest()->create([
            'client_phone' => '071 999 8888',
        ]);

        $registeredClient = User::factory()->create([
            'name' => 'Senuri Gamage Registered',
            'phone_number' => '077-450-9821',
            'role' => 'client',
        ]);

        $this->assertSame($registeredClient->id, $matchingFirst->fresh()?->client_id);
        $this->assertSame($registeredClient->id, $matchingSecond->fresh()?->client_id);
        $this->assertSame('Senuri Gamage Registered', $matchingFirst->fresh()?->display_name);
        $this->assertNull($otherWalkIn->fresh()?->client_id);
    }

    public function test_appointment_seeder_populates_idempotent_bookings_including_walk_in_guests(): void
    {
        $this->seed(AppointmentSeeder::class);
        $this->seed(AppointmentSeeder::class);

        $this->assertDatabaseCount('appointments', 16);
        $this->assertDatabaseHas('appointments', [
            'booking_code' => 'BK-2026-0101',
            'client_name' => 'Nethmi Perera',
            'booking_type' => 'online_booking',
            'status' => 'in_progress',
        ]);
        $this->assertDatabaseHas('appointments', [
            'booking_code' => 'BK-2026-0109',
            'client_id' => null,
            'client_name' => 'Senuri Gamage',
            'booking_type' => 'walk_in',
        ]);
    }
}
