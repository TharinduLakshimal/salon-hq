<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkerPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_sees_admin_workspace_and_full_navigation_in_sidebar(): void
    {
        $owner = User::factory()->create([
            'name' => 'Dilshan Owner',
            'role' => 'owner',
        ]);

        $response = $this->actingAs($owner)->get(route('appointments.index'));

        $response->assertOk();
        $response->assertSee('Admin Workspace');
        $response->assertDontSee('Stylist Workspace');
        $response->assertSee('Dashboard');
        $response->assertSee('Clients');
        $response->assertSee('Services & Pricing', false);
        $response->assertSee('Staff & Stylists', false);
        $response->assertDontSee('Stylist Menu');
        $response->assertDontSee('My Profile');
    }

    public function test_worker_sees_stylist_workspace_and_restricted_navigation_in_sidebar(): void
    {
        $category = Category::create([
            'name' => 'Hair Stylists',
            'slug' => 'hair-stylists',
        ]);

        $worker = User::factory()->create([
            'name' => 'Amaya Worker',
            'email' => 'amaya.stylist@salonhq.com',
            'role' => 'worker',
        ]);

        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Hair Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '5 Years',
            'commission_percentage' => 20.00,
            'today_bookings' => 3,
        ]);

        Appointment::factory()->create([
            'staff_id' => $staff->id,
            'status' => 'confirmed',
        ]);
        Appointment::factory()->create([
            'staff_id' => $staff->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($worker)->get(route('appointments.index'));

        $response->assertOk();
        $response->assertSee('Stylist Workspace');
        $response->assertDontSee('Admin Workspace');
        $response->assertSee('Stylist Menu');
        $response->assertSee('My Appointments');
        $response->assertSee('My Profile');

        // Owner-only links should NOT appear for workers
        $response->assertDontSee('<span>Dashboard</span>', false);
        $response->assertDontSee('<span>Clients</span>', false);
        $response->assertDontSee('<span>Services & Pricing</span>', false);
        $response->assertDontSee('<span>Staff & Stylists</span>', false);
    }

    public function test_worker_login_redirects_to_appointments(): void
    {
        $worker = User::factory()->create([
            'email' => 'worker@salonhq.com',
            'password' => 'password123',
            'role' => 'worker',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'worker@salonhq.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('appointments.index'));
        $this->assertAuthenticatedAs($worker);
    }

    public function test_owner_login_redirects_to_dashboard(): void
    {
        $owner = User::factory()->create([
            'email' => 'owner@salonhq.com',
            'password' => 'password123',
            'role' => 'owner',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'owner@salonhq.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($owner);
    }

    public function test_client_login_is_rejected_from_workspace_and_logged_out(): void
    {
        $client = User::factory()->create([
            'email' => 'client@salonhq.com',
            'password' => 'password123',
            'role' => 'client',
        ]);

        $response = $this->from(route('login'))->post(route('login.post'), [
            'email' => 'client@salonhq.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Client accounts cannot access the salon workspace.',
        ]);
        $this->assertGuest();
    }

    public function test_worker_is_forbidden_from_owner_routes(): void
    {
        $worker = User::factory()->create(['role' => 'worker']);

        $this->actingAs($worker)->get(route('dashboard'))->assertForbidden();
        $this->actingAs($worker)->get(route('clients.index'))->assertForbidden();
        $this->actingAs($worker)->get(route('services.index'))->assertForbidden();
        $this->actingAs($worker)->get(route('staff.index'))->assertForbidden();
    }

    public function test_appointments_index_scopes_queries_strictly_to_authenticated_worker(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker1 = User::factory()->create(['name' => 'Worker One', 'role' => 'worker']);
        $staff1 = Staff::create([
            'user_id' => $worker1->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist 1',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 15.00,
        ]);

        $worker2 = User::factory()->create(['name' => 'Worker Two', 'role' => 'worker']);
        $staff2 = Staff::create([
            'user_id' => $worker2->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist 2',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '4 Years',
            'commission_percentage' => 15.00,
        ]);

        $appointment1 = Appointment::factory()->create([
            'staff_id' => $staff1->id,
            'category_id' => $category->id,
            'client_name' => 'Client for Worker 1',
            'appointment_date' => now()->toDateString(),
        ]);

        $appointment2 = Appointment::factory()->create([
            'staff_id' => $staff2->id,
            'category_id' => $category->id,
            'client_name' => 'Client for Worker 2',
            'appointment_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($worker1)->get(route('appointments.index'));

        $response->assertOk();
        $response->assertViewHas('isWorker', true);

        $viewAppointments = collect($response->viewData('appointments'));
        $this->assertTrue($viewAppointments->contains('id', $appointment1->id));
        $this->assertFalse($viewAppointments->contains('id', $appointment2->id));

        // Filter by stylist select is hidden for workers
        $response->assertDontSee('aria-label="Filter by stylist"', false);
    }

    public function test_worker_store_appointment_forces_staff_id_to_own_staff_profile(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker = User::factory()->create(['name' => 'Worker Me', 'role' => 'worker']);
        $myStaff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 15.00,
        ]);

        $otherUser = User::factory()->create(['role' => 'worker']);
        $otherStaff = Staff::create([
            'user_id' => $otherUser->id,
            'category_id' => $category->id,
            'job_title' => 'Other Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '5 Years',
            'commission_percentage' => 15.00,
        ]);

        $payload = [
            'client_name' => 'Kavindu Perera',
            'client_phone' => '0771234567',
            'client_gender' => 'male',
            'staff_id' => $otherStaff->id, // Intentionally sending other stylist ID
            'service_name' => 'Men Haircut',
            'category_id' => $category->id,
            'duration_minutes' => 30,
            'price' => 600,
            'booking_type' => 'Walk-In',
            'date' => now()->toDateString(),
            'start_time' => '10:00 AM',
            'notes' => 'Short fade',
        ];

        $response = $this->actingAs($worker)->postJson(route('appointments.store'), $payload);

        $response->assertOk();
        $responseData = $response->json();

        // Must be saved with worker's own staff_id, not otherStaff->id
        $this->assertEquals($myStaff->id, $responseData['appointment']['staff_id']);
        $this->assertDatabaseHas('appointments', [
            'id' => $responseData['appointment']['id'],
            'staff_id' => $myStaff->id,
            'client_name' => 'Kavindu Perera',
        ]);
    }

    public function test_worker_can_update_own_appointment(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker = User::factory()->create(['name' => 'Worker Update', 'role' => 'worker']);
        $myStaff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 15.00,
        ]);

        $appointment = Appointment::factory()->create([
            'staff_id' => $myStaff->id,
            'category_id' => $category->id,
            'service_name' => 'Haircut',
            'price' => 1000,
            'client_phone' => '0771122334',
        ]);

        $updatePayload = [
            'client_name' => 'Updated Client Name',
            'client_phone' => '0779988776',
            'client_gender' => 'female',
            'staff_id' => $myStaff->id,
            'service_name' => 'Keratin Treatment',
            'category_id' => $category->id,
            'duration_minutes' => 60,
            'price' => 12000,
            'booking_type' => 'Walk-In',
            'date' => now()->addDay()->toDateString(),
            'start_time' => '02:00 PM',
            'notes' => 'Updated consultation notes',
        ];

        $response = $this->actingAs($worker)->putJson(route('appointments.update', $appointment), $updatePayload);

        $response->assertOk();
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'service_name' => 'Keratin Treatment',
            'price' => 12000,
            'client_name' => 'Updated Client Name',
            'client_phone' => '0779988776',
        ]);
    }

    public function test_worker_cannot_update_another_stylist_appointment(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker1 = User::factory()->create(['name' => 'Worker 1', 'role' => 'worker']);
        $staff1 = Staff::create([
            'user_id' => $worker1->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist 1',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 15.00,
        ]);

        $worker2 = User::factory()->create(['name' => 'Worker 2', 'role' => 'worker']);
        $staff2 = Staff::create([
            'user_id' => $worker2->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist 2',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 15.00,
        ]);

        $otherAppointment = Appointment::factory()->create([
            'staff_id' => $staff2->id,
            'category_id' => $category->id,
            'service_name' => 'Color Treatment',
        ]);

        $updatePayload = [
            'client_name' => 'Sneaky Update',
            'client_phone' => '0771122334',
            'client_gender' => 'male',
            'staff_id' => $staff1->id,
            'service_name' => 'Hacked Service',
            'duration_minutes' => 30,
            'price' => 500,
            'booking_type' => 'Walk-In',
            'date' => now()->toDateString(),
            'start_time' => '11:00 AM',
        ];

        $response = $this->actingAs($worker1)->putJson(route('appointments.update', $otherAppointment), $updatePayload);

        $response->assertForbidden();
    }

    public function test_owner_can_update_any_appointment(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $owner = User::factory()->create(['name' => 'Owner Person', 'role' => 'owner']);
        $worker = User::factory()->create(['name' => 'Stylist Person', 'role' => 'worker']);
        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 15.00,
        ]);

        $appointment = Appointment::factory()->create([
            'staff_id' => $staff->id,
            'category_id' => $category->id,
            'service_name' => 'Original Service',
        ]);

        $updatePayload = [
            'client_name' => 'Owner Rescheduled',
            'client_phone' => '0779900112',
            'client_gender' => 'female',
            'staff_id' => $staff->id,
            'service_name' => 'Updated By Owner',
            'duration_minutes' => 45,
            'price' => 2500,
            'booking_type' => 'Phone Desk',
            'date' => now()->toDateString(),
            'start_time' => '03:00 PM',
        ];

        $response = $this->actingAs($owner)->putJson(route('appointments.update', $appointment), $updatePayload);

        $response->assertOk();
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'service_name' => 'Updated By Owner',
            'client_name' => 'Owner Rescheduled',
        ]);
    }

    public function test_worker_can_view_my_profile_page(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker = User::factory()->create([
            'name' => 'Amaya Stylist',
            'email' => 'amaya.portal@salonhq.com',
            'phone_number' => '0771122334',
            'role' => 'worker',
        ]);

        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Hair Specialist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '5 Years',
            'commission_percentage' => 25.00,
            'bio' => 'Passionate about modern hair trends.',
            'specialties' => ['Hair Styling', 'Balayage'],
        ]);

        $response = $this->actingAs($worker)->get(route('worker.profile'));

        $response->assertOk();
        $response->assertSee('Amaya Stylist');
        $response->assertSee('Senior Hair Specialist');
        $response->assertSee('25.0%');
        $response->assertSee('Passionate about modern hair trends.');
        $response->assertSee('Balayage');
    }

    public function test_worker_can_update_bio_and_phone_number(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker = User::factory()->create([
            'name' => 'Original Name',
            'phone_number' => '0771112233',
            'gender' => 'female',
            'role' => 'worker',
        ]);

        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '3 Years',
            'commission_percentage' => 20.00,
            'bio' => 'Original bio.',
            'specialties' => ['Classic Cuts'],
        ]);

        $payload = [
            'name' => 'Updated Worker Name',
            'phone_number' => '0779988776',
            'gender' => 'female',
            'bio' => 'Updated modern consultation bio describing expert color techniques.',
            'specialties' => 'Layer Cuts, Keratin Smoothing, Balayage',
        ];

        $response = $this->actingAs($worker)->put(route('worker.profile.update'), $payload);

        $response->assertRedirect(route('worker.profile'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('users', [
            'id' => $worker->id,
            'name' => 'Updated Worker Name',
            'phone_number' => '0779988776',
        ]);

        $staff->refresh();
        $this->assertEquals('Updated modern consultation bio describing expert color techniques.', $staff->bio);
        $this->assertEquals(['Layer Cuts', 'Keratin Smoothing', 'Balayage'], $staff->specialties);
    }

    public function test_worker_can_update_availability_status(): void
    {
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
        ]);

        // Test JSON Patch update
        $response = $this->actingAs($worker)->patchJson(route('worker.profile.availability'), [
            'availability_status' => 'in_session',
        ]);

        $response->assertOk();
        $response->assertJson([
            'status' => 'in_session',
            'status_label' => 'In Session',
        ]);

        $staff->refresh();
        $this->assertEquals('in_session', $staff->availability_status);

        // Test another status transition
        $response2 = $this->actingAs($worker)->patch(route('worker.profile.availability'), [
            'availability_status' => 'on_break',
        ]);

        $response2->assertRedirect(route('worker.profile'));
        $staff->refresh();
        $this->assertEquals('on_break', $staff->availability_status);
    }

    public function test_worker_cannot_alter_commission_percentage_job_title_or_category(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hair-stylists'],
            ['name' => 'Hair Stylists']
        );

        $worker = User::factory()->create([
            'name' => 'Honest Stylist',
            'phone_number' => '0771234567',
            'gender' => 'female',
            'role' => 'worker',
        ]);

        $staff = Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Junior Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '1 Year',
            'commission_percentage' => 15.00,
        ]);

        // Malicious payload attempting to elevate commission and role
        $maliciousPayload = [
            'name' => 'Honest Stylist',
            'phone_number' => '0771234567',
            'gender' => 'female',
            'bio' => 'Safe bio.',
            'specialties' => 'Haircuts',
            'commission_percentage' => 99.00,
            'job_title' => 'Salon Owner & Partner',
            'category_id' => 999,
        ];

        $response = $this->actingAs($worker)->put(route('worker.profile.update'), $maliciousPayload);

        $response->assertRedirect(route('worker.profile'));

        $staff->refresh();
        $this->assertEquals('15.00', (string) $staff->commission_percentage);
        $this->assertEquals('Junior Stylist', $staff->job_title);
        $this->assertEquals($category->id, $staff->category_id);
    }

    public function test_non_workers_cannot_access_worker_profile(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner)->get(route('worker.profile'))->assertForbidden();

        $client = User::factory()->create(['role' => 'client']);
        $this->actingAs($client)->get(route('worker.profile'))->assertForbidden();
    }
}
