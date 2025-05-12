<!-- dishes/index.blade.php -->
@extends('layouts.app')

@section('title', 'हाम्रो मेनु')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-8 nepali-font">हाम्रो विशेष पकवानहरू</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($dishes as $dish)
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md transition-transform hover:scale-105">
                <!-- Image Section -->
                <img src="{{ asset('images/dishes/' . strtolower($dish->image)) }}"
                     alt="{{ $dish->name }}"
                     class="w-full h-64 object-cover">

                <!-- Content Section -->
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-2 nepali-font">{{ $dish->name }}</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">{{ Str::limit($dish->description, 100) }}</p>
                    <div class="flex items-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span class="text-red-600 font-semibold">★ {{ $dish->spice_level }}/५</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-green-600">रु {{ number_format($dish->price, 2) }}</span>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                            अहिले अर्डर गर्नुहोस्
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
