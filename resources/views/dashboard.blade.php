@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header-left')
    <div>
        <h1 class="text-xl font-bold text-slate-900">Dashboard Overview</h1>
        <p class="text-xs text-slate-500 mt-0.5">Welcome back, {{ auth()->user()->name ?? 'Admin' }} — Here is what's
            happening at your salon today.</p>
    </div>
@endsection

@section('header-right')
    <div class="relative hidden sm:block">
        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" placeholder="Search bookings, clients..."
            class="pl-9 pr-4 py-2 w-64 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-indigo-600 focus:ring-3 focus:ring-indigo-500/15 focus:outline-none transition">
    </div>

    <a href="#"
        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>New Appointment</span>
    </a>
@endsection

@section('content')
    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Bookings</div>
                <div class="text-3xl font-bold text-slate-900 my-1.5">18</div>
                <div class="text-xs font-medium text-emerald-600">+14% vs yesterday</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Revenue</div>
                <div class="text-3xl font-bold text-slate-900 my-1.5">Rs. 48,500</div>
                <div class="text-xs font-medium text-emerald-600">+8.2% vs last week</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active Clients</div>
                <div class="text-3xl font-bold text-slate-900 my-1.5">342</div>
                <div class="text-xs font-medium text-emerald-600">+24 new this month</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start justify-between shadow-2xs">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">Staff on Duty</div>
                <div class="text-3xl font-bold text-slate-900 my-1.5">{{ $staffMembers->count() }}</div>
                <div class="text-xs font-medium text-slate-500">Unisex salon team</div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="6" cy="6" r="3"></circle>
                    <circle cx="6" cy="18" r="3"></circle>
                    <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                    <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Grid: Appointments Table + Side Widgets -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Left 2 Columns: Today's Appointments -->
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Today's Appointments</h2>
                <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View All Bookings &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                            <th class="px-6 py-3">Client</th>
                            <th class="px-6 py-3">Service</th>
                            <th class="px-6 py-3">Stylist</th>
                            <th class="px-6 py-3">Time</th>
                            <th class="px-6 py-3">Price</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5">
                                <div class="font-semibold text-slate-900">Nethmi Perera</div>
                                <div class="text-xs text-slate-500">077 412 8930</div>
                            </td>
                            <td class="px-6 py-3.5 text-slate-700">Bridal Hair & Makeup</td>
                            <td class="px-6 py-3.5 text-slate-700">Kavindi S.</td>
                            <td class="px-6 py-3.5 text-slate-600">09:30 AM</td>
                            <td class="px-6 py-3.5 font-semibold text-slate-900">Rs. 18,500</td>
                            <td class="px-6 py-3.5"><span
                                    class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">In
                                    Progress</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5">
                                <div class="font-semibold text-slate-900">Dilshan Silva</div>
                                <div class="text-xs text-slate-500">071 845 2219</div>
                            </td>
                            <td class="px-6 py-3.5 text-slate-700">Haircut & Beard Styling</td>
                            <td class="px-6 py-3.5 text-slate-700">Roshan M.</td>
                            <td class="px-6 py-3.5 text-slate-600">10:15 AM</td>
                            <td class="px-6 py-3.5 font-semibold text-slate-900">Rs. 3,200</td>
                            <td class="px-6 py-3.5"><span
                                    class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">In
                                    Progress</span></td>
                        </tr>
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5">
                                <div class="font-semibold text-slate-900">Sachini Fernando</div>
                                <div class="text-xs text-slate-500">076 992 1044</div>
                            </td>
                            <td class="px-6 py-3.5 text-slate-700">Keratin Hair Treatment</td>
                            <td class="px-6 py-3.5 text-slate-700">Amaya J.</td>
                            <td class="px-6 py-3.5 text-slate-600">11:00 AM</td>
                            <td class="px-6 py-3.5 font-semibold text-slate-900">Rs. 14,000</td>
                            <td class="px-6 py-3.5"><span
                                    class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Confirmed</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5">
                                <div class="font-semibold text-slate-900">Tharindu Jayasinghe</div>
                                <div class="text-xs text-slate-500">077 203 5511</div>
                            </td>
                            <td class="px-6 py-3.5 text-slate-700">HydraFacial & Skin Care</td>
                            <td class="px-6 py-3.5 text-slate-700">Nadeesha K.</td>
                            <td class="px-6 py-3.5 text-slate-600">01:30 PM</td>
                            <td class="px-6 py-3.5 font-semibold text-slate-900">Rs. 7,500</td>
                            <td class="px-6 py-3.5"><span
                                    class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Confirmed</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5">
                                <div class="font-semibold text-slate-900">Hashini Wijeratne</div>
                                <div class="text-xs text-slate-500">070 331 6780</div>
                            </td>
                            <td class="px-6 py-3.5 text-slate-700">Gel Nail Extensions</td>
                            <td class="px-6 py-3.5 text-slate-700">Kavindi S.</td>
                            <td class="px-6 py-3.5 text-slate-600">03:00 PM</td>
                            <td class="px-6 py-3.5 font-semibold text-slate-900">Rs. 5,300</td>
                            <td class="px-6 py-3.5"><span
                                    class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Livewire Stylist Status -->
        <div class="flex flex-col gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="text-base font-bold text-slate-900">Quick Actions</h2>
                </div>
                <div class="p-5 grid grid-cols-2 gap-3.5">
                    <a href="#"
                        class="border border-slate-200 rounded-xl p-4 flex flex-col items-center text-center gap-2 text-xs font-semibold text-slate-800 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="12" y1="10" x2="12" y2="16"></line>
                            <line x1="9" y1="13" x2="15" y2="13"></line>
                        </svg>
                        <span>Add Booking</span>
                    </a>
                    <a href="{{ route('staff.index') }}" wire:navigate
                        class="border border-slate-200 rounded-xl p-4 flex flex-col items-center text-center gap-2 text-xs font-semibold text-slate-800 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        <span>Manage Staff</span>
                    </a>
                    <a href="#"
                        class="border border-slate-200 rounded-xl p-4 flex flex-col items-center text-center gap-2 text-xs font-semibold text-slate-800 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <span>Add Service</span>
                    </a>
                    <a href="#"
                        class="border border-slate-200 rounded-xl p-4 flex flex-col items-center text-center gap-2 text-xs font-semibold text-slate-800 hover:border-indigo-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                        <span>POS Billing</span>
                    </a>
                </div>
            </div>

            <!-- Livewire Stylist Status Component -->
            <livewire:stylist-status-widget />
        </div>
    </div>
@endsection