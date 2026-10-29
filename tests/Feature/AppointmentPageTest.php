<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
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
}
