@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4 nepali-font">सार्वजनिक अर्डरहरू</h1>

        @if(count($orders) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <!-- Order listing -->
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400 nepali-font">कुनै सार्वजनिक अर्डरहरू उपलब्ध छैनन्।</p>
        @endif
    </div>
@endsection
