<!-- Livewire Edit Staff Modal (Main Admin Only) -->
@if ($showEditModal)
    <div
        wire:click.self="closeModal"
        class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
    >
        <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Edit Staff Member</h2>
                <button type="button" wire:click="closeModal" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer">✕</button>
            </div>

            <form wire:submit="updateStaff" class="flex flex-col overflow-hidden">
                <div class="p-6 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name *</label>
                        <input type="text" wire:model="name" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Job Title / Role *</label>
                        <input type="text" wire:model="job_title" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        @error('job_title') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Works For (Client Gender) *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="serves_gender" value="both" class="peer sr-only">
                                <div class="rounded-xl border-2 border-slate-200 p-3 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                                    <div class="text-xs font-bold">⚥ Both (Unisex)</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Male & Female Clients</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="serves_gender" value="female" class="peer sr-only">
                                <div class="rounded-xl border-2 border-slate-200 p-3 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                                    <div class="text-xs font-bold">♀ Female Only</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Ladies' Salon & Bridal</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="serves_gender" value="male" class="peer sr-only">
                                <div class="rounded-xl border-2 border-slate-200 p-3 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                                    <div class="text-xs font-bold">♂ Male Only</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Gents' Barber & Grooming</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Department Category *</label>
                        <select wire:model="category" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                            <option value="Hair Stylists">Hair Stylists</option>
                            <option value="Bridal & Makeup">Bridal & Makeup</option>
                            <option value="Barbers">Barbers</option>
                            <option value="Estheticians & Nails">Estheticians & Nails</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Availability Status *</label>
                        <select wire:model="availability_status" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                            <option value="available">Available</option>
                            <option value="in_session">In Session</option>
                            <option value="on_break">On Break</option>
                            <option value="off_duty">Off Duty</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Staff Member's Gender *</label>
                        <select wire:model="gender" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                            <option value="female">Female</option>
                            <option value="male">Male</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Commission (%) *</label>
                        <input type="number" step="0.01" min="0" max="100" wire:model="commission_percentage" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        @error('commission_percentage') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Experience *</label>
                        <input type="text" wire:model="experience" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        @error('experience') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number *</label>
                        <input type="text" wire:model="phone_number" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        @error('phone_number') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Login Email Address *</label>
                        <input type="email" wire:model="email" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                        @error('email') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Specialties / Skills (comma separated)</label>
                        <input type="text" wire:model="specialties" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Short Bio & Experience Summary</label>
                        <textarea wire:model="bio" rows="3" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white shadow-sm cursor-pointer">
                        <span wire:loading.remove wire:target="updateStaff">Update Staff Member</span>
                        <span wire:loading wire:target="updateStaff">Updating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

