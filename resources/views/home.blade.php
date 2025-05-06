@extends('layouts.app')

@section('title', 'मुख्य पृष्ठ')

@section('content')
  <!-- हीरो सेक्सन -->
  <section
    class="relative overflow-hidden h-[80vh] bg-cover bg-center"
    style="background-image: url('{{ asset('images/hero-dish.jpg') }}')"
  >
    <div class="absolute inset-0 bg-black/30"></div>
    <div class="container mx-auto h-full flex items-center justify-center px-4">
      <div class="text-center text-white z-10">
        <h1 class="text-4xl md:text-6xl font-bold mb-6 nepali-font animate-fadeInUp">
          चिञ्‍चा पिरोमा स्वागत छ!
        </h1>
        <p class="text-xl md:text-2xl mb-8 nepali-font">
          "मसालाको राजा, स्वादको महाराज"
        </p>
        <div class="flex justify-center space-x-4">
          <a href="#menu"
             class="bg-white text-red-600 px-8 py-3 rounded-full font-semibold hover:bg-red-50 transition-all">
            मेनु हेर्नुहोस् 🌶️
          </a>
          <a href="{{ route('contact') }}"
             class="border-2 border-white text-white px-8 py-3 rounded-full hover:bg-white hover:text-red-600 transition-all">
            अर्डर गर्नुहोस्
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- विशेष पकवान सेक्सन -->
  <section id="menu" class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 nepali-font text-red-600">
        हाम्रो विशेष पकवानहरू
      </h2>

      <div class="grid md:grid-cols-3 gap-8">
        @foreach($signatureDishes as $dish)
          <div class="group bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300">
            <div class="relative h-60">
              <img
                src="{{ asset($dish['image']) }}"
                alt="{{ $dish['name'] }}"
                class="w-full h-full object-cover transform group-hover:scale-105 transition-all duration-300"
              >
              <div class="absolute inset-0 bg-gradient-to-t from-black/60"></div>
              <div class="absolute bottom-0 left-0 right-0 p-4">
                <h3 class="text-2xl font-bold text-white nepali-font mb-2">
                  {{ $dish['name'] }}
                </h3>
                <div class="flex items-center space-x-2">
                  @for ($i = 0; $i < $dish['spice_level']; $i++)
                    <span class="text-red-400">🌶️</span>
                  @endfor
                </div>
              </div>
            </div>
            <div class="p-6">
              @if($dish['desc'])
                <p class="text-gray-600 dark:text-gray-300 mb-4 nepali-font">
                  {{ $dish['desc'] }}
                </p>
              @endif
              @if($dish['price'])
                <div class="flex justify-between items-center">
                  <span class="text-2xl font-bold text-red-600 nepali-font">
                    रु {{ $dish['price'] }}
                  </span>
                  <button class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition-colors nepali-font">
                    अर्डर गर्नुहोस्
                  </button>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection

@push('styles')
<style>
  .nepali-font { font-family: 'Preeti', 'Mangal', sans-serif; }

  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0);   }
  }
  .animate-fadeInUp { animation: fadeInUp 1s ease-out; }

  .shadow-hover { transition: box-shadow 0.3s ease; }
  .shadow-hover:hover { box-shadow: 0 10px 30px rgba(220, 38, 38, 0.2); }
</style>
@endpush


