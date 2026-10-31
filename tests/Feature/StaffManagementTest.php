<?php

namespace Tests\Feature;

use App\Livewire\StaffDirectory;
use App\Models\Category;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createStaffRecord(): Staff
    {
        $category = Category::create([
            'name' => 'Hair Stylists',
            'slug' => 'hair-stylists',
        ]);

        $worker = User::factory()->create([
            'name' => 'Amaya Jayawardena',
            'email' => 'amaya@salonhq.com',
            'phone_number' => '0769921044',
            'gender' => 'female',
            'role' => 'worker',
        ]);

        return Staff::create([
            'user_id' => $worker->id,
            'category_id' => $category->id,
            'job_title' => 'Creative Hair Stylist',
            'serves_gender' => 'both',
            'availability_status' => 'available',
            'experience' => '5 Years',
            'commission_percentage' => 20.00,
            'specialties' => ['Balayage', 'Keratin'],
        ]);
    }

    public function test_owner_can_edit_staff_member_via_livewire_and_http(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $staff = $this->createStaffRecord();

        Livewire::actingAs($owner)
            ->test(StaffDirectory::class)
            ->call('editStaff', $staff->id)
            ->assertSet('showEditModal', true)
            ->set('name', 'Amaya Perera')
            ->set('job_title', 'Lead Color Specialist')
            ->set('commission_percentage', '25.50')
            ->call('updateStaff')
            ->assertHasNoErrors()
            ->assertSet('showEditModal', false);

        $this->assertDatabaseHas('users', [
            'id' => $staff->user_id,
            'name' => 'Amaya Perera',
        ]);

        $this->assertDatabaseHas('staff', [
            'id' => $staff->id,
            'job_title' => 'Lead Color Specialist',
            'commission_percentage' => 25.50,
        ]);

        $response = $this->actingAs($owner)->put(route('staff.update', $staff->id), [
            'name' => 'Amaya Updated',
            'email' => 'amaya@salonhq.com',
            'phone_number' => '0771112233',
            'gender' => 'female',
            'job_title' => 'Principal Hair Director',
            'category' => 'Bridal & Makeup',
            'serves_gender' => 'female',
            'availability_status' => 'in_session',
            'experience' => '6 Years',
            'commission_percentage' => 30,
            'specialties' => 'Bridal Hair, Balayage',
            'bio' => 'Updated bio.',
        ]);

        $response->assertRedirect(route('staff.show', $staff->id));
        $this->assertDatabaseHas('staff', [
            'id' => $staff->id,
            'job_title' => 'Principal Hair Director',
            'serves_gender' => 'female',
        ]);
    }

    public function test_owner_can_delete_staff_member_and_worker_account(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $staff = $this->createStaffRecord();
        $workerUserId = $staff->user_id;

        Livewire::actingAs($owner)
            ->test(StaffDirectory::class)
            ->call('confirmDelete', $staff->id)
            ->assertSet('showDeleteModal', true)
            ->call('deleteStaff')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('staff', ['id' => $staff->id]);
        $this->assertDatabaseMissing('users', ['id' => $workerUserId]);
    }

    public function test_non_owner_cannot_edit_or_delete_staff_members(): void
    {
        $workerActor = User::factory()->create(['role' => 'worker']);
        $staff = $this->createStaffRecord();

        $this->actingAs($workerActor)
            ->put(route('staff.update', $staff->id), [
                'name' => 'Unauthorized Edit',
                'email' => 'amaya@salonhq.com',
                'phone_number' => '0769921044',
                'gender' => 'female',
                'job_title' => 'Hacked Title',
                'category' => 'Hair Stylists',
                'serves_gender' => 'both',
                'availability_status' => 'available',
                'experience' => '5 Years',
                'commission_percentage' => 50,
            ])
            ->assertForbidden();

        $this->actingAs($workerActor)
            ->delete(route('staff.destroy', $staff->id))
            ->assertForbidden();

        Livewire::actingAs($workerActor)
            ->test(StaffDirectory::class)
            ->call('editStaff', $staff->id)
            ->assertForbidden();

        Livewire::actingAs($workerActor)
            ->test(StaffDirectory::class)
            ->call('confirmDelete', $staff->id)
            ->assertForbidden();

        $this->assertDatabaseHas('staff', ['id' => $staff->id]);
    }
}
