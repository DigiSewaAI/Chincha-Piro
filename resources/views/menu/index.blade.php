@extends('layouts.app')
@section('title', 'मेनु - Chincha Piro')
@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl md:text-5xl font-bold text-center text-red-600 mb-6">हाम्रो मेनु</h1>
    <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
        हाम्रा विविध नेपाली पकवानहरूको स्वाद लिनुहोस् - ताजा, स्वादिष्ट र स्वस्थ।
    </p>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap justify-center gap-4 mb-10">
        <button onclick="filterMenu('all')" class="filter-btn active" aria-pressed="true">सबै पकवान</button>
        @foreach($categories as $category)
            <button
                onclick="filterMenu('{{ $category->id }}')"
                class="filter-btn"
                aria-label="फिल्टर गर्नुहोस्: {{ $category->name }}"
                aria-pressed="false"
            >
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($menus as $menu)
            <div class="menu-item animate-fadeIn" data-category="{{ $menu->category_id }}" itemscope itemtype="https://schema.org/FoodEstablishment">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col h-full hover:shadow-xl hover:scale-105 transition-transform duration-300">
                    <div class="h-48 bg-gray-100 relative overflow-hidden">
                        <img
                            src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/placeholder.png') }}"
                            alt="{{ $menu->name }}"
                            loading="lazy"
                            itemprop="image"
                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                            onerror="this.onerror=null;this.src='{{ asset('images/placeholder.png') }}';"
                        >
                        @if($menu->is_featured)
                            <span class="absolute top-3 right-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full font-bold">FEATURED</span>
                        @endif
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="text-sm text-gray-500 mb-1">{{ $menu->category->name }}</div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2" itemprop="name">{{ $menu->name }}</h2>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $menu->description }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xl font-bold text-red-600" itemprop="price">रु {{ number_format($menu->price, 2) }}</span>
                            <span class="text-sm text-green-600 font-medium">उपलब्ध: {{ $menu->stock }}</span>
                        </div>

                        <!-- ✅ सही बटन (AJAX सँग समायोजित) -->
                        <button
                            class="order-now w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300"
                            data-id="{{ $menu->id }}"
                            data-price="{{ $menu->price }}"
                            data-stock="{{ $menu->stock }}"
                        >
                            कार्टमा थप्नुहोस्
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <p class="text-xl text-gray-500">कुनै पनि पकवान भेटिएन।</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $menus->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 🛒 Order Now बटनको AJAX प्रतिक्रिया
    $(document).ready(function () {
        $('.order-now').on('click', function(e) {
            e.preventDefault(); // 🚫 पृष्ठ रिफ्रेस रोक्नुहोस्

            const itemId = $(this).data('id');
            const expectedPrice = $(this).data('price');
            const maxStock = $(this).data('stock');

            // 📉 स्टक जाँच (साइडमा स्टक प्रदर्शन गर्नुहोस्)
            if (maxStock <= 0) {
                Toastify({
                    text: "यो आइटम उपलब्ध छैन",
                    duration: 3000,
                    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)"
                }).showToast();
                return;
            }

            $.ajax({
                url: "{{ route('cart.add', '') }}/" + itemId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: 1,
                    expected_price: expectedPrice
                },
                success: function(response) {
                    $('#cart-count').text(response.cart_count); // 📦 कार्टको संख्या अपडेट
                    Toastify({
                        text: response.success,
                        duration: 3000,
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                    }).showToast();
                },
                error: function(xhr) {
                    Toastify({
                        text: xhr.responseJSON.error || 'त्रुटि भयो',
                        duration: 3000,
                        backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)"
                    }).showToast();
                }
            });
        });

        // 📦 प्रारम्भिक कार्टको संख्या अपडेट
        function loadCartCount() {
            fetch("{{ route('cart.count') }}")
                .then(res => res.json())
                .then(data => {
                    $('#cart-count').text(data.count);
                });
        }
        loadCartCount();
        setInterval(loadCartCount, 5000); // 🕒 प्रत्येक 5 सेकण्डमा
    });
</script>
@endpush

@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .filter-btn {
        @apply px-4 py-2 rounded-full text-sm font-medium border border-red-600 text-red-600 hover:bg-red-100 transition-colors;
    }
    .filter-btn.active {
        @apply bg-red-600 text-white;
    }
    .order-now {
        @apply bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300;
    }
</style>
@endpush
