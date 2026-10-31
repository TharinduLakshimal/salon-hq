<!-- Add / Edit Client Modal (Connected to MySQL users table) -->
<div
    x-show="showFormModal"
    x-transition.opacity
    @click.self="showFormModal = false"
    class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
    style="display: none;"
>
    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900" x-text="editingClientId ? 'Edit Client Profile' : 'Add New Salon Client'">Add New Salon Client</h2>
                <p class="text-xs text-slate-500 mt-0.5">Saves client details directly to the Salon HQ MySQL database.</p>
            </div>
            <button
                type="button"
                @click="showFormModal = false"
                aria-label="Close modal"
                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer"
            >
                ✕
            </button>
        </div>

        <form
            method="POST"
            :action="editingClientId ? baseClientsUrl + '/' + editingClientId : storeClientUrl"
            class="flex flex-col overflow-hidden"
        >
            @csrf
            <template x-if="editingClientId">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <input type="hidden" name="editing_client_id" :value="editingClientId || ''">

            <div class="p-5 sm:p-6 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name *</label>
                    <input
                        type="text"
                        name="name"
                        x-model="form.name"
                        required
                        placeholder="e.g. Senuri Jayawardena"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                    @error('name')
                        <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Mobile Number *</label>
                    <input
                        type="text"
                        name="phone_number"
                        x-model="form.phone_number"
                        required
                        placeholder="e.g. 077 450 1122"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                    @error('phone_number')
                        <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Explicit Interactive Client Gender Radio Cards -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Client Gender *</label>
                    <input type="hidden" name="gender" :value="form.gender">
                    <div class="grid grid-cols-2 gap-3" role="radiogroup" aria-label="Client Gender">
                        <button
                            type="button"
                            role="radio"
                            :aria-checked="form.gender === 'female'"
                            @click="form.gender = 'female'"
                            :class="form.gender === 'female'
                                ? 'border-pink-500 bg-pink-50 text-pink-700 shadow-2xs'
                                : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50'"
                            class="rounded-xl border-2 p-3 text-left flex items-center justify-between gap-2.5 transition cursor-pointer"
                        >
                            <div>
                                <div class="text-xs font-bold">♀ Female Client</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Ladies' Salon, Bridal & Nails</div>
                            </div>
                            <span
                                :class="form.gender === 'female'
                                    ? 'border-pink-600 bg-pink-600 text-white'
                                    : 'border-slate-300 bg-white text-transparent'"
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center text-[11px] font-bold shrink-0 transition"
                            >
                                ✓
                            </span>
                        </button>

                        <button
                            type="button"
                            role="radio"
                            :aria-checked="form.gender === 'male'"
                            @click="form.gender = 'male'"
                            :class="form.gender === 'male'
                                ? 'border-sky-500 bg-sky-50 text-sky-700 shadow-2xs'
                                : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50'"
                            class="rounded-xl border-2 p-3 text-left flex items-center justify-between gap-2.5 transition cursor-pointer"
                        >
                            <div>
                                <div class="text-xs font-bold">♂ Male Client</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Gents' Barber, Grooming & Skin</div>
                            </div>
                            <span
                                :class="form.gender === 'male'
                                    ? 'border-sky-600 bg-sky-600 text-white'
                                    : 'border-slate-300 bg-white text-transparent'"
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center text-[11px] font-bold shrink-0 transition"
                            >
                                ✓
                            </span>
                        </button>
                    </div>
                    @error('gender')
                        <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Email Address *
                        <span x-show="!editingClientId" class="font-normal text-slate-400">(Default login password: <code>password</code>)</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        x-model="form.email"
                        required
                        placeholder="e.g. senuri@example.com"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                    @error('email')
                        <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                <button
                    type="button"
                    @click="showFormModal = false"
                    class="px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white shadow-sm shadow-indigo-600/20 cursor-pointer"
                    x-text="editingClientId ? 'Update Client' : 'Save Client to Database'"
                >
                    Save Client to Database
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Client 360° Database Profile Modal -->
<div
    x-show="selectedClient !== null"
    x-transition.opacity
    @click.self="selectedClient = null"
    class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
    style="display: none;"
