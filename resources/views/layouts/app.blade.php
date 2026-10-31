<!DOCTYPE html>
<html lang="en">
@include('includes.head')
<body x-data="{ sidebarOpen: false }" class="bg-slate-50 text-slate-900 min-h-screen flex antialiased">

    {{-- Reusable Sidebar Component --}}
    <x-sidebar />

    <!-- Main Content Wrapper -->
    <div class="flex-1 md:ml-64 flex flex-col min-w-0">
        @include('includes.header')

        <!-- Page Body -->
        <main class="p-4 sm:p-6 lg:p-8">
            @include('includes.alerts')
            @yield('content')
        </main>

        @include('includes.footer')
    </div>

    @yield('modals')
    @livewireScripts
    @yield('scripts')
</body>
</html>
