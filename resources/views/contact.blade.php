@extends('layouts.app')

@section('title', 'सम्पर्क - Chincha Piro')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-500 to-red-400 p-6 text-white">
        <h1 class="text-3xl font-bold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-yellow-300 animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v10a2 2 0 00-2 2z" />
            </svg>
            हाम्रो सँग सम्पर्क गर्नुहोस्
        </h1>
        <p class="text-orange-100 mt-1">कुनै प्रश्न, सुझाव वा सहयोगको लागि सन्देश पठाउनुहोस्</p>
    </div>

    <!-- Main Content -->
    <div class="p-6 md:grid md:grid-cols-2 gap-8">
        <!-- Contact Info -->
        <div class="space-y-6">
            <!-- Address -->
            <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-orange-500">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">📍 हाम्रो ठेगाना</h3>
                <p class="nepali-font">काठमाडौँ-३२, तिनकुने<br>गैरिगाउ, चिञ्‍चा पिरो भवन</p>
            </div>

            <!-- Phone -->
            <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-orange-500">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">📞 सम्पर्क नम्बर</h3>
                <p>०१-४११२४४८</p>
                <p>९८४६२१६७११ (WhatsApp)</p>
            </div>

            <!-- Social Media -->
            <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-orange-500">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">🌐 सामाजिक सञ्जाल</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-blue-600 hover:text-blue-800">Facebook</a>
                    <a href="#" class="text-pink-600 hover:text-pink-800">Instagram</a>
                    <a href="#" class="text-blue-400 hover:text-blue-600">Twitter</a>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="mt-6 md:mt-0">
            <!-- Success/Error Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Contact Form -->
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name_np" class="block text-sm font-medium text-gray-700 mb-1">तपाईंको नाम</label>
                    <input type="text" name="name" id="name_np" value="{{ old('name') }}" required
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-orange-200 focus:border-orange-500 transition duration-300"
                        placeholder="तपाईंको नाम">
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email_np" class="block text-sm font-medium text-gray-700 mb-1">ईमेल</label>
                    <input type="email" name="email" id="email_np" value="{{ old('email') }}" required
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-orange-200 focus:border-orange-500 transition duration-300"
                        placeholder="तपाईंको इमेल">
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label for="message_np" class="block text-sm font-medium text-gray-700 mb-1">सन्देश</label>
                    <textarea name="message" id="message_np" rows="5" required
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:ring-orange-200 focus:border-orange-500 transition duration-300"
                        placeholder="तपाईंको सन्देश...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- reCAPTCHA -->
                <div class="mt-4">
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                    सन्देश पठाउनुहोस्
                </button>
            </form>
        </div>
    </div>

    <!-- Google Map -->
    <div class="mt-8 mb-6">
        <iframe
            src="https://www.google.com/maps/embed?pb= !1m18!1m12!1m3!1d3532.356907654372!2d85.30842797484335!3d27.706480076132074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19a3a388e1b1:0x9cd460e010b56b3f!2sChincha%20Piro!5e0!3m2!1sen!2snp!4v1700000000000!5m2!1sen!2snp"
            width="100%"
            height="450"
            class="border-0 rounded-lg"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toast Notification
    @if(session('success'))
        alert("{{ session('success') }}");
    @endif
</script>

<!-- reCAPTCHA JS -->
{!! NoCaptcha::renderJs() !!}
@endsection
