@extends('layouts.app')

@section('title', 'हामीलाई सम्पर्क गर्नुहोस्')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
  <!-- Header Section -->
  <div class="bg-gradient-to-r from-red-500 to-pink-400 p-6">
    <h2 class="text-3xl font-bold text-white flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-300 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 01-8 0 4 4 0 018 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7m0 0H9m3 0h3" />
      </svg>
      हामीलाई सम्पर्क गर्नुहोस्
    </h2>
    <p class="text-red-100 mt-1">कुनै प्रश्न वा सुझाव छ? हामीलाई सन्देश पठाउनुहोस्!</p>
  </div>

  <div class="p-6 md:grid md:grid-cols-2 gap-8">
    <!-- Contact Info -->
    <div>
      <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">📍 ठेगाना</h3>
        <p class="text-gray-600">काठमाडौँ-३२, टिन्कुने, गैरीगाउँ</p>
      </div>

      <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">📞 सम्पर्क नम्बर</h3>
        <p class="text-gray-600">०१-४११२४४८</p>
        <p class="text-gray-600">९८४६२१६७११ (WhatsApp)</p>
      </div>
    </div>

    <!-- Contact Form -->
    <div>
      @if(session('success'))
      <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
      </div>
      @endif

      <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
          <label for="name_np" class="block text-sm font-medium text-gray-700 mb-1">नाम</label>
          <input type="text" name="name" id="name_np" value="{{ old('name') }}" required
            class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-red-200 focus:border-red-500 transition"
            placeholder="तपाईंको नाम">
          @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email_np" class="block text-sm font-medium text-gray-700 mb-1">इमेल</label>
          <input type="email" name="email" id="email_np" value="{{ old('email') }}" required
            class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-red-200 focus:border-red-500 transition"
            placeholder="तपाईंको इमेल">
          @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Message -->
        <div>
          <label for="message_np" class="block text-sm font-medium text-gray-700 mb-1">सन्देश</label>
          <textarea name="message" id="message_np" rows="5" required
            class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-red-200 focus:border-red-500 transition"
            placeholder="तपाईंको सन्देश...">{{ old('message') }}</textarea>
          @error('message')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition-transform transform hover:scale-105">
          सन्देश पठाउनुहोस्
        </button>
      </form>
    </div>
  </div>

  <!-- Google Map -->
  <div class="mt-8">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.558730040071!2d85.3461873150446!3d27.702321382793914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19a64b5f13e1%3A0x28b2d0eacda45b66!2sTinkune%2C%20Kathmandu%2044600!5e0!3m2!1sen!2snp!4v1658911239658!5m2!1sen!2snp"
      width="100%"
      height="450"
      class="border-0 rounded-lg"
      allowfullscreen=""
      loading="lazy">
    </iframe>
  </div>
</div>
@endsection
