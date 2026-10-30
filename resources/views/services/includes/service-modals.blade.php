<!-- Add / Edit Salon Service & Pricing Modal (Connected to MySQL services table) -->
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
                <h2
                    class="text-base sm:text-lg font-bold text-slate-900"
                    x-text="editingServiceId ? 'Edit Service & Pricing' : 'Add New Salon Service'"
                >
                    Add New Salon Service
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Updates the live service catalog and appointment booking menu immediately.
                </p>
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
            :action="editingServiceId ? baseServicesUrl + '/' + editingServiceId : storeServiceUrl"
            class="flex flex-col overflow-hidden"
        >
            @csrf
            <template x-if="editingServiceId">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <input type="hidden" name="editing_service_id" :value="editingServiceId || ''">
            <input type="hidden" name="target_gender" :value="form.target_gender">
            <input type="hidden" name="is_package" :value="form.is_package ? 1 : 0">

            <div class="p-5 sm:p-6 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Service / Package Name -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Service or Package Name *</label>
                    <input
                        type="text"
                        name="name"
                        x-model="form.name"
                        required
                        placeholder="e.g. Hydra Facial or Haircut + Beard cut"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                    @error('name')
                        <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Salon Department Category -->
                <div :class="form.category_id === '__new__' ? 'sm:col-span-1' : 'sm:col-span-2'">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Salon Department Category *</label>
                    <select
                        name="category_id"
                        x-model="form.category_id"
                        required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                        <template x-for="cat in categories" :key="cat.id">
                            <option
                                :value="String(cat.id)"
                                :selected="String(form.category_id) === String(cat.id)"
                                x-text="cat.name"
                            ></option>
                        </template>
                        <option value="__new__" :selected="form.category_id === '__new__'">+ Create New Department...</option>
                    </select>
                    @error('category_id')
                        <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Conditional New Department Name -->
                <div x-show="form.category_id === '__new__'" class="sm:col-span-1" style="display: none;">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">New Department Name *</label>
                    <input
                        type="text"
                        name="new_category_name"
                        x-model="form.new_category_name"
                        :required="form.category_id === '__new__'"
                        placeholder="e.g. Spa & Massage"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                    @error('new_category_name')
                        <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Target Gender Radio Cards -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target Client Gender *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5" role="radiogroup" aria-label="Target Gender">
                        <button
                            type="button"
                            role="radio"
                            :aria-checked="form.target_gender === 'ladies'"
                            @click="form.target_gender = 'ladies'"
                            :class="form.target_gender === 'ladies'
                                ? 'border-pink-500 bg-pink-50 text-pink-700 shadow-2xs'
                                : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50'"
                            class="rounded-xl border-2 p-3 text-left flex items-center justify-between gap-2 transition cursor-pointer"
                        >
                            <div>
                                <div class="text-xs font-bold">♀ Ladies</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Female clients</div>
                            </div>
                            <span
                                :class="form.target_gender === 'ladies'
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
                            :aria-checked="form.target_gender === 'gents'"
                            @click="form.target_gender = 'gents'"
                            :class="form.target_gender === 'gents'
                                ? 'border-sky-500 bg-sky-50 text-sky-700 shadow-2xs'
                                : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50'"
                            class="rounded-xl border-2 p-3 text-left flex items-center justify-between gap-2 transition cursor-pointer"
                        >
                            <div>
                                <div class="text-xs font-bold">♂ Gents</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Male clients</div>
                            </div>
                            <span
                                :class="form.target_gender === 'gents'
                                    ? 'border-sky-600 bg-sky-600 text-white'
                                    : 'border-slate-300 bg-white text-transparent'"
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center text-[11px] font-bold shrink-0 transition"
                            >
                                ✓
                            </span>
                        </button>

                        <button
                            type="button"
                            role="radio"
                            :aria-checked="form.target_gender === 'both'"
                            @click="form.target_gender = 'both'"
                            :class="form.target_gender === 'both'
                                ? 'border-purple-500 bg-purple-50 text-purple-700 shadow-2xs'
                                : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50'"
                            class="rounded-xl border-2 p-3 text-left flex items-center justify-between gap-2 transition cursor-pointer"
                        >
                            <div>
                                <div class="text-xs font-bold">⚥ Unisex</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">Ladies &amp; Gents</div>
                            </div>
                            <span
                                :class="form.target_gender === 'both'
                                    ? 'border-purple-600 bg-purple-600 text-white'
                                    : 'border-slate-300 bg-white text-transparent'"
                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center text-[11px] font-bold shrink-0 transition"
                            >
                                ✓
                            </span>
                        </button>
                    </div>
                    @error('target_gender')
                        <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Price (LKR) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Price (LKR / Rs.) *</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rs.</span>
                        <input
                            type="number"
                            name="price"
                            x-model="form.price"
                            min="0"
                            step="0.01"
                            required
                            placeholder="2500"
                            class="w-full rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 text-sm font-semibold text-slate-900 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                        >
                    </div>
                    @error('price')
                        <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Duration (Minutes) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Est. Duration (Minutes) *</label>
                    <input
                        type="number"
                        name="duration_minutes"
                        x-model.number="form.duration_minutes"
                        min="5"
                        max="600"
                        step="5"
                        required
                        placeholder="30"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-semibold text-slate-900 focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none"
                    >
                    <div class="flex flex-wrap items-center gap-1 mt-2">
                        <template x-for="mins in durationPresets" :key="mins">
                            <button
                                type="button"
                                @click="form.duration_minutes = mins"
                                :class="Number(form.duration_minutes) === mins
                                    ? 'bg-indigo-600 text-white border-indigo-600'
                                    : 'bg-slate-50 text-slate-600 border-slate-200 hover:border-slate-300'"
                                class="px-2 py-0.5 rounded-md border text-[11px] font-semibold transition cursor-pointer"
                                x-text="mins + 'm'"
                            ></button>
                        </template>
                    </div>
                    @error('duration_minutes')
                        <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Value / Combo Package Toggle Card -->
                <div class="sm:col-span-2">
                    <button
                        type="button"
                        @click="form.is_package = !form.is_package"
                        :class="form.is_package
                            ? 'border-amber-400 bg-amber-50/70'
                            : 'border-slate-200 bg-slate-50/60 hover:border-slate-300'"
                        class="w-full rounded-xl border p-3.5 text-left flex items-center justify-between gap-3 transition cursor-pointer"
                    >
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                <span>★ Mark as Combo / Value Package</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Enable for bundled multi-service offers (e.g. Haircut + Beard cut + Cleanup).
                            </p>
                        </div>

                        <span
                            :class="form.is_package ? 'bg-amber-500 justify-end' : 'bg-slate-300 justify-start'"
                            class="w-10 h-6 rounded-full p-0.5 flex items-center shrink-0 transition"
                        >
                            <span class="w-5 h-5 rounded-full bg-white shadow-2xs"></span>
                        </span>
                    </button>
                </div>

                <!-- Live Catalog Preview Strip -->
                <div class="sm:col-span-2 rounded-xl bg-indigo-50/70 border border-indigo-100 px-4 py-3 flex flex-wrap items-center justify-between gap-2">
                    <div class="text-xs text-slate-600 min-w-0">
                        <span class="font-bold text-slate-900" x-text="form.name.trim() || 'Service Preview'"></span>
                        <span x-show="form.is_package" class="ml-1 px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold">Package</span>
                        <div class="text-[11px] text-slate-500 mt-0.5">
                            <span x-text="selectedFormCategoryName()"></span>
                            &bull; <span x-text="genderLabel(form.target_gender)"></span>
                            &bull; <span x-text="(form.duration_minutes || 30) + ' mins'"></span>
                        </div>
                    </div>
                    <div class="text-sm font-extrabold text-indigo-700 shrink-0">
                        Rs. <span x-text="formatCurrency(form.price || 0)"></span>
                    </div>
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
                    x-text="editingServiceId ? 'Update Service & Price' : 'Save Service to Catalog'"
                >
                    Save Service to Catalog
                </button>
            </div>
        </form>
    </div>
</div>

@if ($isOwner)
    <!-- Owner-Only Delete Service Confirmation Modal -->
    <div
        x-show="serviceToDelete !== null"
        x-transition.opacity
        @click.self="serviceToDelete = null"
        class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
        style="display: none;"
    >
        <template x-if="serviceToDelete">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden p-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Remove Salon Service?</h3>
                <p class="text-xs text-slate-500 mt-1.5">
                    Are you sure you want to permanently remove <strong class="text-slate-800" x-text="serviceToDelete.name"></strong>
                    (<span x-text="'Rs. ' + formatCurrency(serviceToDelete.price)"></span>) from the Salon HQ catalog?
                </p>

                <form method="POST" :action="baseServicesUrl + '/' + serviceToDelete.id" class="mt-5 flex items-center justify-center gap-3">
                    @csrf
                    @method('DELETE')
                    <button
                        type="button"
                        @click="serviceToDelete = null"
                        class="px-4 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-xs font-semibold text-white cursor-pointer"
                    >
                        Yes, Delete Service
                    </button>
                </form>
            </div>
        </template>
    </div>
@endif

