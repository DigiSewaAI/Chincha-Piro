@extends('layouts.app')

@section('title', 'मेनु - Chincha Piro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center text-orange-600 mb-10">हाम्रो मेनु</h1>

    <!-- Nepali Font Alert -->
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
        <p class="nepali-font">कृपया नेपाली फन्ट सक्रिय गर्नुहोस् ताकि सबै पाठ सही रूपमा प्रदर्शित होस्।</p>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($dishes as $dish)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
            <!-- Dish Image -->
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                @if($dish->image)
                    <img src="{{ asset('storage/' . $dish->image) }}" alt="{{ $dish->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-gray-400">छवि उपलब्ध छैन</span>
                @endif
            </div>

            <!-- Dish Info -->
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $dish->name }}</h2>
                <p class="text-gray-600 mb-4">{{ $dish->description }}</p>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xl font-bold text-orange-600">रु {{ number_format($dish->price, 2) }}</span>
                    <span class="text-sm text-green-600 font-medium">उपलब्ध</span>
                </div>

                <!-- Order Button -->
                <button
                    onclick="document.getElementById('orderModal{{ $dish->id }}').classList.remove('hidden')"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300"
                >
                    अर्डर गर्नुहोस्
                </button>
            </div>
        </div>

        <!-- Order Modal -->
        <div id="orderModal{{ $dish->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative">
                <button
                    onclick="document.getElementById('orderModal{{ $dish->id }}').classList.add('hidden')"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
                >✕</button>

                <h3 class="text-2xl font-bold text-gray-800 mb-4">अर्डर गर्नुहोस्: {{ $dish->name }}</h3>

                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="dish_id" value="{{ $dish->id }}">

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2 nepali-font" for="customer_name">ग्राहकको नाम</label>
                        <input type="text" name="customer_name" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2 nepali-font" for="phone">फोन नम्बर</label>
                        <input type="tel" name="phone" required pattern="98\d{8}"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="98XXXXXXXX">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2 nepali-font" for="address">ठेगाना</label>
                        <textarea name="address" rows="2" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2 nepali-font" for="special_instructions">विशेष निर्देश (वैकल्पिक)</label>
                        <textarea name="special_instructions" rows="2"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2 nepali-font" for="quantity">मात्रा</label>
                        <input type="number" name="quantity" min="1" max="10" value="1"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        अर्डर पुष्टि गर्नुहोस्
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10">
            <p class="text-xl text-gray-500">कुनै पनि पकवान भेटिएन।</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $dishes->links() }}
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
@endsection
