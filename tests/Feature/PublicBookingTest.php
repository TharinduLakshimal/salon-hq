<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_guest_can_view_booking_page_with_categories_and_services(): void
    {
        $category = Category::create([
            'name' => 'Hair Styling',
            'slug' => 'hair-styling',
        ]);

        $stylist = User::factory()->create([
            'name' => 'Kamal Perera',
            'role' => 'worker',
        ]);

        $staff = Staff::create([
            'user_id' => $stylist->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Hair Specialist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '6 Years',
            'commission_percentage' => 20.00,
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Signature Haircut & Wash',
            'target_gender' => 'both',
            'price' => 3500.00,
            'duration_minutes' => 45,
            'is_package' => false,
        ]);

        $response = $this->get(route('booking.index'));

        $response->assertOk();
        $response->assertViewIs('public.booking');
        $response->assertSee('Hair Styling');
        $response->assertSee('Signature Haircut');
        $response->assertSee('Kamal Perera');
        $response->assertSee("selectedCategory: parseInt('{$category->id}')", false);
        $response->assertSee("@click=\"selectedCategory = {$category->id}\"", false);
        $response->assertSee('s.category_id == selectedCategory', false);
        $response->assertViewHas('categories');
        $response->assertViewHas('services');
        $response->assertViewHas('allStaff');
    }

    public function test_public_guest_can_successfully_book_appointment_and_creates_new_client_user(): void
    {
        $category = Category::create([
            'name' => 'Esthetics & Skin',
            'slug' => 'esthetics-skin',
        ]);

        $stylist = User::factory()->create([
            'name' => 'Ruwanthi Silva',
            'role' => 'worker',
        ]);

        $staff = Staff::create([
            'user_id' => $stylist->id,
            'category_id' => $category->id,
            'job_title' => 'Lead Esthetician',
            'serves_gender' => 'female',
            'availability_status' => 'available',
            'experience' => '8 Years',
            'commission_percentage' => 25.00,
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Hydra-Glow Gold Facial',
            'target_gender' => 'ladies',
            'price' => 12500.00,
            'duration_minutes' => 60,
            'is_package' => false,
        ]);

        $bookingData = [
            'service_id' => $service->id,
            'staff_id' => $staff->id,
            'date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '10:00 AM',
            'client_name' => 'Ananya Wickramasinghe',
            'client_phone' => '077 456 7890',
            'client_gender' => 'female',
            'client_email' => 'ananya@example.com',
            'notes' => 'First time client, sensitive skin.',
        ];

        $response = $this->post(route('booking.store'), $bookingData);

        // Assert redirect to booking success page
        $response->assertSessionHas('success');
        $response->assertSessionHas('booking_code');

        $appointment = Appointment::where('service_name', 'Hydra-Glow Gold Facial')->first();
        $this->assertNotNull($appointment);

        $response->assertRedirect(route('booking.success', ['code' => $appointment->booking_code]));

        // Assert new client user was created
        $this->assertDatabaseHas('users', [
            'name' => 'Ananya Wickramasinghe',
            'email' => 'ananya@example.com',
            'phone_number' => '0774567890',
            'gender' => 'female',
            'role' => 'client',
        ]);

        // Assert appointment record was saved properly
        $this->assertDatabaseHas('appointments', [
            'booking_code' => $appointment->booking_code,
            'client_id' => $appointment->client_id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'client_name' => 'Ananya Wickramasinghe',
            'client_phone' => '0774567890',
            'booking_type' => 'online_booking',
            'service_name' => 'Hydra-Glow Gold Facial',
            'duration_minutes' => 60,
            'price' => 12500.00,
            'status' => 'pending',
            'notes' => 'First time client, sensitive skin.',
        ]);
    }

    public function test_public_booking_reuses_existing_client_user_when_phone_matches(): void
    {
        $category = Category::create([
            'name' => 'Barbershop',
            'slug' => 'barbershop',
        ]);

        $stylist = User::factory()->create(['role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $stylist->id,
            'category_id' => $category->id,
            'job_title' => 'Master Barber',
            'serves_gender' => 'male',
            'availability_status' => 'available',
            'experience' => '10 Years',
            'commission_percentage' => 30.00,
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Royal Beard Sculpt & Trim',
            'target_gender' => 'gents',
            'price' => 2500.00,
            'duration_minutes' => 30,
            'is_package' => false,
        ]);

        $existingClient = User::factory()->create([
            'name' => 'Sanjaya Jayawardena',
            'phone_number' => '0712345678',
            'role' => 'client',
        ]);

        $clientsCountBefore = User::where('role', 'client')->count();

        // Submit with +94 format matching the same normalized number
        $response = $this->post(route('booking.store'), [
            'service_id' => $service->id,
            'staff_id' => $staff->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '02:00 PM',
            'client_name' => 'Sanjaya J.',
            'client_phone' => '+94 71 234 5678',
            'client_gender' => 'male',
        ]);

        $response->assertSessionHasNoErrors();

        // Ensure no duplicate user was created
        $this->assertSame($clientsCountBefore, User::where('role', 'client')->count());

        // Ensure appointment is attached to the existing client
        $this->assertDatabaseHas('appointments', [
            'client_id' => $existingClient->id,
            'service_name' => 'Royal Beard Sculpt & Trim',
            'booking_type' => 'online_booking',
        ]);
    }

    public function test_public_booking_rejects_invalid_sri_lankan_phone_number(): void
    {
        $category = Category::create([
            'name' => 'General',
            'slug' => 'general',
        ]);

        $stylist = User::factory()->create(['role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $stylist->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 20.00,
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Quick Trim',
            'target_gender' => 'both',
            'price' => 1500.00,
            'duration_minutes' => 20,
            'is_package' => false,
        ]);

        $response = $this->post(route('booking.store'), [
            'service_id' => $service->id,
            'staff_id' => $staff->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => '11:00 AM',
            'client_name' => 'Invalid Phone User',
            'client_phone' => '0112345678', // Landline, not 07X mobile
            'client_gender' => 'female',
        ]);

        $response->assertSessionHasErrors('client_phone');
        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_booking_success_page_displays_confirmed_appointment_details(): void
    {
        $category = Category::create([
            'name' => 'Nails',
            'slug' => 'nails',
        ]);

        $stylist = User::factory()->create([
            'name' => 'Nadeesha Perera',
            'role' => 'worker',
        ]);

        $staff = Staff::create([
            'user_id' => $stylist->id,
            'category_id' => $category->id,
            'job_title' => 'Nail Artist',
            'serves_gender' => 'female',
            'availability_status' => 'available',
            'experience' => '5 Years',
            'commission_percentage' => 20.00,
        ]);

        $client = User::factory()->create([
            'name' => 'Tharushi Silva',
            'role' => 'client',
        ]);

        $appointment = Appointment::factory()->create([
            'booking_code' => 'BK-2026-9876',
            'client_id' => $client->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'client_name' => 'Tharushi Silva',
            'client_phone' => '0779998888',
            'service_name' => 'Gel Nail Extension & Art',
            'price' => 7500.00,
            'booking_type' => 'online_booking',
            'status' => 'pending',
        ]);

        $response = $this->get(route('booking.success', ['code' => 'BK-2026-9876']));

        $response->assertOk();
        $response->assertViewIs('public.success');
        $response->assertSee('BK-2026-9876');
        $response->assertSee('Gel Nail Extension & Art');
        $response->assertSee('Nadeesha Perera');
        $response->assertSee('Tharushi Silva');
        $response->assertSee('7,500.00');
    }

    public function test_authenticated_client_can_view_client_dashboard(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $category = Category::create(['name' => 'Hair', 'slug' => 'hair']);
        $stylist = User::factory()->create(['name' => 'Ruwan Stylist', 'role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $stylist->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 20.00,
        ]);

        $appointment = Appointment::factory()->create([
            'client_id' => $client->id,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Keratin Hair Spa',
            'price' => 8000.00,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($client)->get(route('client.dashboard'));

        $response->assertOk();
        $response->assertViewIs('client-area.dashboard');
        $response->assertSee('Keratin Hair Spa');
        $response->assertSee('Ruwan Stylist');
        $response->assertSee(route('booking.index'));
    }

    public function test_worker_and_owner_cannot_access_client_dashboard(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $worker = User::factory()->create(['role' => 'worker']);

        $this->actingAs($owner)->get(route('client.dashboard'))->assertForbidden();
        $this->actingAs($worker)->get(route('client.dashboard'))->assertForbidden();
    }

    public function test_public_booking_page_renders_gender_filter_and_service_filtering_logic(): void
    {
        $category = Category::create([
            'name' => 'Hair Styling & Cuts',
            'slug' => 'hair-cuts',
        ]);

        Service::create([
            'category_id' => $category->id,
            'name' => 'Women Layered Haircut',
            'target_gender' => 'ladies',
            'price' => 4500.00,
            'duration_minutes' => 45,
            'is_package' => false,
        ]);

        Service::create([
            'category_id' => $category->id,
            'name' => 'Men Fade Haircut and Beard',
            'target_gender' => 'gents',
            'price' => 2500.00,
            'duration_minutes' => 30,
            'is_package' => false,
        ]);

        $response = $this->get(route('booking.index'));

        $response->assertOk();
        $response->assertSee('Who is this appointment for?');
        $response->assertSee("setGenderFilter('female')", false);
        $response->assertSee("setGenderFilter('male')", false);
        $response->assertSee("setGenderFilter('all')", false);
        $response->assertSee('Ladies');
        $response->assertSee('Gents');
        $response->assertSee('Women Layered Haircut');
        $response->assertSee('Men Fade Haircut and Beard');
        $response->assertSee('serviceMatchesGender(s, genderFilter)', false);
    }

    public function test_public_booking_page_passes_booked_appointments_to_frontend(): void
    {
        $category = Category::create(['name' => 'Barbers', 'slug' => 'barbers-test']);
        $worker = User::factory()->create(['role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Barber',
            'serves_gender' => 'male',
            'availability_status' => 'available',
            'experience' => '4 Years',
            'commission_percentage' => 20.00,
        ]);

        $futureDate = now()->addDays(3)->format('Y-m-d');

        Appointment::create([
            'booking_code' => 'BK-TEST-1001',
            'client_id' => null,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'client_name' => 'Test Client',
            'client_phone' => '0771234567',
            'client_gender' => 'male',
            'booking_type' => 'online_booking',
            'service_name' => 'Haircut',
            'duration_minutes' => 45,
            'appointment_date' => $futureDate,
            'start_time' => '11:00:00',
            'end_time' => '11:45:00',
            'price' => 2000.00,
            'status' => 'confirmed',
        ]);

        $response = $this->get(route('booking.index'));

        $response->assertOk();
        $response->assertViewHas('bookedAppointments');
        $response->assertSee('isSlotBooked(slot)', false);
        $response->assertSee('availableSlotsCount()', false);
    }

    public function test_booking_availability_api_endpoint_returns_booked_slots(): void
    {
        $category = Category::create(['name' => 'Barbers', 'slug' => 'barbers-api']);
        $worker = User::factory()->create(['role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Barber',
            'serves_gender' => 'male',
            'availability_status' => 'available',
            'experience' => '4 Years',
            'commission_percentage' => 20.00,
        ]);

        $futureDate = now()->addDays(4)->format('Y-m-d');

        Appointment::create([
            'booking_code' => 'BK-TEST-1002',
            'client_id' => null,
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'client_name' => 'Client API',
            'client_phone' => '0771234568',
            'client_gender' => 'male',
            'booking_type' => 'online_booking',
            'service_name' => 'Fade',
            'duration_minutes' => 45,
            'appointment_date' => $futureDate,
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'price' => 2500.00,
            'status' => 'confirmed',
        ]);

        $response = $this->get(route('booking.availability', [
            'staff_id' => $staff->id,
            'date' => $futureDate,
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['booked_slots']);
        $this->assertCount(1, $response->json('booked_slots'));
        $this->assertSame('10:00 AM', $response->json('booked_slots.0.start_time'));
    }

    public function test_cannot_double_book_stylist_at_same_time_slot(): void
    {
        $category = Category::create(['name' => 'Barbers', 'slug' => 'barbers-double']);
        $worker = User::factory()->create(['role' => 'worker', 'name' => 'Master Barber']);
        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Barber',
            'serves_gender' => 'male',
            'availability_status' => 'available',
            'experience' => '5 Years',
            'commission_percentage' => 20.00,
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Precision Cut',
            'target_gender' => 'gents',
            'price' => 3000.00,
            'duration_minutes' => 45,
            'is_package' => false,
        ]);

        $targetDate = now()->addDays(5)->format('Y-m-d');

        // First booking succeeds
        $bookingData1 = [
            'service_id' => $service->id,
            'staff_id' => $staff->id,
            'date' => $targetDate,
            'start_time' => '11:00 AM',
            'client_name' => 'First Client',
            'client_phone' => '077 111 2222',
            'client_gender' => 'male',
        ];

        $response1 = $this->post(route('booking.store'), $bookingData1);
        $response1->assertRedirect();
        $response1->assertSessionHas('success');

        // Second booking attempt for the exact same stylist and time slot MUST fail
        $bookingData2 = [
            'service_id' => $service->id,
            'staff_id' => $staff->id,
            'date' => $targetDate,
            'start_time' => '11:00 AM',
            'client_name' => 'Second Client',
            'client_phone' => '077 333 4444',
            'client_gender' => 'male',
        ];

        $response2 = $this->from(route('booking.index'))->post(route('booking.store'), $bookingData2);
        $response2->assertRedirect(route('booking.index'));
        $response2->assertSessionHasErrors(['start_time']);

        // Assert only 1 appointment exists in the database
        $this->assertSame(1, Appointment::where('staff_id', $staff->id)->whereDate('appointment_date', $targetDate)->count());
    }
}
