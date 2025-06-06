@extends('layouts.app')

@section('title', 'चिञ्‍चा पिरो - नेपाली मसालाको अनुभूति')

@section('content')
<!-- Hero Section -->
<section class="relative h-screen bg-gray-900 flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('images/hero-bg.jpg') }}')">
  <div class="absolute inset-0 bg-black/50"></div>
  <div class="container mx-auto px-4 relative z-10">
    <div class="text-center text-white space-y-8">
      <h1 class="text-5xl md:text-8xl font-bold nepali-font animate-fadeIn mb-6">चिञ्‍चा पिरो</h1>
      <p class="text-3xl md:text-5xl nepali-font text-red-400 italic mb-8">"Welcome to Chincha Piro"</p>
      <div class="flex flex-col md:flex-row justify-center gap-6">
        <a href="#menu" class="bg-red-600 text-white px-12 py-4 rounded-full nepali-font text-xl hover:bg-red-700 transition-all">मेनु हेर्नुहोस्</a>
        <a href="{{ route('cart.index') }}" class="border-2 border-red-600 text-red-500 px-12 py-4 rounded-full nepali-font text-xl hover:bg-red-600 hover:text-white transition-all">कार्ट हेर्नुहोस्</a>
      </div>
    </div>
  </div>
</section>

<!-- Featured Section -->
<section id="menu" class="py-24 bg-gradient-to-b from-white to-gray-50">
  <div class="container mx-auto px-4">
    <div class="text-center mb-20">
      <h2 class="text-4xl md:text-5xl font-bold mb-6 nepali-font text-red-600">Featured डिशहरू</h2>
      <p class="text-xl text-gray-600 max-w-2xl mx-auto">हाम्रा सबैभन्दा लोकप्रिय पकवानहरूको स्वाद लिनुहोस्</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
      @forelse ($featuredMenus as $menu)
      <div class="group relative bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
        <div class="relative h-96 overflow-hidden">
          <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/placeholder.png') }}" class="w-full h-full object-cover" alt="{{ $menu->name }}">
          <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
            <!-- ✅ Quantity Form -->
            <form class="add-to-cart-form" data-id="{{ $menu->id }}" data-price="{{ $menu->price }}">
                @csrf
                <div class="flex items-center gap-2">
                    <input type="number" name="quantity" min="1" max="{{ $menu->stock }}" value="1" class="quantity-input w-16 px-2 py-1 border rounded">
                    <button
                        type="submit"
                        class="order-now flex-1 bg-red-600 text-white font-bold py-2 px-4 rounded-full nepali-font text-md hover:bg-red-700 transition-all"
                    >
                        <i class="fas fa-shopping-cart me-2"></i> कार्टमा थप्नुहोस्
                    </button>
                </div>
            </form>
          </div>
        </div>
        <div class="p-8 space-y-4">
          <h3 class="text-2xl font-bold nepali-font text-gray-800">{{ $menu->name }}</h3>
          <p class="text-gray-600 nepali-font line-clamp-2">{{ $menu->description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-red-500 text-lg nepali-font">★ {{ rand(4, 5) }}/५</span>
            <span class="text-xl font-bold text-red-600 nepali-font">रु {{ number_format($menu->price, 2) }}</span>
          </div>
        </div>
      </div>
      @empty
      <div class="col-span-full text-center py-12">
        <p class="text-gray-500 text-xl">कुनै featured डिश भेटिएन</p>
      </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Random Dishes Section -->
<section class="py-24 bg-gradient-to-b from-gray-100 to-white">
  <div class="container mx-auto px-4">
    <div class="flex flex-col items-center mb-20">
      <img src="{{ asset('images/cartoon-chef.png') }}" alt="Chincha Chef" class="w-48 md:w-64 mb-6">
      <h2 class="text-5xl md:text-6xl font-bold mb-20 nepali-font text-red-600 border-b-4 border-red-600 pb-4">हाम्रो विशेष पकवानहरू</h2>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
      @foreach($dishes as $dish)
      <div class="group relative bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="relative h-96 overflow-hidden">
          <img src="{{ $dish->image ? Storage::url($dish->image) : asset('images/placeholder.png') }}" alt="{{ $dish->name }}" class="w-full h-full object-cover">
          <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
            <!-- ✅ Quantity Form -->
            <form class="add-to-cart-form" data-id="{{ $dish->id }}" data-price="{{ $dish->price }}">
                @csrf
                <div class="flex items-center gap-2">
                    <input type="number" name="quantity" min="1" max="{{ $dish->stock }}" value="1" class="quantity-input w-16 px-2 py-1 border rounded">
                    <button
                        type="submit"
                        class="order-now flex-1 bg-red-600 text-white font-bold py-2 px-4 rounded-full nepali-font text-md hover:bg-red-700 transition-all"
                    >
                        <i class="fas fa-shopping-cart me-2"></i> कार्टमा थप्नुहोस्
                    </button>
                </div>
            </form>
          </div>
        </div>
        <div class="p-8 space-y-4">
          <h3 class="text-3xl font-bold nepali-font text-gray-800">{{ $dish->name }}</h3>
          <p class="text-gray-600 nepali-font">{{ $dish->description }}</p>
          <div class="flex justify-between items-center">
            <span class="text-red-500 text-xl nepali-font">★ {{ $dish->spice_level ?? 4 }}/५</span>
            <span class="text-2xl font-bold text-red-600 nepali-font">रु {{ number_format($dish->price, 2) }}</span>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  .nepali-font {
    font-family: 'Noto Sans Devanagari', 'Preeti', sans-serif;
    letter-spacing: 0.5px;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .animate-fadeIn {
    animation: fadeIn 1s ease-out;
  }

  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .quantity-input {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 4px 8px;
    width: 60px;
    text-align: center;
  }
</style>
@endpush

@push('scripts')
<!-- Toastify CDN -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // ✅ Unified Add-to-Cart handler
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> कृपया प्रतीक्षा गर्नुहोस्...';

            const itemId = this.dataset.id;
            const expectedPrice = parseFloat(this.dataset.price);
            const quantityInput = this.querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value) || 1;

            if (quantity > parseInt(quantityInput.max)) {
                showToast(`अधिकतम उपलब्ध मात्रा: ${quantityInput.max}`, 'error');
                resetButton();
                return;
            }

            try {
                const response = await fetch("{{ route('cart.add') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Content-Type': 'application/json'
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
                    showToast(data.message, 'success');
                    updateCartPanel(data.cart_items);
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                console.error('AJAX Error:', error);
                showToast('कार्टमा आइटम थप्न असफल', 'error');
            } finally {
                resetButton();
            }

            function resetButton() {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    });

    // ✅ Cart Panel Update
    function updateCartPanel(items) {
        const container = document.getElementById('cart-items-container');
        if (!container) return;

        if (items.length > 0) {
            container.innerHTML = items.map(item => `
                <div class="cart-item flex justify-between items-center p-2 border-b">
                    <span class="nepali-font">${item.name}</span>
                    <span class="nepali-font">${item.quantity} x ₹${item.price.toFixed(2)}</span>
                    <span class="font-bold nepali-font">₹${(item.price * item.quantity).toFixed(2)}</span>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p class="text-center nepali-font">तपाईँको कार्ट खाली छ</p>';
        }
    }

    // ✅ Toast Notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded shadow-lg z-50 transition-all duration-300 transform ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;
        toast.innerHTML = `<div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ✅ Live Cart Count
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
});
</script>
@endpush
