@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
  <!-- Header with Gradient and Icon -->
  <div class="bg-gradient-to-r from-purple-600 to-indigo-500 p-6">
    <h2 class="text-3xl font-bold text-white flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-yellow-300 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 01-8 0 4 4 0 018 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7m0 0H9m3 0h3" />
      </svg>
      Contact Us
    </h2>
    <p class="text-purple-200 mt-1">
      Have any questions or suggestions? Fill out the form below and we'll get back to you ASAP.
    </p>
  </div>

  <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Section -->
    <div>
      @if(session('success'))
      <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
      </div>
      @endif

      <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input type="text" name="name" id="name" value="{{ old('name') }}" required
            class="block w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-purple-200 focus:border-purple-500 transition"
            placeholder="Your name">
          @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" name="email" id="email" value="{{ old('email') }}" required
            class="block w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-purple-200 focus:border-purple-500 transition"
            placeholder="you@example.com">
          @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Message -->
        <div>
          <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
          <textarea name="message" id="message" rows="5" required
            class="block w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-purple-200 focus:border-purple-500 transition"
            placeholder="Your message...">{{ old('message') }}</textarea>
          @error('message')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-lg transition-transform transform hover:scale-105">
          Send Message
        </button>
      </form>
    </div>

    <!-- Google Map Embed -->
    <div class="hidden lg:block">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.123456789012!2d85.3230!3d27.7172!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb190f3e7029f7%3A0x8a0a1f0a0b0c0d0e!2sKathmandu%2C%20Nepal!5e0!3m2!1sen!2sus!4vXXXXXXXXXXXX"
        width="100%" height="100%" class="rounded-lg shadow-lg" style="border:0;" allowfullscreen="" loading="lazy">
      </iframe>
    </div>
  </div>
</div>
@endsection
