<!-- Livewire Delete Staff Confirmation Modal (Main Admin Only) -->
@if ($showDeleteModal)
    <div
        wire:click.self="closeModal"
        class="fixed inset-0 z-50 bg-slate-900/55 backdrop-blur-xs flex items-center justify-center p-4"
    >
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
                    Are you sure you want to permanently remove <strong class="text-slate-800">{{ $deletingStaffName }}</strong>? This will delete their staff profile, shifts, portfolio works, and worker login account.
                </p>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 cursor-pointer">
                    Cancel
                </button>
                <button type="button" wire:click="deleteStaff" class="px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-xs font-semibold text-white shadow-sm cursor-pointer">
                    <span wire:loading.remove wire:target="deleteStaff">Yes, Delete Staff</span>
                    <span wire:loading wire:target="deleteStaff">Deleting...</span>
                </button>
            </div>
        </div>
    </div>
@endif

