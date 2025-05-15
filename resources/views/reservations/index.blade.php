@extends('layouts.app')

@section('title', 'रिजर्भेसन')

@section('content')
<div class="container mx-auto p-4 nepali-font">
    <!-- ✅ सफलता सन्देश -->
    @if(session('success'))
    <div id="successMessage" class="bg-green-100 text-green-800 border border-green-400 px-4 py-3 rounded mb-4 transition-opacity duration-1000"
        role="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- ❌ त्रुटि सन्देशहरू -->
    @if($errors->any())
    <div class="bg-red-100 text-red-800 border border-red-400 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- 📝 रिजर्भेसन फारम -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mb-8">
        <h1 class="text-3xl font-bold mb-6 text-red-600 dark:text-red-400">रिजर्भेसन फारम</h1>

        <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <!-- मिति र समय -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">मिति र समय:</label>
                <input type="datetime-local" name="reservation_time"
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded"
                    value="{{ old('reservation_time') }}" required>
            </div>

            <!-- व्यक्ति संख्या -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">व्यक्ति संख्या (1-100):</label>
                <input type="number" name="guests" min="1" max="100"
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded"
                    value="{{ old('guests') }}" required>
            </div>

            <!-- सम्पर्क नम्बर -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">सम्पर्क नम्बर:</label>
                <input type="tel" name="contact_number" pattern="98\d{8}"
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded"
                    placeholder="98XXXXXXXX" value="{{ old('contact_number') }}" required>
            </div>

            <!-- विशेष अनुरोध -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">विशेष अनुरोध:</label>
                <textarea name="special_request" rows="3"
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded">{{ old('special_request') }}</textarea>
            </div>

            <!-- बटन -->
            <button type="submit"
                class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition-colors">
                रिजर्भ गर्नुहोस्
            </button>
        </form>
    </div>

    <!-- ✅ फारम रिसेट र सन्देश fade-out स्क्रिप्ट -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('reservationForm');
            const successBox = document.getElementById('successMessage');

            if (form) form.reset();

            if (successBox) {
                setTimeout(() => {
                    successBox.classList.add('opacity-0');
                    setTimeout(() => successBox.remove(), 1000);
                }, 5000);
            }

            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });
    </script>
    @endif

    <!-- 📋 रिजर्भेसन सूची -->
    @if(isset($reservations) && $reservations->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">तपाईंका रिजर्भेसनहरू</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">मिति</th>
                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">व्यक्ति</th>
                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">अवस्था</th>
                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">कार्य</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <!-- मिति -->
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                            {{ \Carbon\Carbon::parse($reservation->reservation_time)->locale('ne')->isoFormat('YYYY-MM-DD HH:mm') }}
                        </td>

                        <!-- मानिस संख्या -->
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600 text-center">
                            {{ $reservation->guests }}
                        </td>

                        <!-- अवस्था -->
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                            @switch($reservation->status)
                                @case('confirmed')
                                    <span class="px-2 py-1 rounded bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-100">
                                        पुष्टि भएको
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2 py-1 rounded bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-100">
                                        रद्द भएको
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 py-1 rounded bg-yellow-200 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100">
                                        पुष्टि हुन बाँकी
                                    </span>
                            @endswitch
                        </td>

                        <!-- कार्य -->
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600 text-center">
                            <a href="{{ route('reservations.edit', $reservation->id) }}"
                                class="text-blue-600 hover:underline mr-2">सम्पादन</a>
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('के तपाई यो रिजर्भेसन मेटाउन चाहानुहुन्छ?')">मेटाउनुहोस्</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
        <p class="text-gray-700 dark:text-gray-300">अहिलेसम्म कुनै रिजर्भेसन भएको छैन।</p>
    </div>
    @endif
</div>
@endsection
