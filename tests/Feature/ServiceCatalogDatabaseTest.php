<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Service;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceCatalogDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_and_category_relationships_and_defaults_work(): void
    {
        $category = Category::create([
            'name' => 'Estheticians & Nails',
            'slug' => 'estheticians-nails',
        ]);

        $service = Service::create([
            'category_id' => $category->id,
            'name' => 'Hydra Facial',
            'target_gender' => 'ladies',
            'price' => 10000,
        ]);

        $this->assertModelExists($service);
        $this->assertSame(30, $service->duration_minutes);
        $this->assertFalse($service->is_package);
        $this->assertSame('Rs. 10,000', $service->formatted_price);
        $this->assertSame($category->id, $service->category?->id);
        $this->assertCount(1, $category->services);

        $category->delete();

        $this->assertModelMissing($service);
    }

    public function test_service_seeder_populates_all_ladies_gents_and_package_services_idempotently(): void
    {
        $this->seed(ServiceSeeder::class);
        $this->seed(ServiceSeeder::class);

        $this->assertDatabaseCount('services', 42);
        $this->assertSame(29, Service::where('target_gender', 'ladies')->count());
        $this->assertSame(13, Service::where('target_gender', 'gents')->count());
        $this->assertSame(4, Service::where('is_package', true)->count());

        $this->assertDatabaseHas('services', [
            'name' => 'Hydra Facial',
            'target_gender' => 'ladies',
            'price' => 10000.00,
            'is_package' => false,
        ]);
        $this->assertDatabaseHas('services', [
            'name' => 'Hydra Facial',
            'target_gender' => 'gents',
            'price' => 10000.00,
            'is_package' => false,
        ]);
        $this->assertDatabaseHas('services', [
            'name' => 'Haircut + Beard cut + Oil treatment + Cleanup',
            'target_gender' => 'gents',
            'price' => 3600.00,
            'is_package' => true,
        ]);
    }
}
