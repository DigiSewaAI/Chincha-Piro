@extends('layouts.app')

@section('title', 'रिजर्भेसन')

@section('content')
<div class="container mx-auto p-4 nepali-font">
    <!-- रिजर्भेसन फारम -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mb-8">
        <h1 class="text-3xl font-bold mb-6 text-red-600 dark:text-red-400">रिजर्भेसन फारम</h1>

        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">मिति र समय:</label>
                <input type="datetime-local" name="reservation_time"
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded"
                    required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">मानिस संख्या:</label>
                <input type="number" name="guests" min="1" max="20"
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded"
                    value="2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">सम्पर्क नम्बर:</label>
                <input type="tel" name="contact_number" pattern="98\d{8}"
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded"
                    placeholder="98XXXXXXXX" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 mb-2">विशेष अनुरोध:</label>
                <textarea name="special_request" rows="3"
                    class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded"></textarea>
            </div>

            <button type="submit"
                class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition-colors">
                रिजर्भ गर्नुहोस्
            </button>
        </form>
    </div>

    <!-- तपाईंका रिजर्भेसनहरू -->
    @if($reservations && $reservations->isNotEmpty())
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">तपाईंका रिजर्भेसनहरू</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">मिति</th>
                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">मानिस</th>
                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-600">अवस्था</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                            {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('Y-m-d H:i') }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600 text-center">
                            {{ $reservation->guests }}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-600">
                            @switch($reservation->status)
                                @case('confirmed')
                                    <span class="px-2 py-1 rounded bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-100">
                                        पुष्टि गरिएको
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
