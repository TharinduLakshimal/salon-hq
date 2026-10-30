<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Seed real Salon HQ Ladies & Gents services grouped under salon categories.
     */
    public function run(): void
    {
        $categoryNames = [
            'Bridal & Makeup',
            'Hair Stylists',
            'Barbers',
            'Estheticians & Nails',
        ];

        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[$name] = Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => "{$name} department at Salon HQ."]
            );
        }

        $services = [
            // =========================================================
            // LADIES (target_gender: 'ladies')
            // =========================================================

            // Threading & Piercing -> Estheticians & Nails
            ['category' => 'Estheticians & Nails', 'name' => 'Eyebrows threading', 'target_gender' => 'ladies', 'price' => 200, 'duration_minutes' => 15, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Full face threading', 'target_gender' => 'ladies', 'price' => 600, 'duration_minutes' => 30, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Nose piercing', 'target_gender' => 'ladies', 'price' => 800, 'duration_minutes' => 20, 'is_package' => false],

            // Facial & Skincare -> Estheticians & Nails
            ['category' => 'Estheticians & Nails', 'name' => 'Cleanup', 'target_gender' => 'ladies', 'price' => 1500, 'duration_minutes' => 45, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Hydra cleanup', 'target_gender' => 'ladies', 'price' => 5000, 'duration_minutes' => 60, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Gold Facial', 'target_gender' => 'ladies', 'price' => 4500, 'duration_minutes' => 60, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Hydra Facial', 'target_gender' => 'ladies', 'price' => 10000, 'duration_minutes' => 75, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Pimple treatment', 'target_gender' => 'ladies', 'price' => 2500, 'duration_minutes' => 45, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Microblading', 'target_gender' => 'ladies', 'price' => 12000, 'duration_minutes' => 120, 'is_package' => false],

            // Waxing -> Estheticians & Nails
            ['category' => 'Estheticians & Nails', 'name' => 'Hand wax', 'target_gender' => 'ladies', 'price' => 3000, 'duration_minutes' => 30, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Leg wax', 'target_gender' => 'ladies', 'price' => 4000, 'duration_minutes' => 45, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Under arms', 'target_gender' => 'ladies', 'price' => 1500, 'duration_minutes' => 20, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Full body', 'target_gender' => 'ladies', 'price' => 8000, 'duration_minutes' => 90, 'is_package' => false],

            // Hair Cut & Style -> Hair Stylists
            ['category' => 'Hair Stylists', 'name' => 'Long length cut', 'target_gender' => 'ladies', 'price' => 2000, 'duration_minutes' => 45, 'is_package' => false],
            ['category' => 'Hair Stylists', 'name' => 'Short length cut', 'target_gender' => 'ladies', 'price' => 1500, 'duration_minutes' => 40, 'is_package' => false],
            ['category' => 'Hair Stylists', 'name' => 'U/V/Bob cut', 'target_gender' => 'ladies', 'price' => 500, 'duration_minutes' => 30, 'is_package' => false],
            ['category' => 'Hair Stylists', 'name' => 'Hair wash', 'target_gender' => 'ladies', 'price' => 700, 'duration_minutes' => 20, 'is_package' => false],

            // Hair Texture -> Hair Stylists
            ['category' => 'Hair Stylists', 'name' => 'Keratin', 'target_gender' => 'ladies', 'price' => 12000, 'duration_minutes' => 180, 'is_package' => false],
            ['category' => 'Hair Stylists', 'name' => 'Straightening', 'target_gender' => 'ladies', 'price' => 10000, 'duration_minutes' => 180, 'is_package' => false],
            ['category' => 'Hair Stylists', 'name' => 'Rebonding', 'target_gender' => 'ladies', 'price' => 12000, 'duration_minutes' => 210, 'is_package' => false],

            // Hair Colors -> Hair Stylists
            ['category' => 'Hair Stylists', 'name' => 'Black color Keune Full', 'target_gender' => 'ladies', 'price' => 4000, 'duration_minutes' => 60, 'is_package' => false],
            ['category' => 'Hair Stylists', 'name' => 'Black color Dreamron Full', 'target_gender' => 'ladies', 'price' => 3500, 'duration_minutes' => 60, 'is_package' => false],

            // Hands & Legs -> Estheticians & Nails
            ['category' => 'Estheticians & Nails', 'name' => 'Gel nails', 'target_gender' => 'ladies', 'price' => 2500, 'duration_minutes' => 60, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Acrylic nail extension', 'target_gender' => 'ladies', 'price' => 5000, 'duration_minutes' => 90, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Gel manicure', 'target_gender' => 'ladies', 'price' => 3000, 'duration_minutes' => 45, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Gel pedicure', 'target_gender' => 'ladies', 'price' => 4000, 'duration_minutes' => 60, 'is_package' => false],

            // Dressing -> Bridal & Makeup
            ['category' => 'Bridal & Makeup', 'name' => 'Full dressing', 'target_gender' => 'ladies', 'price' => 2500, 'duration_minutes' => 90, 'is_package' => false],
            ['category' => 'Bridal & Makeup', 'name' => 'Makeup', 'target_gender' => 'ladies', 'price' => 1000, 'duration_minutes' => 45, 'is_package' => false],
            ['category' => 'Bridal & Makeup', 'name' => 'Hair styles', 'target_gender' => 'ladies', 'price' => 1000, 'duration_minutes' => 45, 'is_package' => false],

            // =========================================================
            // GENTS (target_gender: 'gents')
            // =========================================================

            // Hair & Beard -> Barbers
            ['category' => 'Barbers', 'name' => 'Haircut', 'target_gender' => 'gents', 'price' => 600, 'duration_minutes' => 30, 'is_package' => false],
            ['category' => 'Barbers', 'name' => 'Beard cut', 'target_gender' => 'gents', 'price' => 500, 'duration_minutes' => 20, 'is_package' => false],
            ['category' => 'Barbers', 'name' => 'Head massage', 'target_gender' => 'gents', 'price' => 600, 'duration_minutes' => 25, 'is_package' => false],

            // Treatments -> Hair Stylists
            ['category' => 'Hair Stylists', 'name' => 'Keratin treatment', 'target_gender' => 'gents', 'price' => 10000, 'duration_minutes' => 150, 'is_package' => false],
            ['category' => 'Hair Stylists', 'name' => 'Dandruff treatment', 'target_gender' => 'gents', 'price' => 2000, 'duration_minutes' => 45, 'is_package' => false],

            // Colors -> Barbers
            ['category' => 'Barbers', 'name' => 'Fashion color', 'target_gender' => 'gents', 'price' => 2000, 'duration_minutes' => 60, 'is_package' => false],
            ['category' => 'Barbers', 'name' => 'Black colour Keune Full', 'target_gender' => 'gents', 'price' => 3500, 'duration_minutes' => 45, 'is_package' => false],

            // Face -> Estheticians & Nails
            ['category' => 'Estheticians & Nails', 'name' => 'Cleanup', 'target_gender' => 'gents', 'price' => 1500, 'duration_minutes' => 45, 'is_package' => false],
            ['category' => 'Estheticians & Nails', 'name' => 'Hydra Facial', 'target_gender' => 'gents', 'price' => 10000, 'duration_minutes' => 75, 'is_package' => false],

            // Gents Packages (is_package: true) -> Barbers
            ['category' => 'Barbers', 'name' => 'Haircut + Beard cut', 'target_gender' => 'gents', 'price' => 1000, 'duration_minutes' => 45, 'is_package' => true],
            ['category' => 'Barbers', 'name' => 'Haircut + Beard cut + Hair wash', 'target_gender' => 'gents', 'price' => 1300, 'duration_minutes' => 60, 'is_package' => true],
            ['category' => 'Barbers', 'name' => 'Haircut + Beard cut + Head Massage', 'target_gender' => 'gents', 'price' => 1500, 'duration_minutes' => 65, 'is_package' => true],
            ['category' => 'Barbers', 'name' => 'Haircut + Beard cut + Oil treatment + Cleanup', 'target_gender' => 'gents', 'price' => 3600, 'duration_minutes' => 105, 'is_package' => true],
        ];

        foreach ($services as $serviceData) {
            $category = $categories[$serviceData['category']];

            Service::updateOrCreate(
                [
                    'name' => $serviceData['name'],
                    'target_gender' => $serviceData['target_gender'],
                ],
                [
                    'category_id' => $category->id,
                    'price' => $serviceData['price'],
                    'duration_minutes' => $serviceData['duration_minutes'],
                    'is_package' => $serviceData['is_package'],
                ]
            );
        }
    }
}
