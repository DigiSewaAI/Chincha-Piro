<x-app-layout>
    <x-slot name="header">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white/95 backdrop-blur-lg h-screen fixed left-0 top-0 shadow-xl hidden md:block">
            <div class="p-6">
                <h1 class="text-3xl font-extrabold gradient-text mb-6">चिन्चा-पिरो</h1>
                <nav class="space-y-3">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 transition">
                        <i class="fas fa-home mr-4 text-xl"></i>
                        <span class="text-lg">ड्यासबोर्ड</span>
                    </x-nav-link>
                    <x-nav-link href="#" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 transition">
                        <i class="fas fa-chart-line mr-4 text-xl"></i>
                        <span class="text-lg">विश्लेषण</span>
                    </x-nav-link>
                    <x-nav-link href="{{ route('orders.index') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 transition">
                        <i class="fas fa-box-open mr-4 text-xl"></i>
                        <span class="text-lg">आर्डरहरू</span>
                    </x-nav-link>
                </nav>
            </div>
        </aside>

        <!-- Main Content Header -->
        <div class="ml-0 md:ml-64 transition-all">
            <div class="flex justify-between items-center p-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">नमस्ते, {{ $user->name }}! 👋</h1>
                    <p class="text-gray-500 text-sm">आजको मिति: {{ now()->format('Y-m-d') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-full bg-white shadow-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bell text-gray-600 text-lg"></i>
                    </button>
                    <div class="flex items-center space-x-3 bg-white/90 px-4 py-2 rounded-full shadow">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full border-2 border-white">
                        <div>
                            <p class="text-base font-medium">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">Admin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 ml-0 md:ml-64 transition-all">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Orders -->
                <div class="glass-card p-5 transform hover:scale-105 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm mb-2">कुल आर्डर</p>
                            <p class="text-2xl font-bold text-indigo-600">१,२४२</p>
                        </div>
                        <div class="p-3 bg-indigo-100 rounded-lg">
                            <i class="fas fa-shopping-cart text-indigo-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-1.5 bg-gray-200 rounded-full">
                            <div class="w-3/4 h-full bg-indigo-500 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Total Income -->
                <div class="glass-card p-5 transform hover:scale-105 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm mb-2">कुल आय</p>
                            <p class="text-2xl font-bold text-green-600">रु २५,४२१</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-wallet text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-green-500 text-sm font-medium">
                            <i class="fas fa-arrow-up mr-1"></i> १२% बृद्धि
                        </span>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="glass-card p-5 transform hover:scale-105 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm mb-2">सक्रिय प्रयोगकर्ता</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $activeUsers->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-3 overflow-hidden">
                        @foreach($activeUsers as $user)
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full border-2 border-white">
                        @endforeach
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-medium">
                            +{{ $totalActiveUsers - 5 }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="glass-card p-6 mb-8 transform hover:scale-105 transition">
                <h3 class="text-xl font-semibold mb-4">आय विश्लेषण</h3>
                <div class="h-72" id="chart"></div>
            </div>

            <!-- Recent Orders -->
            <div class="glass-card p-6 transform hover:scale-105 transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">हालका आर्डरहरू</h3>
                    <x-nav-link href="{{ route('orders.index') }}" class="text-indigo-600 hover:text-indigo-800 transition">
                        सबै हेर्नुहोस् →
                    </x-nav-link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left">आर्डर ID</th>
                                <th class="px-4 py-3 text-left">मिति</th>
                                <th class="px-4 py-3 text-left">स्थिति</th>
                                <th class="px-4 py-3 text-left">रकम</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">#{{ $order->id }}</td>
                                <td class="px-4 py-3">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 {{ $order->status_color }} rounded-full text-xs font-medium">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">रु {{ number_format($order->amount) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'मासिक आय',
                    data: @json($chartData),
                    borderColor: '#6366f1',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => `रु ${context.raw.toLocaleString()}`
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: (value) => `रु ${value.toLocaleString()}`,
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .gradient-text {
            background: linear-gradient(45deg, #6366f1, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link.active {
            @apply bg-indigo-100 text-indigo-600 font-semibold;
        }

        .nav-link:hover {
            @apply bg-indigo-50;
        }
    </style>
</x-app-layout>
