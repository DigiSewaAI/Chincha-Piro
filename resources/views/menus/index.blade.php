@extends('layouts.app')

@section('title', 'मेनु - Chincha Piro')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl md:text-5xl font-bold text-center text-red-600 mb-6 nepali-font">हाम्रो मेनु</h1>
    <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto nepali-font">
        हाम्रा विविध नेपाली पकवानहरूको स्वाद लिनुहोस् - ताजा, स्वादिष्ट र स्वस्थ।
    </p>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap justify-center gap-4 mb-10" id="category-filters">
        <button onclick="filterMenu('all')" class="filter-btn active">सबै पकवान</button>
        @foreach($categories as $category)
            <button onclick="filterMenu('{{ $category->id }}')" class="filter-btn">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($menus as $menu)
            <div class="menu-item" data-category="{{ $menu->category_id }}">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col h-full hover:shadow-xl hover:scale-105 transition-transform duration-300">
                    <div class="h-48 bg-gray-100 relative overflow-hidden">
                        <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/placeholder.png') }}" alt="{{ $menu->name }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        @if($menu->is_featured)
                            <span class="absolute top-3 right-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full font-bold">FEATURED</span>
                        @endif
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="text-sm text-gray-500 mb-1 nepali-font">{{ $menu->category?->name ?? 'Uncategorized' }}</div>
                        <h2 class="text-xl font-semibold nepali-font text-gray-800">{{ $menu->name }}</h2>
                        <p class="text-gray-600 nepali-font">{{ $menu->description }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xl font-bold text-red-600 nepali-font">रु {{ number_format($menu->price, 2) }}</span>
                            <span class="text-sm text-green-600 font-medium nepali-font">उपलब्ध: {{ $menu->stock }}</span>
                        </div>

                        <!-- Add to Cart Form -->
                        <form class="add-to-cart-form" data-id="{{ $menu->id }}" data-price="{{ $menu->price }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $menu->id }}">
                            <input type="hidden" name="expected_price" value="{{ $menu->price }}">
                            <div class="flex items-center gap-2">
                                <input type="number" name="quantity" min="1" max="{{ $menu->stock }}" value="1" class="quantity-input" />
                                <button type="submit" class="order-now bg-red-600 text-white font-bold py-2 px-4 rounded-full nepali-font hover:bg-red-700 transition-all">
                                    <i class="fas fa-shopping-cart"></i> कार्टमा थप्नुहोस्
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <p class="text-xl text-gray-500 nepali-font">कुनै पनि पकवान भेटिएन।</p>
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
    // Add to Cart Handler
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> कृपया प्रतीक्षा गर्नुहोस्...`;

            const itemId = form.dataset.id;
            const expectedPrice = parseFloat(form.dataset.price);
            const quantityInput = form.querySelector('input[name="quantity"]');
            const quantity = parseInt(quantityInput.value);

            if (quantity > parseInt(quantityInput.max)) {
                showToast(`अधिकतम उपलब्ध मात्रा: ${quantityInput.max}`, 'error');
                resetBtn();
                return;
            }

            try {
                const response = await fetch("{{ route('cart.add') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: quantity,
                        expected_price: expectedPrice
                    })
                });
                const data = await response.json();

                if (data.success) {
                    document.getElementById('cart-count')?.textContent = data.cart_count;
                    updateCartPanel(data.cart_items);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                showToast("कार्टमा थप्न असफल भयो", 'error');
                console.error(err);
            } finally {
                resetBtn();
            }

            function resetBtn() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });

    // Cart Panel Update
    function updateCartPanel(items) {
        const container = document.getElementById('cart-items-container');
        if (!container) return;

        if (items.length > 0) {
            container.innerHTML = items.map(item => `
                <div class="cart-item flex justify-between items-center p-2 border-b">
                    <span class="nepali-font">${item.name}</span>
                    <span class="nepali-font">${item.quantity} x रु${item.price.toFixed(2)}</span>
                    <span class="font-bold nepali-font">रु${(item.price * item.quantity).toFixed(2)}</span>
                </div>
            `).join('');
        } else {
            container.innerHTML = `<p class="text-center nepali-font">तपाईंको कार्ट खाली छ।</p>`;
        }
    }

    // Toast Notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded shadow-lg z-50 text-white transition-all duration-300 transform ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        toast.innerHTML = `<div class="flex items-center gap-2">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-times-circle'}"></i>
            <span>${message}</span>
        </div>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Load Cart Count
    function loadCartCount() {
        fetch("{{ route('cart.count') }}")
            .then(res => res.json())
            .then(data => {
                const el = document.getElementById('cart-count');
                if (el) el.textContent = data.count;
            });
    }
    loadCartCount();
    setInterval(loadCartCount, 10000);

    // Menu Filter
    window.filterMenu = function(categoryId) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('onclick')?.includes(categoryId)) {
                btn.classList.add('active');
            }
        });
        document.querySelectorAll('.menu-item').forEach(item => {
            item.style.display = (categoryId === 'all' || item.dataset.category === categoryId) ? 'block' : 'none';
        });
    };
});
</script>
@endpush

@push('styles')
<style>
    .nepali-font {
        font-family: 'Noto Sans Devanagari', 'Preeti', sans-serif;
    }
    .quantity-input {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 4px 8px;
        width: 60px;
        text-align: center;
    }
    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        background-color: #f3f4f6;
        transition: all 0.3s;
    }
    .filter-btn.active {
        background-color: #dc2626;
        color: white;
    }
</style>
@endpush
