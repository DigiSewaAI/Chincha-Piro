@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[70vh] bg-gradient-to-br from-green-50 to-green-100 px-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-lg text-center">
        <div class="text-green-500 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 0110 0 5 5 0 01-10 0z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">अर्डर सफल भयो!</h1>
        <p class="text-gray-600 mb-4">तपाईंको अर्डर सफलतापूर्वक प्राप्त गरियो। हामी तपाईंलाई चाँडै सम्पर्क गर्नेछौं।</p>

        @if(session('order_id'))
            <a href="{{ route('orders.track', session('order_id')) }}"
               class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-lg transition duration-300">
                📦 ट्र्याक गर्नुहोस्
            </a>
        @else
            <a href="{{ route('orders.index') }}"
               class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-5 rounded-lg transition duration-300">
                ⬅️ अर्डर सूचीमा फर्कनुहोस्
            </a>
        @endif
    </div>
</div>
@endsection
