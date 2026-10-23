<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class StaffDirectory extends Component
{
    public string $search = '';

    public string $activeCategory = 'all';

    public string $activeGender = 'all';

    public bool $showAddModal = false;

    // Add New Staff Form Fields
    public string $name = '';

    public string $job_title = '';

    public string $serves_gender = 'both';

    public string $category = 'Hair Stylists';

    public string $gender = 'female';

    public string $experience = '';

    public string $phone_number = '';

    public string $email = '';

    public string $specialties = '';

    public string $bio = '';

    public ?string $successMessage = null;

    public function setCategory(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function setGender(string $gender): void
    {
        $this->activeGender = $gender;
    }

    public function openModal(): void
    {
        $this->resetValidation();
        $this->showAddModal = true;
    }

    public function closeModal(): void
    {
        $this->showAddModal = false;
    }

    public function saveStaff(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:25'],
            'gender' => ['required', 'in:male,female,other'],
            'job_title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80'],
            'serves_gender' => ['required', 'in:both,female,male'],
            'experience' => ['required', 'string', 'max:50'],
            'specialties' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
        ]);

        $skills = ! empty($validated['specialties'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['specialties']))))
            : ['Salon Styling', 'Client Care'];

        $gradients = [
            'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
            'linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%)',
            'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            'linear-gradient(135deg, #ec4899 0%, #e11d48 100%)',
            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
        ];

        DB::transaction(function () use ($validated, $skills, $gradients) {
            $categoryModel = Category::firstOrCreate(
                ['slug' => Str::slug($validated['category'])],
                ['name' => $validated['category']]
            );

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => 'password',
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'role' => 'worker',
                'email_verified_at' => now(),
            ]);

            Staff::create([
                'user_id' => $user->id,
                'category_id' => $categoryModel->id,
                'job_title' => $validated['job_title'],
                'serves_gender' => $validated['serves_gender'],
                'availability_status' => 'available',
                'experience' => $validated['experience'],
                'joined_date' => now()->format('F Y'),
                'commission_percentage' => 20.00,
                'rating' => 5.0,
                'reviews_count' => 0,
                'completed_works' => 0,
                'today_bookings' => 0,
                'monthly_revenue' => 0.00,
                'repeat_clients' => '100%',
                'avatar_gradient' => $gradients[array_rand($gradients)],
                'bio' => $validated['bio'] ?: 'Dedicated salon specialist at Salon HQ.',
                'specialties' => $skills,
                'schedule' => [
                    'Mon - Sat' => '09:00 AM - 06:30 PM',
                    'Sunday' => 'Off Duty',
                ],
            ]);
        });

        $this->reset(['name', 'job_title', 'serves_gender', 'category', 'gender', 'experience', 'phone_number', 'email', 'specialties', 'bio']);
        $this->serves_gender = 'both';
        $this->category = 'Hair Stylists';
        $this->gender = 'female';
        $this->showAddModal = false;
        $this->successMessage = 'New staff member saved to MySQL database via Livewire!';
    }

    public function render(): View
    {
        $query = Staff::with(['user', 'category', 'works'])->orderBy('id');

        if ($this->activeCategory !== 'all') {
            $query->whereHas('category', function ($cq) {
                $cq->where('name', $this->activeCategory);
            });
        }

        if ($this->activeGender !== 'all') {
            $query->where('serves_gender', $this->activeGender);
        }

        if (trim($this->search) !== '') {
            $searchTerm = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('job_title', 'like', $searchTerm)
                    ->orWhere('specialties', 'like', $searchTerm)
                    ->orWhereHas('category', function ($cq) use ($searchTerm) {
                        $cq->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('user', function ($uq) use ($searchTerm) {
                        $uq->where('name', 'like', $searchTerm)
                            ->orWhere('phone_number', 'like', $searchTerm);
                    });
            });
        }

        return view('livewire.staff-directory', [
            'staffMembers' => $query->get(),
            'totalCount' => Staff::count(),
        ]);
    }
}
