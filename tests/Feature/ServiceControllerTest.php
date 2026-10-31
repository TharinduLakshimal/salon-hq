<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_visiting_services_page(): void
    {
        $response = $this->get(route('services.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_views_services_catalog_with_categories_and_active_booking_counts(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $esthetics = Category::create([
            'name' => 'Estheticians & Nails',
            'slug' => 'estheticians-nails',
        ]);

        $hydraFacial = Service::factory()->for($esthetics)->create([
            'name' => 'Hydra Facial',
            'target_gender' => 'ladies',
            'price' => 10000.00,
            'duration_minutes' => 60,
            'is_package' => false,
        ]);

        Appointment::factory()->create([
            'service_name' => 'Hydra Facial',
            'status' => 'confirmed',
        ]);
        Appointment::factory()->create([
            'service_name' => 'Hydra Facial',
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($owner)->get(route('services.index', ['new' => 1]));

        $response->assertOk();
        $response->assertViewIs('services.index');
        $response->assertViewHas('openModalOnLoad', true);
        $response->assertViewHas('isOwner', true);
        $response->assertViewHas('services', function (array $services) use ($hydraFacial): bool {
            return count($services) === 1
                && $services[0]['id'] === $hydraFacial->id
                && $services[0]['bookings_count'] === 1
                && $services[0]['price'] === 10000.0;
        });
        $response->assertSee('Services &amp; Pricing', false);
        $response->assertSee('Hydra Facial');
    }

    public function test_store_creates_service_under_existing_category_and_supports_creating_new_category(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $barbers = Category::create([
            'name' => 'Barbers',
            'slug' => 'barbers',
        ]);

        $existingCatResponse = $this->actingAs($owner)->post(route('services.store'), [
            'name' => 'Executive Beard Sculpt',
            'category_id' => $barbers->id,
            'target_gender' => 'gents',
            'price' => 1200,
            'duration_minutes' => 30,
            'is_package' => 0,
        ]);

        $existingCatResponse->assertRedirect(route('services.index'));
        $existingCatResponse->assertSessionHas('success');
        $this->assertDatabaseHas('services', [
            'name' => 'Executive Beard Sculpt',
            'category_id' => $barbers->id,
            'target_gender' => 'gents',
            'price' => 1200.00,
            'duration_minutes' => 30,
            'is_package' => false,
        ]);

        $newCatResponse = $this->actingAs($owner)->post(route('services.store'), [
            'name' => 'Aromatherapy Head & Shoulder Bundle',
            'category_id' => '__new__',
            'new_category_name' => 'Spa & Wellness',
            'target_gender' => 'both',
            'price' => 6500,
            'duration_minutes' => 75,
            'is_package' => 1,
        ]);

        $newCatResponse->assertRedirect(route('services.index'));
        $createdCategory = Category::where('slug', 'spa-wellness')->first();
        $this->assertNotNull($createdCategory);
        $this->assertDatabaseHas('services', [
            'name' => 'Aromatherapy Head & Shoulder Bundle',
            'category_id' => $createdCategory->id,
            'target_gender' => 'both',
            'price' => 6500.00,
            'duration_minutes' => 75,
            'is_package' => true,
        ]);
    }

    public function test_store_rejects_duplicate_within_same_gender_and_category_but_allows_different_gender(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $esthetics = Category::create([
            'name' => 'Estheticians & Nails',
            'slug' => 'estheticians-nails',
        ]);

        Service::factory()->for($esthetics)->create([
            'name' => 'Cleanup',
            'target_gender' => 'ladies',
            'price' => 1500,
        ]);

        $duplicateResponse = $this->actingAs($owner)
            ->from(route('services.index'))
            ->post(route('services.store'), [
                'name' => 'Cleanup',
                'category_id' => $esthetics->id,
                'target_gender' => 'ladies',
                'price' => 1800,
                'duration_minutes' => 45,
            ]);

        $duplicateResponse->assertRedirect(route('services.index'));
        $duplicateResponse->assertSessionHasErrors([
            'name' => 'A service with this name already exists for the selected gender and department.',
        ]);

        $gentsResponse = $this->actingAs($owner)->post(route('services.store'), [
            'name' => 'Cleanup',
            'category_id' => $esthetics->id,
            'target_gender' => 'gents',
            'price' => 1500,
            'duration_minutes' => 45,
        ]);

        $gentsResponse->assertRedirect(route('services.index'));
        $this->assertDatabaseCount('services', 2);
    }

    public function test_store_rejects_empty_payload_with_validation_errors(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)
            ->from(route('services.index'))
            ->post(route('services.store'), []);

        $response->assertRedirect(route('services.index'));
        $response->assertSessionHasErrors([
            'name',
            'category_id',
            'target_gender',
            'price',
            'duration_minutes',
        ]);
    }

    public function test_update_modifies_service_pricing_duration_gender_and_package_flag(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $hairCategory = Category::create([
            'name' => 'Hair Stylists',
            'slug' => 'hair-stylists',
        ]);
        $bridalCategory = Category::create([
            'name' => 'Bridal & Makeup',
            'slug' => 'bridal-makeup',
        ]);

        $service = Service::factory()->for($hairCategory)->create([
            'name' => 'Full dressing',
            'target_gender' => 'ladies',
            'price' => 2500.00,
            'duration_minutes' => 60,
            'is_package' => false,
        ]);

        $response = $this->actingAs($owner)->put(route('services.update', $service), [
            'name' => 'Bridal Full Dressing & Draping',
            'category_id' => $bridalCategory->id,
            'target_gender' => 'ladies',
            'price' => 4500,
            'duration_minutes' => 90,
            'is_package' => 1,
        ]);

        $response->assertRedirect(route('services.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'category_id' => $bridalCategory->id,
            'name' => 'Bridal Full Dressing & Draping',
            'target_gender' => 'ladies',
            'price' => 4500.00,
            'duration_minutes' => 90,
            'is_package' => true,
        ]);
    }

    public function test_destroy_forbids_worker_with_403_and_allows_owner_to_delete_service(): void
    {
        $worker = User::factory()->create(['role' => 'worker']);
        $owner = User::factory()->create(['role' => 'owner']);
        $service = Service::factory()->create([
            'name' => 'Nose piercing',
            'price' => 800.00,
        ]);

        $forbiddenResponse = $this->actingAs($worker)->delete(route('services.destroy', $service));

        $forbiddenResponse->assertForbidden();
        $this->assertModelExists($service);

        $ownerResponse = $this->actingAs($owner)->delete(route('services.destroy', $service));

        $ownerResponse->assertRedirect(route('services.index'));
        $this->assertModelMissing($service);
    }
}
