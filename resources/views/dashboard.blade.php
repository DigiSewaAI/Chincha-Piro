@extends('layouts.app', ['title' => 'ड्यासबोर्ड'])

@section('content')
<div class="space-y-8">
    <!-- हेडर सेक्सन -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">प्रशासनिक नियन्त्रण केन्द्र</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">चिन्चा पिरोको प्रदर्शन विश्लेषण</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                नयाँ अर्डर
            </button>
        </div>
    </div>

    <!-- स्ट्याट्स ग्रिड -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- आजको आरक्षण कार्ड -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">आजको आरक्षण</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $todayReservations }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-full">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex justify-between items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400">गत हप्ताको तुलनामा 12% ↑</span>
                <a href="{{ route('reservations.index') }}" class="text-purple-600 hover:text-purple-700 flex items-center">
                    विस्तृत हेर्नुहोस्
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- आजको अर्डर कार्ड -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">आजको अर्डर</p>
                    <p class="text-3xl font-bold text-green-600">रु {{ number_format($todayOrders) }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-full">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex justify-between items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400">गत महिनाको तुलनामा 8% ↑</span>
                <a href="{{ route('orders.index') }}" class="text-green-600 hover:text-green-700 flex items-center">
                    अर्डर हेर्नुहोस्
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- मेनु आइटम कार्ड -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">सक्रिय मेनु</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $menuCount }}</p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900/20 rounded-full">
                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex justify-between items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400">5 नयाँ आइटम थपिएको</span>
                <a href="{{ route('menu') }}" class="text-orange-600 hover:text-orange-700 flex items-center">
                    मेनु व्यवस्थापन
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- चार्ट सेक्सन -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- राजस्व विश्लेषण -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">राजस्व प्रवृत्ति</h3>
                <div class="flex gap-2">
                    <button class="px-3 py-1 text-sm rounded-full bg-purple-600 text-white">6 महिना</button>
                    <button class="px-3 py-1 text-sm rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">1 वर्ष</button>
                </div>
            </div>
            <canvas id="revenueChart" class="w-full h-64"></canvas>
        </div>

        <!-- अर्डर स्थिति -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">अर्डर स्थिति</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-green-100 dark:bg-green-900/20 rounded-xl text-center">
                    <div class="text-2xl font-bold text-green-600">85%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">सफल अर्डर</div>
                </div>
                <div class="p-4 bg-yellow-100 dark:bg-yellow-900/20 rounded-xl text-center">
                    <div class="text-2xl font-bold text-yellow-600">12%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">प्रगतिमा</div>
                </div>
                <div class="p-4 bg-red-100 dark:bg-red-900/20 rounded-xl text-center">
                    <div class="text-2xl font-bold text-red-600">3%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">रद्द भएका</div>
                </div>
                <div class="p-4 bg-blue-100 dark:bg-blue-900/20 rounded-xl text-center">
                    <div class="text-2xl font-bold text-blue-600">0.5%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300">फिर्ता भएका</div>
                </div>
            </div>
        </div>
    </div>

    <!-- हालैका अर्डरहरू -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">हालैका अर्डरहरू</h3>
            <div class="relative w-full md:w-64">
                <input type="text" placeholder="अर्डर खोज्नुहोस्..." class="w-full pl-4 pr-10 py-2 border dark:border-gray-700 rounded-lg bg-transparent focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <svg class="w-5 h-5 absolute right-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">अर्डर ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ग्राहक</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">मिति</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">रकम</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">स्थिति</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($recentOrders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">#{{ $order->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">रु {{ number_format($order->total) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">कुनै अर्डर फेला परेन</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'राजस्व (रु)',
            data: @json($chartData),
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(value) {
                        return 'रु ' + value;
                    }
                }
            }
        }
    }
});
</script>
@endpush
