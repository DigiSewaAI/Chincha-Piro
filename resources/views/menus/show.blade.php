@extends('layouts.app')

@section('title', $menu->name)

@section('content')
<!-- CSRF Token (AJAXका लागि) -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mx-auto px-4 py-12">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- मेनु छवि -->
        <img src="{{ $menu->image ? asset('storage/'.$menu->image) : asset('images/placeholder.jpg') }}"
             alt="{{ $menu->name }}"
             class="w-full h-64 object-cover">

        <!-- मेनु विवरण -->
        <div class="p-6">
            <h1 class="text-3xl font-bold text-red-600 mb-4">{{ $menu->name }}</h1>
            <p class="text-gray-700 mb-6">{{ $menu->description }}</p>

            <div class="flex justify-between items-center mb-6">
                <span class="text-2xl font-semibold text-gray-800">रु {{ number_format($menu->price, 2) }}</span>
                <span class="text-sm text-green-600 font-medium">उपलब्ध: {{ $menu->stock }}</span>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add', $menu->id) }}"
                  method="POST"
                  class="w-full max-w-md add-to-cart-form"
                  data-stock="{{ $menu->stock }}">
                @csrf
                <div class="flex items-center border rounded-lg overflow-hidden">
                    <input type="number"
                           name="quantity"
                           value="1"
                           min="1"
                           max="{{ $menu->stock }}"
                           class="w-16 text-center border-r border-gray-300 focus:outline-none">
                    <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 hover:bg-red-700 transition duration-300 add-to-cart-btn">
                        कार्टमा थप्नुहोस्
                    </button>
                </div>
                @if($menu->stock < 5)
                    <p class="text-sm text-red-500 mt-1">केवल {{ $menu->stock }} आइटम उपलब्ध छन्</p>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ✅ AJAX अपडेटका लागि "Add to Cart" बटनमा क्रियाहरू
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const form = this.closest('form');
            const quantityInput = form.querySelector('input[name="quantity"]');
            const stock = parseInt(form.dataset.stock);
            const quantity = parseInt(quantityInput.value);

            // 📉 स्टक जाँच
            if (quantity > stock) {
                alert("अनुरोध गरिएको मात्रा उपलब्ध स्टकभन्दा बढी छ।");
                return;
            }

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 📦 कार्ट संख्या अपडेट
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) cartCount.textContent = data.cart_count;

                    // ✅ सफलता सन्देश
                    const toast = document.createElement('div');
                    toast.className = "fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50 animate-fade-in-down";
                    toast.textContent = data.success;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(20px)';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                alert('त्रुटि भयो');
            });
        });
    });

    // 📦 कार्ट संख्या अपडेट (प्रत्येक 5 सेकण्डमा)
    function loadCartCount() {
        fetch("{{ route('cart.count') }}")
            .then(res => res.json())
            .then(data => {
                const countElement = document.getElementById('cart-count');
                if (countElement) countElement.textContent = data.count;
            });
    }

    loadCartCount();
    setInterval(loadCartCount, 5000);
});
</script>
@endpush

@push('styles')
<style>
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-down {
    animation: fadeInDown 0.3s ease-out;
}
</style>
@endpush
