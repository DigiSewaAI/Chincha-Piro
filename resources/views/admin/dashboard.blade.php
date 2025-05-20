@extends('layouts.app', ['title' => 'ड्यासबोर्ड'])
@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- हेडर सेक्सन -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 nepali-font">प्रशासनिक नियन्त्रण केन्द्र</h1>
                <p class="nepali-font text-gray-500 dark:text-gray-400 mt-1">चिन्चा पिरोको प्रदर्शन विश्लेषण</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- ✅ फारमको रूट नाम सही छ -->
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dish_id" value="1"> <!-- उदाहरणलाई लागि -->
                    <input type="hidden" name="quantity" value="1"> <!-- उदाहरणलाई लागि -->
                    <button
                        type="submit"
                        class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center shadow-md"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        नयाँ अर्डर
                    </button>
                </form>
            </div>
        </div>
        <!-- स्ट्याट्स कार्ड -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- आजको अर्डर कार्ड -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="nepali-font text-gray-500 dark:text-gray-400 text-sm mb-2">आजको अर्डर</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $totalOrders ?? 18 }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-full">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center text-sm">
                    <span class="text-green-500 dark:text-green-400">+12% ↑ गत हप्ताको तुलनामा</span>
                    <a href="#" class="nepali-font text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 flex items-center">
                        विस्तृत हेर्नुहोस्
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            <!-- आजको राजस्व कार्ड -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="nepali-font text-gray-500 dark:text-gray-400 text-sm mb-2">आजको राजस्व</p>
                        <p class="text-3xl font-bold text-green-600">रु {{ number_format($revenueToday ?? 85000) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-full">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center text-sm">
                    <span class="text-green-500 dark:text-green-400">+8% ↑ गत महिनाको तुलनामा</span>
                    <a href="#" class="nepali-font text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 flex items-center">
                        अर्डर हेर्नुहोस्
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            <!-- सक्रिय मेनु कार्ड -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="nepali-font text-gray-500 dark:text-gray-400 text-sm mb-2">सक्रिय मेनु</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $activeMenus ?? 42 }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/20 rounded-full">
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center text-sm">
                    <span class="nepali-font text-gray-500 dark:text-gray-400">५ नयाँ आइटम थपिएको</span>
                    <a href="{{ route('menu.index') }}" class="nepali-font text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 flex items-center">
                        मेनु व्यवस्थापन
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <!-- चार्ट र स्थिति सेक्सन -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- राजस्व चार्ट -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="nepali-font text-xl font-semibold text-gray-800 dark:text-gray-200">राजस्व प्रवृत्ति</h3>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 text-sm rounded-full bg-purple-600 text-white hover:bg-purple-700 transition-colors">६ महिना</button>
                        <button class="px-3 py-1 text-sm rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">१ वर्ष</button>
                    </div>
                </div>
                <canvas id="revenueChart" class="w-full h-64"></canvas>
            </div>
            <!-- अर्डर स्थिति -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                <h3 class="nepali-font text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">अर्डर स्थिति</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-green-100 dark:bg-green-900/20 rounded-xl text-center transition-transform duration-300 hover:scale-105">
                        <div class="text-2xl font-bold text-green-600">८५%</div>
                        <div class="nepali-font text-sm text-gray-600 dark:text-gray-300">सफल अर्डर</div>
                    </div>
                    <div class="p-4 bg-yellow-100 dark:bg-yellow-900/20 rounded-xl text-center transition-transform duration-300 hover:scale-105">
                        <div class="text-2xl font-bold text-yellow-600">१२%</div>
                        <div class="nepali-font text-sm text-gray-600 dark:text-gray-300">प्रगतिमा</div>
                    </div>
                    <div class="p-4 bg-red-100 dark:bg-red-900/20 rounded-xl text-center transition-transform duration-300 hover:scale-105">
                        <div class="text-2xl font-bold text-red-600">३%</div>
                        <div class="nepali-font text-sm text-gray-600 dark:text-gray-300">रद्द भएका</div>
                    </div>
                    <div class="p-4 bg-blue-100 dark:bg-blue-900/20 rounded-xl text-center transition-transform duration-300 hover:scale-105">
                        <div class="text-2xl font-bold text-blue-600">०.५%</div>
                        <div class="nepali-font text-sm text-gray-600 dark:text-gray-300">फिर्ता भएका</div>
                    </div>
                </div>
                <div class="mt-6 h-4 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full w-3/4 bg-green-500 relative">
                        <div class="absolute right-0 top-0 bottom-0 w-1/4 bg-yellow-500"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-1/12 bg-red-500"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-1/24 bg-blue-500"></div>
                    </div>
                </div>
                <div class="mt-3 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>सफल</span>
                    <span>प्रगतिमा</span>
                    <span>रद्द</span>
                    <span>फिर्ता</span>
                </div>
            </div>
        </div>
        <!-- हालैका अर्डरहरू -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h3 class="nepali-font text-xl font-semibold text-gray-800 dark:text-gray-200">हालैका अर्डरहरू</h3>
                <div class="relative w-full md:w-64">
                    <input type="text" placeholder="अर्डर खोज्नुहोस्..." class="w-full pl-4 pr-10 py-2 border dark:border-gray-700 rounded-lg bg-transparent focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    <svg class="w-5 h-5 absolute right-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider nepali-font">अर्डर ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider nepali-font">ग्राहक</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider nepali-font">मिति</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider nepali-font">रकम</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider nepali-font">भुक्तानी</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider nepali-font">स्थिति</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($recentOrders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">#ORD{{ $order->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">रु {{ number_format($order->total_price) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($order->payment_status === 'paid')
                                        bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                    @else
                                        bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                    @endif nepali-font">
                                    {{ $order->payment_status === 'paid' ? 'पूरा भएको' : 'बाँकी' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @switch($order->status)
                                        @case('completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @break
                                        @case('processing') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 @break
                                        @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @break
                                        @default bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @endswitch nepali-font">
                                    @switch($order->status)
                                        @case('completed') पूरा @break
                                        @case('processing') प्रगतिमा @break
                                        @case('cancelled') रद्द @break
                                        @default फिर्ता
                                    @endswitch
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 nepali-font">कुनै अर्डर छैन</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-end">
                <a href="#" class="nepali-font text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 text-sm font-medium flex items-center">
                    सबै अर्डरहरू हेर्नुहोस्
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
        <!-- सूचना र कार्य सेक्सन -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                <h3 class="nepali-font text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">महत्वपूर्ण सूचनाहरू</h3>
                <div class="space-y-4">
                    <div class="flex items-start p-4 border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10 rounded-r-lg transition-transform duration-300 hover:translate-x-1">
                        <svg class="w-6 h-6 text-yellow-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h4 class="font-medium text-yellow-800 dark:text-yellow-300 nepali-font">आउँदो कार्यक्रम</h4>
                            <p class="text-yellow-700 dark:text-yellow-200 nepali-font">२ दिन पछि आगामी बैठक छ।</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/10 rounded-r-lg transition-transform duration-300 hover:translate-x-1">
                        <svg class="w-6 h-6 text-red-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h4 class="font-medium text-red-800 dark:text-red-300 nepali-font">महत्वपूर्ण घटना</h4>
                            <p class="text-red-700 dark:text-red-200 nepali-font">३ अर्डरहरू भुक्तानी बाँकी छन्।</p>
                        </div>
                    </div>
                    <div class="flex items-start p-4 border-l-4 border-green-500 bg-green-50 dark:bg-green-900/10 rounded-r-lg transition-transform duration-300 hover:translate-x-1">
                        <svg class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="font-medium text-green-800 dark:text-green-300 nepali-font">सफलता</h4>
                            <p class="text-green-700 dark:text-green-200 nepali-font">१२ अर्डरहरू सफलतापूर्वक पूरा भए।</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                <h3 class="nepali-font text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">कार्य सूची</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <input type="checkbox" id="task1" class="mt-1 mr-3 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="task1" class="nepali-font text-gray-700 dark:text-gray-300">पछिल्लो ७ दिनका अर्डरहरूको विश्लेषण गर्नुहोस्</label>
                    </li>
                    <li class="flex items-start">
                        <input type="checkbox" id="task2" class="mt-1 mr-3 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="task2" class="nepali-font text-gray-700 dark:text-gray-300">खानेकुराको सूचीमा नयाँ आइटम थप्नुहोस्</label>
                    </li>
                    <li class="flex items-start">
                        <input type="checkbox" id="task3" class="mt-1 mr-3 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="task3" class="nepali-font text-gray-700 dark:text-gray-300">ग्राहक समीक्षा जाँच गर्नुहोस्</label>
                    </li>
                    <li class="flex items-start">
                        <input type="checkbox" id="task4" class="mt-1 mr-3 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="task4" class="nepali-font text-gray-700 dark:text-gray-300">प्रदर्शन रिपोर्ट तयार गर्नुहोस्</label>
                    </li>
                    <li class="flex items-start">
                        <input type="checkbox" id="task5" class="mt-1 mr-3 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="task5" class="nepali-font text-gray-700 dark:text-gray-300">अगामी बैठकका लागि सामग्री तयार गर्नुहोस्</label>
                    </li>
                </ul>
                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <span class="nepali-font text-sm text-gray-500 dark:text-gray-400">प्रगति</span>
                        <span class="nepali-font text-sm font-medium text-purple-600 dark:text-purple-400">४०%</span>
                    </div>
                    <div class="mt-2 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full w-2/5 bg-purple-600 dark:bg-purple-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js  "></script>
<!-- Toastify -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js  "></script>
<!-- Livewire -->
@livewireScripts
<!-- Order Modal Script -->
<script>
    function openOrderModal() {
        document.getElementById('orderModal').classList.remove('hidden');
    }
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
    }
    // ESC Key मार्फत मोडल बन्द गर्नुहोस्
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closeOrderModal();
    });
    // Toast Notification
    @if(session('success'))
        Toastify({
            text: "{{ session('success') }}",
            duration: 5000,
            gravity: "top",
            position: "right",
            backgroundColor: "#4CAF50",
            className: "nepali-font",
        }).showToast();
    @endif
</script>
<!-- Revenue Chart -->
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['जनवरी', 'फेब्रुअरी', 'मार्च', 'अप्रिल', 'मे', 'जुन'],
            datasets: [{
                label: 'राजस्व (रु)',
                data: [4000, 3000, 5000, 4500, 6000, 7000],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#6366f1',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return 'रु ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
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
@endsection
