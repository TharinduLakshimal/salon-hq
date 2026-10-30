<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Database\Seeders\AppointmentSeeder;
use Illuminate\Database\Eloquent\Collection;
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

    public function test_appointments_index_loads_current_week_and_future_appointments_from_database(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $currentWeekAppointment = Appointment::factory()->create([
            'booking_code' => 'BK-2026-0901',
            'appointment_date' => now()->toDateString(),
        ]);

        Appointment::factory()->create([
            'booking_code' => 'BK-2026-0902',
            'appointment_date' => now()->startOfWeek()->subDays(2)->toDateString(),
        ]);

        $response = $this->actingAs($owner)->get(route('appointments.index'));

        $response->assertViewHas('appointments', function (array $appointments) use ($currentWeekAppointment): bool {
            return count($appointments) === 1
                && $appointments[0]['id'] === $currentWeekAppointment->id
                && $appointments[0]['booking_code'] === 'BK-2026-0901';
        });
    }

    public function test_store_creates_registered_and_walk_in_appointments_and_validates_phone(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $client = User::factory()->create([
            'name' => 'Registered Client Name',
            'phone_number' => '0778891122',
            'gender' => 'female',
            'role' => 'client',
        ]);
        $category = Category::create([
            'name' => 'Hair Stylists',
            'slug' => 'hair-stylists',
        ]);
        $worker = User::factory()->create(['name' => 'Amaya Jayawardena', 'role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Hair Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
        ]);

        $registeredResponse = $this->actingAs($owner)->postJson(route('appointments.store'), [
            'client_id' => $client->id,
            'client_name' => $client->name,
            'client_phone' => '077 889 1122',
            'client_gender' => 'female',
            'staff_id' => $staff->id,
            'service_name' => 'Butterfly Layer Cut & Blowout',
            'category' => 'Hair Stylists',
            'duration_minutes' => 60,
            'price' => 4500,
            'booking_type' => 'Online Booking',
            'date' => now()->toDateString(),
            'start_time' => '10:00 AM',
            'notes' => 'Registered booking test.',
        ]);

        $registeredResponse->assertOk()
            ->assertJsonPath('appointment.client_id', $client->id)
            ->assertJsonPath('appointment.client_name', 'Registered Client Name')
            ->assertJsonPath('appointment.booking_type', 'Online Booking')
            ->assertJsonPath('appointment.end_time', '11:00 AM');

        $walkInResponse = $this->actingAs($owner)->postJson(route('appointments.store'), [
            'client_id' => null,
            'client_name' => 'Walk-In Customer',
            'client_phone' => '+94 71 456 7890',
            'client_gender' => 'male',
            'staff_id' => $staff->id,
            'service_name' => 'Butterfly Layer Cut & Blowout',
            'category' => 'Hair Stylists',
            'duration_minutes' => 60,
            'price' => 4500,
            'booking_type' => 'Walk-In',
            'date' => now()->toDateString(),
            'start_time' => '01:30 PM',
        ]);

        $walkInResponse->assertOk()
            ->assertJsonPath('appointment.client_id', null)
            ->assertJsonPath('appointment.client_name', 'Walk-In Customer')
            ->assertJsonPath('appointment.client_phone', '+94714567890');

        $invalidPhoneResponse = $this->actingAs($owner)->postJson(route('appointments.store'), [
            'client_id' => null,
            'client_name' => 'Invalid Phone Guest',
            'client_phone' => '0112345678',
            'client_gender' => 'female',
            'staff_id' => $staff->id,
            'service_name' => 'Butterfly Layer Cut & Blowout',
            'duration_minutes' => 60,
            'price' => 4500,
            'booking_type' => 'Walk-In',
            'date' => now()->toDateString(),
            'start_time' => '02:00 PM',
        ]);

        $invalidPhoneResponse->assertUnprocessable()
            ->assertJsonValidationErrors(['client_phone']);
    }

    public function test_update_status_endpoint_persists_new_status_and_returns_json(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $appointment = Appointment::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($owner)->patchJson(
            route('appointments.update-status', $appointment),
            ['status' => 'in_progress']
        );

        $response->assertOk()
            ->assertJsonPath('appointment.id', $appointment->id)
            ->assertJsonPath('appointment.status', 'in_progress');

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_search_by_phone_returns_matching_user_or_not_found_payload(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $client = User::factory()->create([
            'name' => 'Sachini Fernando',
            'phone_number' => '076 992 1044',
            'gender' => 'female',
            'role' => 'client',
        ]);

        $foundResponse = $this->actingAs($owner)->getJson(
            route('clients.search-phone', ['phone' => '+94 76 992 1044'])
        );

        $foundResponse->assertOk()
            ->assertJson([
                'found' => true,
                'user' => [
                    'id' => $client->id,
                    'name' => 'Sachini Fernando',
                    'phone_number' => '0769921044',
                    'gender' => 'female',
                ],
            ]);

        $missingResponse = $this->actingAs($owner)->getJson(
            route('clients.search-phone', ['phone' => '0770001122'])
        );

        $missingResponse->assertOk()
            ->assertJson([
                'found' => false,
                'user' => null,
            ]);
    }

    public function test_appointments_index_injects_real_services_and_excludes_legacy_catalog(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $esthetics = Category::create([
            'name' => 'Estheticians & Nails',
            'slug' => 'estheticians-nails',
        ]);
        $barbers = Category::create([
            'name' => 'Barbers',
            'slug' => 'barbers',
        ]);

        $ladiesFacial = Service::factory()->for($esthetics)->create([
            'name' => 'Gold Facial',
            'target_gender' => 'ladies',
            'price' => 4500.00,
            'duration_minutes' => 60,
            'is_package' => false,
        ]);

        $gentsPackage = Service::factory()->for($barbers)->package()->create([
            'name' => 'Haircut + Beard cut',
            'target_gender' => 'gents',
            'price' => 1000.00,
            'duration_minutes' => 45,
        ]);

        $response = $this->actingAs($owner)->get(route('appointments.index'));

        $response->assertOk();
        $response->assertViewMissing('serviceCatalog');
        $response->assertViewHas('services', function (Collection $services) use ($ladiesFacial, $gentsPackage): bool {
            return $services->count() === 2
                && $services->contains('id', $ladiesFacial->id)
                && $services->contains('id', $gentsPackage->id);
        });
        $response->assertSee('Gold Facial');
        $response->assertSee('Haircut + Beard cut');
        $response->assertSee('service in filteredServices', false);
        $response->assertDontSee('Bridal Hair & HD Airbrush Makeup');
    }
}