>
    <template x-if="selectedClient">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] flex flex-col">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div
                        class="w-11 h-11 rounded-2xl text-white font-bold text-sm flex items-center justify-center shrink-0"
                        :style="'background: ' + selectedClient.avatar_gradient"
                        x-text="selectedClient.avatar_initials"
                    ></div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <h3 class="text-base font-bold text-slate-900 truncate" x-text="selectedClient.name"></h3>
                            <span
                                :class="selectedClient.gender === 'female' ? 'text-pink-600' : 'text-sky-600'"
                                class="font-bold"
                                x-text="selectedClient.gender === 'female' ? '♀' : '♂'"
                            ></span>
                        </div>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="font-mono text-[11px] font-semibold text-slate-400" x-text="selectedClient.client_code"></span>
                            <span
                                :class="tierBadgeClass(selectedClient.membership_tier)"
                                class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                x-text="selectedClient.membership_tier"
                            ></span>
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    @click="selectedClient = null"
                    aria-label="Close profile"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center shrink-0 cursor-pointer"
                >
                    ✕
                </button>
            </div>

            <div class="p-5 sm:p-6 overflow-y-auto space-y-4 text-sm">
                <!-- Contact Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3">
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Contact Details</div>
                        <div class="font-bold text-slate-900 mt-1" x-text="selectedClient.phone_number"></div>
                        <div class="text-slate-500 truncate mt-0.5" x-text="selectedClient.email"></div>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-3">
                        <div class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Account & Stylist</div>
                        <div class="font-bold text-indigo-700 mt-1 truncate" x-text="selectedClient.preferred_stylist"></div>
                        <div class="text-slate-500 mt-0.5">
                            Joined <span x-text="selectedClient.joined_date"></span>
                        </div>
                    </div>
                </div>

                <!-- Loyalty & Spend Summary -->
                <div class="rounded-xl bg-indigo-50/60 border border-indigo-100 p-3.5 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <div class="text-xs text-slate-600">
                            <strong class="text-slate-900" x-text="selectedClient.total_visits"></strong> completed visits &bull;
                            Total Spend: <strong class="text-emerald-700">Rs. <span x-text="formatNumber(selectedClient.total_spent)"></span></strong>
                        </div>
                        <div class="text-xs font-bold text-amber-700 mt-0.5">
                            Earned Loyalty Points: <span x-text="formatNumber(selectedClient.loyalty_points)"></span> pts
                        </div>
                    </div>
                    <span
                        x-show="selectedClient.email_verified"
                        class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[11px] font-semibold"
                    >
                        Verified Account
                    </span>
                </div>

                <!-- Completed Salon Works History from Database -->
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Recorded Salon Services (MySQL)</div>
                    <div x-show="!selectedClient.recent_services || selectedClient.recent_services.length === 0" class="text-xs text-slate-400 bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                        No completed salon services recorded yet for this client.
                    </div>
                    <div class="space-y-2" x-show="selectedClient.recent_services && selectedClient.recent_services.length > 0">
                        <template x-for="(visit, idx) in selectedClient.recent_services" :key="idx">
                            <div class="rounded-xl border border-slate-200 px-3.5 py-2.5 flex items-center justify-between gap-2 text-xs">
                                <div class="min-w-0">
                                    <div class="font-semibold text-slate-800 truncate" x-text="visit.service"></div>
                                    <div class="text-slate-400">
                                        <span x-text="visit.date"></span> &bull; Stylist: <span class="text-slate-600 font-medium" x-text="visit.stylist"></span>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-900 shrink-0">Rs. <span x-text="formatNumber(visit.amount)"></span></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-slate-200 bg-slate-50 flex flex-wrap items-center justify-between gap-2">
                <button
                    type="button"
                    @click="openEditModal(selectedClient); selectedClient = null"
                    class="px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer"
                >
                    Edit Client Details
                </button>
                <a
                    :href="bookingUrlForClient(selectedClient)"
                    wire:navigate
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white shadow-sm shadow-indigo-600/20"
                >
                    + Book New Appointment
                </a>
            </div>
        </div>
    </template>
</div>

@if ($isOwner)
    <!-- Owner-Only Delete Client Database Confirmation Modal -->
    <div
        x-show="clientToDelete !== null"
        x-transition.opacity
        @click.self="clientToDelete = null"
        class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
        style="display: none;"
    >
        <template x-if="clientToDelete">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden p-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Remove Client Profile?</h3>
                <p class="text-xs text-slate-500 mt-1.5">
                    Are you sure you want to permanently delete <strong class="text-slate-800" x-text="clientToDelete.name"></strong> (<span x-text="clientToDelete.client_code"></span>) from the MySQL database?
                </p>

                <form method="POST" :action="baseClientsUrl + '/' + clientToDelete.id" class="mt-5 flex items-center justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button
                        type="button"
                        @click="clientToDelete = null"
                        class="px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-xs font-semibold text-white cursor-pointer"
                    >
                        Yes, Delete Client
                    </button>
                </form>
            </div>
        </template>
    </div>
@endif
