<div class="max-w-4xl mx-auto p-4">
    <!-- Search & Filters -->
    <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <input wire:model="search" placeholder="खोज्नुहोस्: क्रम सङ्ख्या वा ग्राहक नाम"
               class="w-full md:w-1/2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 nepali-font">

        <select wire:model="statusFilter"
                class="w-full md:w-1/4 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 nepali-font">
            <option value="">सबै स्थिति</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
            @endforeach
        </select>

        <button wire:click="clearFilters"
                class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition nepali-font">
            फिल्टर हटाउनुहोस्
        </button>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white p-4 rounded-lg shadow border-l-4
                @if($order->status == 'pending') border-yellow-500
                @elseif($order->status == 'completed') border-green-500
                @else border-red-500 @endif">

                <div class="flex flex-col sm:flex-row justify-between">
                    <!-- Dish Details -->
                    <div>
                        <h4 class="nepali-font text-lg font-bold">{{ $order->dish->name }}</h4>
                        <p class="nepali-font text-sm text-gray-600">मात्रा: {{ $order->quantity }}</p>
                    </div>

                    <!-- Price & Time -->
                    <div class="text-right">
                        <p class="nepali-font font-semibold text-lg">रु {{ number_format($order->total_price, 2) }}</p>
                        <span class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Status & Order ID -->
                <div class="mt-3 flex justify-between items-center text-sm">
                    <span class="px-2 py-1 rounded-full
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif
                        nepali-font">
                        {{ $order->status }}
                    </span>
                    <span class="text-gray-400">अर्डर #{{ $order->id }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 nepali-font">
                कुनै अर्डर भेटिएन।
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
