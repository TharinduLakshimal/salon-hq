<!-- Left 2 Columns: Today's Appointments -->
<div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
    <div class="px-4 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-2">
        <h2 class="text-base font-bold text-slate-900">Today's Appointments</h2>
        <a href="{{ route('appointments.index') }}" wire:navigate class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 whitespace-nowrap">View All Bookings &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] text-left border-collapse">
            <thead>
                <tr
                    class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold uppercase tracking-wider text-slate-500 whitespace-nowrap">
                    <th class="px-4 sm:px-6 py-3">Client</th>
                    <th class="px-4 sm:px-6 py-3">Service</th>
                    <th class="px-4 sm:px-6 py-3">Stylist</th>
                    <th class="px-4 sm:px-6 py-3">Time</th>
                    <th class="px-4 sm:px-6 py-3">Price</th>
                    <th class="px-4 sm:px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                <tr class="hover:bg-slate-50/70">
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                        <div class="font-semibold text-slate-900">Nethmi Perera</div>
                        <div class="text-xs text-slate-500">077 412 8930</div>
                    </td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Bridal Hair & Makeup</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Kavindi S.</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-600 whitespace-nowrap">09:30 AM</td>
                    <td class="px-4 sm:px-6 py-3.5 font-semibold text-slate-900 whitespace-nowrap">Rs. 18,500</td>
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap"><span
                            class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">In
                            Progress</span></td>
                </tr>
                <tr class="hover:bg-slate-50/70">
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                        <div class="font-semibold text-slate-900">Dilshan Silva</div>
                        <div class="text-xs text-slate-500">071 845 2219</div>
                    </td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Haircut & Beard Styling</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Roshan M.</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-600 whitespace-nowrap">10:15 AM</td>
                    <td class="px-4 sm:px-6 py-3.5 font-semibold text-slate-900 whitespace-nowrap">Rs. 3,200</td>
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap"><span
                            class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">In
                            Progress</span></td>
                </tr>
                <tr class="hover:bg-slate-50/70">
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                        <div class="font-semibold text-slate-900">Sachini Fernando</div>
                        <div class="text-xs text-slate-500">076 992 1044</div>
                    </td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Keratin Hair Treatment</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Amaya J.</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-600 whitespace-nowrap">11:00 AM</td>
                    <td class="px-4 sm:px-6 py-3.5 font-semibold text-slate-900 whitespace-nowrap">Rs. 14,000</td>
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap"><span
                            class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Confirmed</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/70">
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                        <div class="font-semibold text-slate-900">Tharindu Jayasinghe</div>
                        <div class="text-xs text-slate-500">077 203 5511</div>
                    </td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">HydraFacial & Skin Care</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Nadeesha K.</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-600 whitespace-nowrap">01:30 PM</td>
                    <td class="px-4 sm:px-6 py-3.5 font-semibold text-slate-900 whitespace-nowrap">Rs. 7,500</td>
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap"><span
                            class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Confirmed</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/70">
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                        <div class="font-semibold text-slate-900">Hashini Wijeratne</div>
                        <div class="text-xs text-slate-500">070 331 6780</div>
                    </td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Gel Nail Extensions</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-700 whitespace-nowrap">Kavindi S.</td>
                    <td class="px-4 sm:px-6 py-3.5 text-slate-600 whitespace-nowrap">03:00 PM</td>
                    <td class="px-4 sm:px-6 py-3.5 font-semibold text-slate-900 whitespace-nowrap">Rs. 5,300</td>
                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap"><span
                            class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
