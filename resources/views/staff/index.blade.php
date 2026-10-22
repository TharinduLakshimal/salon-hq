@extends('layouts.app')

@section('title', 'Staff & Stylists')

@section('header-left')
    <div>
        <h1 class="text-xl font-bold text-slate-900">Staff & Stylists (Unisex Salon)</h1>
        <p class="text-xs text-slate-500 mt-0.5">Real-time Livewire & Alpine.js management connected to MySQL.</p>
    </div>
@endsection

@section('content')
    <livewire:staff-directory />
@endsection
