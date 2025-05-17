@extends('layouts.app', ['title' => 'अर्डर व्यवस्थापन'])
@section('content')
<div x-data="orderSystem()" class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Real-time Notification Badge -->
    <div class="fixed top-4 right-4 z-50">
        <div x-show="hasNewOrders"
             x-transition:enter="transition ease-out duration-300"
             x-transition:leave="transition ease-in duration-200"
             class="animate-pulse bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="nepali-font">नयाँ अर्डर आएको छ!</span>
        </div>
    </div>

    <!-- Enhanced Category Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-wrap gap-2 mb-8">
            <template x-for="(category, index) in categories" :key="index">
                <button
                    @click="activeCategory = index"
                    :class="activeCategory === index
                        ? 'bg-red-600 text-white border-red-700'
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'"
                    class="px-6 py-3 rounded-xl border-2 transition-all duration-300 nepali-font text-sm font-medium flex items-center"
                >
                    <img :src="category.icon" class="w-6 h-6 mr-2" :alt="category.name">
                    <span x-text="category.name"></span>
                </button>
            </template>
        </div>

        <!-- Improved Dish Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="dish in filteredDishes" :key="dish.id">
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                    <!-- Popular Badge -->
                    <div x-show="dish.is_popular" class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-xs nepali-font">
                        लोकप्रिय
                    </div>
                    <img :src="dish.image" :alt="dish.name" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 nepali-font" x-text="dish.name"></h3>

                        <!-- Dietary Information -->
                        <div class="flex gap-2 mt-2">
                            <span x-show="dish.is_vegetarian"
                                  class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                शाकाहारी
                            </span>
                            <span x-show="dish.is_spicy"
                                  class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                मसलादार
                            </span>
                        </div>

                        <!-- Price and Quantity Controls -->
                        <div class="mt-4 flex justify-between items-center">
                            <div>
                                <span class="text-red-600 font-bold text-lg"
                                      x-text="'रु ' + dish.price"></span>
                                <span class="text-sm text-gray-500 ml-2"
                                      x-text="'(' + dish.calories + ' क्यालोरी)'"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button @click="decrementQuantity(dish)"
                                        class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <input
                                    type="number"
                                    x-model.number="dish.quantity"
                                    min="1"
                                    max="10"
                                    class="w-12 text-center bg-transparent border-b-2 border-red-500 focus:outline-none"
                                >
                                <button @click="incrementQuantity(dish)"
                                        class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        <button
                            @click="addToCart(dish)"
                            class="mt-4 w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg transition-colors nepali-font flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            कार्टमा थप्नुहोस्
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Shopping Cart Sidebar -->
    <div class="fixed right-0 top-0 h-screen w-96 bg-white dark:bg-gray-800 shadow-xl p-6 transform transition-transform duration-300"
         :class="cart.length > 0 ? 'translate-x-0' : 'translate-x-full'">
        <!-- Cart Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold nepali-font">तपाईंको कार्ट</h2>
            <button @click="clearCart" class="text-red-500 hover:text-red-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="space-y-4 overflow-y-auto h-[calc(100vh-250px)]">
            <template x-for="(item, index) in cart" :key="index">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <img :src="item.image" class="w-16 h-16 rounded-lg object-cover">
                        <div>
                            <h4 class="font-medium nepali-font" x-text="item.name"></h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <button @click="decrementCartItem(index)"
                                        class="bg-gray-200 dark:bg-gray-600 p-1 rounded">
                                    -
                                </button>
                                <span x-text="item.quantity" class="w-8 text-center"></span>
                                <button @click="incrementCartItem(index)"
                                        class="bg-gray-200 dark:bg-gray-600 p-1 rounded">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-bold" x-text="'रु ' + (item.price * item.quantity)"></span>
                        <button @click="removeFromCart(index)"
                                class="mt-1 text-red-500 hover:text-red-700">
                            हटाउनुहोस्
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Checkout Form -->
        <div class="absolute bottom-0 left-0 right-0 bg-white dark:bg-gray-800 p-6 border-t">
            <div class="space-y-3 mb-4">
                <div class="flex justify-between">
                    <span class="nepali-font">जम्मा रकम:</span>
                    <span class="font-bold" x-text="'रु ' + cartTotal"></span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>सेवा शुल्क:</span>
                    <span x-text="'रु ' + (cartTotal * 0.13).toFixed(2)"></span>
                </div>
                <div class="flex justify-between font-bold">
                    <span>अन्तिम रकम:</span>
                    <span x-text="'रु ' + (cartTotal * 1.13).toFixed(2)"></span>
                </div>
            </div>

            <!-- Special Instructions -->
            <textarea
                x-model="specialInstructions"
                class="w-full p-2 border rounded dark:bg-gray-700 mb-4"
                rows="2"
                placeholder="विशेष निर्देशनहरू लेख्नुहोस्..."
            ></textarea>

            <!-- Payment Methods -->
            <div class="mb-4">
                <label class="block mb-2 nepali-font">भुक्तानी विधि:</label>
                <div class="grid grid-cols-2 gap-3">
                    <template x-for="method in paymentMethods" :key="method.value">
                        <label
                            :class="selectedPayment === method.value
                                ? 'border-red-500 bg-red-50 dark:bg-gray-700'
                                : 'border-gray-300 dark:border-gray-600'"
                            class="border rounded p-3 cursor-pointer flex items-center space-x-2"
                        >
                            <input
                                type="radio"
                                x-model="selectedPayment"
                                :value="method.value"
                                class="hidden"
                            >
                            <img :src="method.logo" class="w-8 h-8">
                            <span x-text="method.label" class="nepali-font"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Checkout Button -->
            <button
                @click="checkout"
                :disabled="cart.length === 0"
                class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg nepali-font font-medium transition-colors disabled:opacity-50"
            >
                अर्डर पुष्टि गर्नुहोस्
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function orderSystem() {
    return {
        // State Management
        categories: @json($categories),
        dishes: @json($dishes->map(function($dish) {
            return array_merge($dish->toArray(), ['quantity' => 1]);
        })),
        activeCategory: 0,
        cart: [],
        specialInstructions: '',
        selectedPayment: 'cash',
        hasNewOrders: false,

        // Payment Methods
        paymentMethods: [
            { value: 'esewa', label: 'eSewa', logo: '/images/esewa-logo.png' },
            { value: 'khalti', label: 'खल्ती', logo: '/images/khalti-logo.png' },
            { value: 'cash', label: 'नगद', logo: '/images/cash-icon.png' },
            { value: 'card', label: 'कार्ड', logo: '/images/credit-card-icon.png' }
        ],

        // Computed Properties
        get filteredDishes() {
            const activeCategory = this.categories[this.activeCategory];
            return this.dishes.filter(
                dish => dish.category_id === activeCategory.id
            );
        },
        get cartTotal() {
            return this.cart.reduce(
                (sum, item) => sum + (item.price * item.quantity), 0
            );
        },

        // Methods
        incrementQuantity(dish) {
            if (dish.quantity < 10) dish.quantity++;
        },
        decrementQuantity(dish) {
            if (dish.quantity > 1) dish.quantity--;
        },
        addToCart(dish) {
            const existing = this.cart.find(i => i.id === dish.id);
            if (existing) {
                existing.quantity += dish.quantity;
            } else {
                this.cart.push({
                    ...dish,
                    quantity: dish.quantity
                });
            }
            this.resetDishQuantity(dish);
        },
        resetDishQuantity(dish) {
            dish.quantity = 1;
        },
        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        clearCart() {
            this.cart = [];
        },
        decrementCartItem(index) {
            if (this.cart[index].quantity > 1) {
                this.cart[index].quantity--;
            }
        },
        incrementCartItem(index) {
            if (this.cart[index].quantity < 10) {
                this.cart[index].quantity++;
            }
        },
        async checkout() {
            try {
                const response = await fetch("{{ route('orders.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        items: this.cart,
                        total: this.cartTotal * 1.13, // Including service charge
                        special_instructions: this.specialInstructions,
                        payment_method: this.selectedPayment
                    })
                });

                if (response.ok) {
                    window.location.href = "{{ route('orders.success') }}";
                } else {
                    const error = await response.json();
                    alert(error.message || 'Checkout failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('नेटवर्क त्रुटि भयो, पछि प्रयास गर्नुहोस्');
            }
        }
    }
}
</script>
@endpush

@push('styles')
<style>
.nepali-font {
    font-family: 'Preeti', 'Noto Sans Devanagari', sans-serif;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
::-webkit-scrollbar-thumb {
    background-color: #dc2626;
    border-radius: 4px;
}
.dark ::-webkit-scrollbar-thumb {
    background-color: #ef4444;
}
</style>
@endpush
@endsection
