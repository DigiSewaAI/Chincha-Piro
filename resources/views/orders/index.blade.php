@extends('layouts.app')
@section('title', 'आदेश पृष्ठ')
@section('content')
    <div class="container mx-auto py-12">
        <!-- Order Form Section -->
        <h1 class="text-4xl nepali-font text-center mb-8">अर्डर फारम</h1>
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            @foreach($dishes as $dish)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="{{ asset($dish->image) }}" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-2xl nepali-font">{{ $dish->name }}</h3>
                    <form action="{{ route('order.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="dish_id" value="{{ $dish->id }}">
                        <div class="my-4">
                            <label class="nepali-font">मात्रा:</label>
                            <input type="number" name="quantity" min="1" max="10"
                                   class="w-full border rounded p-2" required>
                        </div>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-full nepali-font">
                            अर्डर गर्नुहोस्
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order List Section -->
        <section class="py-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg">
            <div class="container mx-auto px-4">
                <!-- Section Header -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0 nepali-font">
                        @auth
                            तपाईंका अर्डरहरू
                        @else
                            सबै अर्डरहरू
                        @endauth
                    </h1>
                    <!-- Status Filter -->
                    <div class="flex items-center nepali-font">
                        <label for="status-filter" class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">स्थिति:</label>
                        <select id="status-filter" class="rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring focus:ring-red-200 dark:bg-gray-700 dark:border-gray-600">
                            <option value="all">सबै</option>
                            <option value="pending">पुष्टि हुन बाँकी</option>
                            <option value="confirmed">पुष्टि भएको</option>
                            <option value="processing">तयारीमा</option>
                            <option value="completed">पुरा भएको</option>
                            <option value="cancelled">रद्द भएको</option>
                        </select>
                    </div>
                </div>

                <!-- Empty Orders -->
                @if($orders->isEmpty())
                <div class="text-center bg-white dark:bg-gray-900 rounded-lg shadow-lg p-12">
                    <div class="mx-auto w-20 h-20 text-gray-300 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-3 0h.01" />
                        </svg>
                    </div>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-6 nepali-font">अझसम्म कुनै अर्डर गरिएको छैन।</p>
                    <a href="{{ route('menu.index') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-full hover:bg-red-700 transition nepali-font">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                        </svg>
                        मेनु हेर्नुहोस्
                    </a>
                </div>
                @else
                <!-- Orders Table -->
                <div class="bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
                    <!-- Table Header -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-wrap justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white nepali-font">अर्डर सूची</h2>
                        <div class="flex items-center mt-3 md:mt-0">
                            <label for="search-order" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300 nepali-font">खोज्नुहोस्:</label>
                            <input type="text" id="search-order" placeholder="अर्डर ID वा पकवानको नाम"
                                   class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm focus:border-red-500 focus:ring focus:ring-red-200 nepali-font">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-red-600 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">पकवान</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">मात्रा</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">मूल्य</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">स्थिति</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">मिति</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">क्रिया</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset($order->dish->image_url) }}" alt="{{ $order->dish->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900 dark:text-white nepali-font">{{ $order->dish->name }}</div>
                                                <div class="text-sm text-gray-500 nepali-font">{{ $order->dish->category }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 nepali-font">{{ $order->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-500 font-semibold nepali-font">रु {{ number_format($order->total_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 rounded-full font-semibold
                                          {{ \App\Helpers\StatusHelper::getStatusClass($order->status) }}">
                                          {{ \App\Helpers\StatusHelper::getStatusText($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 nepali-font">{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium flex justify-end space-x-3">
                                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition nepali-font">
                                            विवरण
                                        </a>
                                        @if(in_array($order->status, ['pending', 'confirmed']))
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition nepali-font"
                                                onclick="return confirm('के तपाईं यो अर्डर हटाउन निश्चित हुनुहुन्छ?')">
                                                हटाउनुहोस्
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $orders->links('vendor.pagination.tailwind') }}
                </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('styles')
<style>
    .nepali-font {
        font-family: 'Preeti', 'Noto Sans Devanagari', sans-serif;
    }
    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #2d3748;
        }
        .dark\:text-white {
            color: #fff;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Status Filter with Nepali translations
    const statusTranslations = {
        'pending': 'पुष्टि हुन बाँकी',
        'confirmed': 'पुष्टि भएको',
        'processing': 'तयारीमा',
        'completed': 'पुरा भएको',
        'cancelled': 'रद्द भएको'
    };

    document.getElementById('status-filter').addEventListener('change', function() {
        const selectedStatus = this.value;
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const statusCell = row.querySelector('.rounded-full');
            const statusText = statusCell.textContent.trim();
            if(selectedStatus === 'all' || statusText === statusTranslations[selectedStatus]) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Search Functionality
    document.getElementById('search-order').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const dishName = row.querySelector('td:nth-child(2) .font-medium').textContent.toLowerCase();
            const orderId = row.querySelector('td:first-child').textContent.toLowerCase();
            if(dishName.includes(searchTerm) || orderId.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
