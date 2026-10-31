<!-- Alpine.js Edit & Delete Modals for Staff Profile Page (Main Admin Only) -->
<div x-show="editOpen" x-cloak style="display: none;" @click.self="editOpen = false" class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Edit Staff Profile</h2>
            <button type="button" @click="editOpen = false" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer">✕</button>
        </div>

        <form method="POST" action="{{ route('staff.update', $staff->id) }}" class="flex flex-col overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $staff->user?->name) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Job Title / Role *</label>
                    <input type="text" name="job_title" value="{{ old('job_title', $staff->job_title) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Department Category *</label>
                    @php $currentCat = old('category', $staff->category?->name ?? 'Hair Stylists'); @endphp
                    <select name="category" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        @foreach (['Hair Stylists', 'Bridal & Makeup', 'Barbers', 'Estheticians & Nails'] as $catOption)
                            <option value="{{ $catOption }}" @selected($currentCat === $catOption)>{{ $catOption }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Works For (Client Gender) *</label>
                    @php $currentServes = old('serves_gender', $staff->serves_gender); @endphp
                    <select name="serves_gender" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        <option value="both" @selected($currentServes === 'both')>⚥ Both (Unisex)</option>
                        <option value="female" @selected($currentServes === 'female')>♀ Female Only</option>
                        <option value="male" @selected($currentServes === 'male')>♂ Male Only</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Availability Status *</label>
                    @php $currentStatus = old('availability_status', $staff->availability_status); @endphp
                    <select name="availability_status" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        <option value="available" @selected($currentStatus === 'available')>Available</option>
                        <option value="in_session" @selected($currentStatus === 'in_session')>In Session</option>
                        <option value="on_break" @selected($currentStatus === 'on_break')>On Break</option>
                        <option value="off_duty" @selected($currentStatus === 'off_duty')>Off Duty</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Staff Member's Gender *</label>
                    @php $currentGender = old('gender', $staff->user?->gender ?? 'female'); @endphp
                    <select name="gender" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        <option value="female" @selected($currentGender === 'female')>Female</option>
                        <option value="male" @selected($currentGender === 'male')>Male</option>
                        <option value="other" @selected($currentGender === 'other')>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Commission (%) *</label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_percentage" value="{{ old('commission_percentage', $staff->commission_percentage) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Experience *</label>
                    <input type="text" name="experience" value="{{ old('experience', $staff->experience) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number *</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $staff->user?->phone_number) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Login Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $staff->user?->email) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Specialties / Skills (comma separated)</label>
                    <input type="text" name="specialties" value="{{ old('specialties', implode(', ', $staff->specialties ?? [])) }}" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Short Bio & Experience Summary</label>
                    <textarea name="bio" rows="3" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">{{ old('bio', $staff->bio) }}</textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                <button type="button" @click="editOpen = false" class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white shadow-sm cursor-pointer">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div x-show="deleteOpen" x-cloak style="display: none;" @click.self="deleteOpen = false" class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                    <path d="M10 11v6"></path>
                    <path d="M14 11v6"></path>
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                </svg>
            </div>

            <h3 class="text-lg font-bold text-slate-900 mb-1.5">Delete Staff Member?</h3>
            <p class="text-sm text-slate-500">
                Are you sure you want to permanently remove <strong class="text-slate-800">{{ $staff->user?->name }}</strong>? This action cannot be undone.
            </p>
        </div>

        <form method="POST" action="{{ route('staff.destroy', $staff->id) }}" class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
            @csrf
            @method('DELETE')
            <button type="button" @click="deleteOpen = false" class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer">
                Cancel
            </button>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-xs font-semibold text-white shadow-sm cursor-pointer">
                Yes, Delete Staff
            </button>
        </form>
    </div>
</div>

