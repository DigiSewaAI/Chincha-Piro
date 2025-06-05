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

                        <!-- ✅ "Add to Cart" Button with Quantity -->
                        <div class="flex items-center gap-2">
                            <input type="number" min="1" max="{{ $menu->stock }}" value="1" class="quantity-input w-16 px-2 py-1 border rounded" data-id="{{ $menu->id }}">
                            <button
                                class="order-now flex-1"
                                data-id="{{ $menu->id }}"
                                data-stock="{{ $menu->stock }}"
                            >
                                कार्टमा थप्नुहोस्
                            </button>
                        </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        // AJAX Add to Cart
        document.querySelectorAll('.order-now').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.dataset.id;
                const stock = parseInt(this.dataset.stock);
                const quantityInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
                const quantity = parseInt(quantityInput.value) || 1;

                if (quantity > stock) {
                    showToast("उत्तिकै स्टक छैन", "error");
                    return;
                }

                fetch(`{{ route('cart.add', '') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ quantity: quantity })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cart-count').textContent = data.cart_count;
                        showToast(data.success, "success");
                    } else if (data.error) {
                        showToast(data.error, "error");
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    showToast("त्रुटि भयो", "error");
                });
            });
        });

        // Load cart count on page load and every 5 sec
        function loadCartCount() {
            fetch("{{ route('cart.count') }}")
                .then(res => res.json())
                .then(data => {
                    const el = document.getElementById('cart-count');
                    if (el) el.textContent = data.count;
                });
        }
        loadCartCount();
        setInterval(loadCartCount, 5000);

        // Menu Filter
        window.filterMenu = function (categoryId) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            document.querySelectorAll('.menu-item').forEach(item => {
                if (categoryId === 'all' || item.dataset.category === categoryId) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Toast Notification
        window.showToast = function (message, type = "success") {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 p-4 rounded shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
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
    .quantity-input {
        @apply border border-gray-300 rounded px-2 py-1 text-center;
    }
</style>
@endpush
