@if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm font-semibold text-emerald-800 flex items-center justify-between"
    >
        <span>{{ session('success') }}</span>
        <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">✕</button>
    </div>
@endif

@if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-medium text-red-600 flex items-center justify-between"
    >
        <span>{{ session('error') }}</span>
        <button type="button" @click="show = false" class="text-red-500 hover:text-red-800 cursor-pointer">✕</button>
    </div>
@endif

