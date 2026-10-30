<?php

namespace Tests\Feature;

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

    public function test_authenticated_owner_can_view_clients_page_with_database_stylists_and_delete_modal(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $category = Category::create([
            'name' => 'Hair Stylists',
            'slug' => 'hair-stylists',
        ]);
        $stylistUser = User::factory()->create([
            'name' => 'Dinithi Samarasinghe',
            'role' => 'worker',
        ]);
        Staff::create([
            'user_id' => $stylistUser->id,
            'category_id' => $category->id,
            'job_title' => 'Senior Hair Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '6 Years',
            'commission_percentage' => 22.00,
        ]);

        $response = $this->actingAs($owner)->get(route('clients.index'));

        $response->assertViewIs('clients.index');
        $response->assertViewHas('isOwner', true);
        $response->assertViewHas('openModalOnLoad', false);
        $response->assertSee('Clients & Loyalty CRM');
        $response->assertSee('Dinithi Samarasinghe');
        $response->assertSee('5 / page');
        $response->assertSee('Remove Client Profile?');
    }

    public function test_worker_views_clients_page_without_owner_delete_controls(): void
    {
        $worker = User::factory()->create(['role' => 'worker']);

        $response = $this->actingAs($worker)->get(route('clients.index'));

        $response->assertViewHas('isOwner', false);
        $response->assertSee('Clients & Loyalty CRM');
        $response->assertDontSee('Remove Client Profile?');
    }

    public function test_new_query_parameter_enables_add_client_modal_flag(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->get(route('clients.index', ['new' => 1]));

        $response->assertOk();
        $response->assertViewHas('openModalOnLoad', true);
    }
}
