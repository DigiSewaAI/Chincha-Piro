@extends('layouts.app')

@section('title', 'मुख्य पृष्ठ')

@section('content')
<!-- हीरो सेक्सन -->
<section class="relative overflow-hidden h-[80vh] bg-cover bg-center" style="background-image: url('{{ asset('images/hero-dish.jpg') }}')">
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
        <a href="#menu" class="bg-white text-red-600 px-8 py-3 rounded-full font-semibold hover:bg-red-50 transition-all">
          मेनु हेर्नुहोस् 🌶️
        </a>
        <a href="{{ route('contact') }}" class="border-2 border-white text-white px-8 py-3 rounded-full hover:bg-white hover:text-red-600 transition-all">
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
      @foreach([
        [
          'name' => 'चिप्ले चिञ्‍चा मासु',
          'image' => 'images/dishes/chiplet-chincha.jpg',
          'desc' => 'ताजा इमलीको चटनी र १५ प्रकारका मसालाको मिश्रण',
          'price' => '६५०',
          'spice' => 3
        ],
        [
          'name' => 'पिरो चिल्ली चिकन',
          'image' => 'images/dishes/chilli-chicken.jpg',
          'desc' => 'भुटाने सुख्खा मिर्चको अग्नी स्वाद',
          'price' => '५५०',
          'spice' => 4
        ],
        [
          'name' => 'मिक्स्ड टार्कारी फ्राई',
          'image' => 'images/dishes/tarkari.jpg',
          'desc' => 'हरियो मसाला ग्रेवीमा पकाइएको ताजा तरकारी',
          'price' => '४५०',
          'spice' => 2
        ]
      ] as $dish)
      <div class="group bg-white dark:bg-gray-700 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
        <div class="relative h-60">
          <img src="{{ asset($dish['image']) }}" alt="{{ $dish['name'] }}"
               class="w-full h-full object-cover transform group-hover:scale-105 transition-all duration-300">
          <div class="absolute inset-0 bg-gradient-to-t from-black/60"></div>
          <div class="absolute bottom-0 left-0 right-0 p-4">
            <h3 class="text-2xl font-bold text-white nepali-font mb-2">
              {{ $dish['name'] }}
            </h3>
            <div class="flex items-center space-x-2">
              @for($i=0; $i<$dish['spice']; $i++)
              <span class="text-red-400">🌶️</span>
              @endfor
            </div>
          </div>
        </div>

        <div class="p-6">
          <p class="text-gray-600 dark:text-gray-300 mb-4 nepali-font">
            {{ $dish['desc'] }}
          </p>
          <div class="flex justify-between items-center">
            <span class="text-2xl font-bold text-red-600 nepali-font">
              रु {{ $dish['price'] }}
            </span>
            <button class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition-colors nepali-font">
              अर्डर गर्नुहोस्
            </button>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- सम्पर्क सेक्सन -->
<section class="bg-gray-800 text-white py-12">
  <div class="container mx-auto px-4">
    <div class="grid md:grid-cols-3 gap-8 items-center">
      <div class="text-center md:text-left">
        <h3 class="text-2xl font-bold mb-4 nepali-font">हाम्रो ठेगाना</h3>
        <p class="text-gray-300 nepali-font">
          काठमाडौं-३२, टिन्कुने<br>
          गैरिगाउ, चिञ्‍चा पिरो भवन
        </p>
      </div>

      <div class="text-center">
        <h3 class="text-2xl font-bold mb-4 nepali-font">सम्पर्क</h3>
        <p class="text-gray-300 nepali-font">
          📞 ०१-४११२४४८<br>
          📱 ९८४६२१६७११
        </p>
      </div>

      <div class="text-center md:text-right">
        <h3 class="text-2xl font-bold mb-4 nepali-font">सामाजिक सञ्जाल</h3>
        <div class="flex justify-center md:justify-end space-x-4">
          <a href="#" class="text-white hover:text-red-500">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3।470h-2।796v8।385C19।612 23।027 24 18।062 24 12।073z"/>
            </svg>
          </a>
          <a href="#" class="text-zero hover-text-red-500">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3।204 0 3।584।012 4۔85۔07 3۔252۔148 4۔771 1।691 4۔919 4۔919۔058 1۔265۔069 1۔645۔069 4।849 0 3।205۔012 3۔584۔069 4۔849۔149 3۔225۔

              ... <!-- Rest of SVG truncated for brevity -->
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  .nepali-font {
    font-family: 'Preeti', 'Mangal', sans-serif;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fadeInUp {
    animation: fadeInUp 1s ease-out;
  }

  .shadow-hover {
    transition: box-shadow 0.3s ease;
  }

  .shadow-hover:hover {
    box-shadow: 0 10px 30px rgba(220, 38, 38, 0.2);
  }
</style>
@endpush

