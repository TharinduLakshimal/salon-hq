<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Display the salon services & pricing catalog loaded from MySQL.
     */
    public function index(Request $request): View
    {
        $categories = Category::query()->orderBy('id')->get();
        $categoriesList = $categories->map(fn (Category $category): array => [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ])->values()->all();

        $bookingCountsByService = Appointment::query()
            ->where('status', '!=', 'cancelled')
            ->selectRaw('LOWER(service_name) as service_key, COUNT(*) as bookings_count')
            ->groupByRaw('LOWER(service_name)')
            ->pluck('bookings_count', 'service_key');

        $services = Service::with('category')
            ->orderBy('category_id')
            ->orderBy('id')
            ->get()
            ->map(function (Service $service) use ($bookingCountsByService): array {
                $lookupKey = strtolower(trim($service->name));

                return [
                    'id' => $service->id,
                    'service_code' => sprintf('SRV-%03d', $service->id),
                    'name' => $service->name,
                    'category_id' => $service->category_id,
                    'category_name' => $service->category?->name ?? 'General Salon',
                    'target_gender' => $service->target_gender,
                    'price' => (float) $service->price,
                    'duration_minutes' => (int) $service->duration_minutes,
                    'is_package' => (bool) $service->is_package,
                    'bookings_count' => (int) ($bookingCountsByService[$lookupKey] ?? 0),
                    'updated_date' => $service->updated_at?->format('M d, Y') ?? 'Recently',
                ];
            })
            ->values()
            ->all();

        $openModalOnLoad = $request->boolean('new');
        $isOwner = $request->user()?->isOwner() ?? false;

        return view('services.index', compact(
            'services',
            'categoriesList',
            'openModalOnLoad',
            'isOwner'
        ));
    }

    /**
     * Store a newly created salon service or combo package in the services table.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateServicePayload($request);
        $categoryId = $this->resolveCategoryId($validated);

        $request->validate([
            'name' => [
                Rule::unique('services', 'name')->where(fn ($query) => $query
                    ->where('target_gender', $validated['target_gender'])
                    ->where('category_id', $categoryId)
                ),
            ],
        ], [
            'name.unique' => 'A service with this name already exists for the selected gender and department.',
        ]);

        $service = Service::create([
            'category_id' => $categoryId,
            'name' => trim($validated['name']),
            'target_gender' => $validated['target_gender'],
            'price' => $validated['price'],
            'duration_minutes' => (int) $validated['duration_minutes'],
            'is_package' => $request->boolean('is_package'),
        ]);

        return redirect()
            ->route('services.index')
            ->with('success', "Service \"{$service->name}\" added to the catalog successfully!");
    }

    /**
     * Update an existing salon service or combo package in the services table.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $this->validateServicePayload($request);
        $categoryId = $this->resolveCategoryId($validated);

        $request->validate([
            'name' => [
                Rule::unique('services', 'name')
                    ->where(fn ($query) => $query
                        ->where('target_gender', $validated['target_gender'])
                        ->where('category_id', $categoryId)
                    )
                    ->ignore($service->id),
            ],
        ], [
            'name.unique' => 'A service with this name already exists for the selected gender and department.',
        ]);

        $service->update([
            'category_id' => $categoryId,
            'name' => trim($validated['name']),
            'target_gender' => $validated['target_gender'],
            'price' => $validated['price'],
            'duration_minutes' => (int) $validated['duration_minutes'],
            'is_package' => $request->boolean('is_package'),
        ]);

        return redirect()
            ->route('services.index')
            ->with('success', "Service \"{$service->name}\" updated successfully!");
    }

    /**
     * Delete a service record from the catalog (Main Admin / Owner only).
     */
    public function destroy(Request $request, Service $service): RedirectResponse
    {
        abort_unless($request->user()?->isOwner(), 403, 'Only the main salon admin can delete salon services.');

        $serviceName = $service->name;
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', "\"{$serviceName}\" has been removed from the service catalog.");
    }

    /**
     * Validate base attributes shared by store and update actions.
     *
     * @return array<string, mixed>
     */
    private function validateServicePayload(Request $request): array
    {
        $isCreatingCategory = $request->input('category_id') === '__new__';

        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => $isCreatingCategory
                ? ['required', 'string', Rule::in(['__new__'])]
                : ['required', 'integer', Rule::exists('categories', 'id')],
            'new_category_name' => $isCreatingCategory
                ? ['required', 'string', 'max:80']
                : ['nullable', 'string', 'max:80'],
            'target_gender' => ['required', Rule::in(['ladies', 'gents', 'both'])],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:600'],
            'is_package' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Resolve an existing category ID or create a new salon department category when requested.
     *
     * @param  array<string, mixed>  $validated
     */
    private function resolveCategoryId(array $validated): int
    {
        if (($validated['category_id'] ?? null) === '__new__') {
            $trimmedCategory = trim((string) $validated['new_category_name']);

            $category = Category::firstOrCreate(
                ['slug' => Str::slug($trimmedCategory)],
                ['name' => $trimmedCategory]
            );

            return $category->id;
        }

        return (int) $validated['category_id'];
    }
}
