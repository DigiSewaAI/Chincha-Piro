@extends('layouts.app')

@section('title', 'चिञ्‍चा पिरो - नेपाली मसालाको अनुभूति')

@section('content')
<!-- Premium Hero Section -->
<section class="relative h-screen bg-gray-900 flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('images/hero-bg.jpg') }}')">
  <div class="absolute inset-0 bg-black/50"></div>
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center text-white space-y-8">
      <h1 class="text-5xl md:text-8xl font-bold nepali-font animate-fadeIn mb-6">
        चिञ्‍चा पिरो
      </h1>
      <p class="text-3xl md:text-5xl nepali-font text-red-400 italic mb-8">
        "Welcome to Chincha Piro"
      </p>
      <div class="flex flex-col md:flex-row justify-center gap-6">
        <a href="#menu" class="bg-red-600 text-white px-12 py-4 rounded-full nepali-font text-xl hover:bg-red-700 transition-all">
          मेनु हेर्नुहोस्
        </a>
        <a href="{{ route('order.index') }}" class="border-2 border-red-600 text-red-500 px-12 py-4 rounded-full nepali-font text-xl hover:bg-red-600 hover:text-white transition-all">
          अहिले अर्डर गर्नुहोस्
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Professional Dishes Section -->
<section class="py-24 bg-gradient-to-b from-gray-100 to-white">
  <div class="container mx-auto px-4">
    <h2 class="text-5xl md:text-6xl font-bold text-center mb-20 nepali-font text-red-600
               border-b-4 border-red-600 pb-4 inline-block">
      हाम्रो विशेष पकवानहरू
    </h2>

    <!-- Dishes Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
      @foreach($dishes as $dish)
      <div class="group relative bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- Dish Image with Order Button -->
        <div class="relative h-96 overflow-hidden">
          <img src="{{ asset('images/dishes/'.strtolower($dish->image)) }}"
               alt="{{ $dish->name }}"
               class="w-full h-full object-cover">

          <!-- Always Visible Order Button -->
          <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80">
            <button class="w-full bg-red-600 text-white px-4 py-2 rounded-full nepali-font
                          text-md hover:bg-red-700 transition-all flex items-center justify-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
              </svg>
              अहिले अर्डर गर्नुहोस्
            </button>
          </div>
        </div>

        <!-- Dish Details -->
        <div class="p-8 space-y-4">
          <h3 class="text-3xl font-bold nepali-font text-gray-800">
            {{ $dish->name }}
          </h3>
          <p class="text-gray-600 nepali-font">
            {{ $dish->description }}
          </p>
          <div class="flex justify-between items-center">
            <span class="text-red-500 text-xl nepali-font">
              ★ {{ $dish->spice_level }}/५
            </span>
            <span class="text-2xl font-bold text-red-600 nepali-font">
              रु {{ number_format($dish->price, 2) }}
            </span>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

@endsection

@push('styles')
<style>
  .nepali-font {
    font-family: 'Noto Sans Devanagari', 'Preeti', sans-serif;
    letter-spacing: 0.5px;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .animate-fadeIn {
    animation: fadeIn 1s ease-out;
  }
</style>
@endpush
