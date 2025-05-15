@extends('layouts.app', ['title' => 'अर्डर व्यवस्थापन'])
@section('content')
<div x-data="orderSystem()" class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Real-time Notification Badge -->
    <div class="fixed top-4 right-4 z-50">
        <div x-show="hasNewOrders" class="animate-pulse bg-red-500 text-white px-3 py-1 rounded-full text-sm">
            नयाँ अर्डर उपलब्ध!
        </div>
    </div>
    <!-- Dual Column Layout -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
        <!-- Menu Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Category Tabs -->
            <div class="flex overflow-x-auto pb-2">
                <template x-for="(category, index) in categories" :key="index">
                    <button
                        @click="activeCategory = index"
                        :class="activeCategory === index
                            ? 'bg-red-600 text-white'
                            : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                        class="px-4 py-2 rounded-lg mr-2 transition-colors nepali-font"
                    >
                        <span x-text="category.name"></span>
                    </button>
                </template>
            </div>
            <!-- Dish Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="dish in filteredDishes" :key="dish.id">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                        <img :src="dish.image" :alt="dish.name" class="w-full h-48 object-cover rounded-lg mb-4">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 nepali-font" x-text="dish.name"></h3>
                        <div class="flex justify-between items-center mt-4">
                            <div>
                                <span class="text-red-600 font-bold" x-text="'रु ' + dish.price"></span>
                                <span class="text-sm text-gray-500 ml-2" x-text="'(' + dish.spice_level + ' मसला)'"></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="number"
                                    x-model.number="dish.quantity"
                                    min="1"
                                    max="10"
                                    class="w-16 px-2 py-1 border rounded dark:bg-gray-700"
                                >
                                <button
                                    @click="addToCart(dish)"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors nepali-font"
                                >
                                    थप्नुहोस्
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <!-- Order Summary Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 h-fit sticky top-4">
            <h2 class="text-2xl font-bold mb-4 nepali-font border-b pb-2">अर्डर सारांश</h2>
            <!-- Selected Items -->
            <template x-for="(item, index) in cart" :key="index">
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <h4 class="font-medium" x-text="item.name"></h4>
                        <span class="text-sm text-gray-500" x-text="'मात्रा: ' + item.quantity"></span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span x-text="'रु ' + (item.price * item.quantity)"></span>
                        <button
                            @click="removeFromCart(index)"
                            class="text-red-500 hover:text-red-700"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
            <!-- Totals -->
            <div class="mt-4 space-y-2">
                <div class="flex justify-between font-bold">
                    <span>जम्मा:</span>
                    <span x-text="'रु ' + cartTotal"></span>
                </div>
            </div>
            <!-- Checkout Form -->
            <form @submit.prevent="submitOrder" class="mt-6 space-y-4">
                <div>
                    <label class="block mb-2 nepali-font">विशेष निर्देशनहरू:</label>
                    <textarea
                        x-model="specialInstructions"
                        class="w-full p-2 border rounded dark:bg-gray-700"
                        rows="3"
                        placeholder="उदाहरण: धेरै मसला नहाल्नुहोस्, प्याकिङ्ग चाहिन्छ..."
                    ></textarea>
                </div>
                <div>
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
                <button
                    type="submit"
                    :disabled="cart.length === 0"
                    class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors nepali-font"
                >
                    अर्डर पुष्टि गर्नुहोस्
                </button>
            </form>
        </div>
    </div>
    <!-- Order Tracking Modal -->
    <div x-show="showTrackingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4 nepali-font">अर्डर ट्र्याकिङ</h3>
            <div class="space-y-4">
                <template x-for="(status, index) in orderStatus" :key="index">
                    <div class="flex items-center space-x-3">
                        <div :class="{
                            'bg-red-500': status.active,
                            'bg-gray-300 dark:bg-gray-600': !status.active
                        }" class="w-3 h-3 rounded-full"></div>
                        <span
                            :class="status.active
                                ? 'text-gray-900 dark:text-white'
                                : 'text-gray-500'"
                            x-text="status.label"
                        ></span>
                    </div>
                </template>
            </div>
            <button
                @click="showTrackingModal = false"
                class="mt-6 w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 py-2 rounded-lg nepali-font"
            >
                बन्द गर्नुहोस्
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
        dishes: @json($dishes),
        activeCategory: 0,
        cart: [],
        specialInstructions: '',
        selectedPayment: 'cash',
        showTrackingModal: false,
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
            return this.dishes.filter(dish =>
                dish.category_id === this.categories[this.activeCategory].id
            );
        },
        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },
        // Order Status Timeline
        orderStatus: [
            { label: 'अर्डर प्राप्त भयो', active: true },
            { label: 'तयारीमा', active: false },
            { label: 'बाटोमा', active: false },
            { label: 'पूरा भयो', active: false }
        ],
        // Methods
        addToCart(dish) {
            if (dish.quantity < 1) return;
            const existing = this.cart.find(item => item.id === dish.id);
            if (existing) {
                existing.quantity += dish.quantity;
            } else {
                this.cart.push({
                    id: dish.id,
                    name: dish.name,
                    price: dish.price,
                    quantity: dish.quantity
                });
            }
            dish.quantity = 1; // Reset quantity input
        },
        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        async submitOrder() {
            try {
                const response = await fetch("{{ route('orders.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        items: this.cart,
                        special_instructions: this.specialInstructions,
                        payment_method: this.selectedPayment
                    })
                });
                const data = await response.json();
                if (response.ok) {
                    window.location.href = `/orders/track/${data.order_id}`;
                } else {
                    alert(data.message || 'अर्डर पेश गर्दा त्रुटि भयो');
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
