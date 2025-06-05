@extends('layouts.app')

@section('title', 'चिञ्‍चा पिरो - नेपाली मसालाको अनुभूति')

@section('content')
<!-- Hero Section -->
<section class="relative h-screen bg-gray-900 flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('images/hero-bg.jpg') }}')">
  <div class="absolute inset-0 bg-black/50"></div>
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center text-white space-y-8">
      <h1 class="text-5xl md:text-8xl font-bold nepali-font animate-fadeIn mb-6">चिञ्‍चा पिरो</h1>
      <p class="text-3xl md:text-5xl nepali-font text-red-400 italic mb-8">"Welcome to Chincha Piro"</p>
      <div class="flex flex-col md:flex-row justify-center gap-6">
        <a href="#menu" class="bg-red-600 text-white px-12 py-4 rounded-full nepali-font text-xl hover:bg-red-700 transition-all">मेनु हेर्नुहोस्</a>
        <a href="{{ route('cart.index') }}" class="border-2 border-red-600 text-red-500 px-12 py-4 rounded-full nepali-font text-xl hover:bg-red-600 hover:text-white transition-all">कार्टमा थप्नुहोस्</a>
      </div>
    </div>
  </div>
</section>

<!-- Featured Section -->
<section id="menu" class="py-24 bg-gradient-to-b from-white to-gray-50">
  <div class="container mx-auto px-4">
    <div class="text-center mb-20">
      <h2 class="text-4xl md:text-5xl font-bold mb-6 nepali-font text-red-600">Featured डिशहरू</h2>
      <p class="text-xl text-gray-600 max-w-2xl mx-auto">हाम्रा सबैभन्दा लोकप्रिय पकवानहरूको स्वाद लिनुहोस्</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
      @forelse ($featuredMenus as $menu)
      <div class="group relative bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
        <div class="relative h-96 overflow-hidden">
          <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/placeholder.png') }}">

          <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
            <!-- नयाँ कार्ट बटन -->
            <form action="{{ route('cart.add', $menu->id) }}" method="POST">
              @csrf
              <input type="number" name="quantity" value="1" min="1" class="w-16 text-center bg-gray-800 text-white rounded mr-2">
              <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-full nepali-font text-md hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                कार्टमा थप्नुहोस्
              </button>
            </form>
          </div>
        </div>
        <div class="p-8 space-y-4">
          <h3 class="text-2xl font-bold nepali-font text-gray-800">{{ $menu->name }}</h3>
          <p class="text-gray-600 nepali-font line-clamp-2">{{ $menu->description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-red-500 text-lg nepali-font">★ {{ rand(4, 5) }}/५</span>
            <span class="text-xl font-bold text-red-600 nepali-font">रु {{ number_format($menu->price, 2) }}</span>
          </div>
        </div>
      </div>
      @empty
      <div class="col-span-full text-center py-12">
        <p class="text-gray-500 text-xl">कुनै featured डिश भेटिएन</p>
      </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Random Dishes Section -->
<section class="py-24 bg-gradient-to-b from-gray-100 to-white">
  <div class="container mx-auto px-4">
    <div class="flex flex-col items-center mb-20">
      <img src="{{ asset('images/cartoon-chef.png') }}" alt="Chincha Chef" class="w-48 md:w-64 mb-6">
      <h2 class="text-5xl md:text-6xl font-bold mb-20 nepali-font text-red-600 border-b-4 border-red-600 pb-4">हाम्रो विशेष पकवानहरू</h2>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
      @foreach($dishes as $dish)
      <div class="group relative bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="relative h-96 overflow-hidden">
          <img src="{{ $dish->image ? Storage::url($dish->image) : asset('images/placeholder.png') }}" alt="{{ $dish->name }}" class="w-full h-full object-cover">
          <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
            <!-- नयाँ कार्ट बटन -->
            <form action="{{ route('cart.add', $dish->id) }}" method="POST">
              @csrf
              <input type="number" name="quantity" value="1" min="1" class="w-16 text-center bg-gray-800 text-white rounded mr-2">
              <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-full nepali-font text-md hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                कार्टमा थप्नुहोस्
              </button>
            </form>
          </div>
        </div>
        <div class="p-8 space-y-4">
          <h3 class="text-3xl font-bold nepali-font text-gray-800">{{ $dish->name }}</h3>
          <p class="text-gray-600 nepali-font">{{ $dish->description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-red-500 text-xl nepali-font">★ {{ $dish->spice_level ?? 4 }}/५</span>
            <span class="text-2xl font-bold text-red-600 nepali-font">रु {{ number_format($dish->price, 2) }}</span>
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

  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>
@endpush
