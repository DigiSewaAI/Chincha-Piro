@extends('layouts.app', ['title' => 'नयाँ अर्डर बनाउनुहोस्'])

@section('content')
<div class="container mx-auto py-12">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl nepali-font font-bold text-gray-800 mb-6">नयाँ अर्डर</h1>

        <!-- पकवान खोज्ने -->
        <div class="mb-6">
            <input x-model="searchQuery" type="text"
                   class="w-full nepali-font p-3 border rounded-lg"
                   placeholder="पकवान खोज्नुहोस्...">
        </div>

        <!-- पकवान श्रेणीहरू -->
        <template x-for="category in filteredCategories" :key="category.id">
            <div class="mb-8">
                <h2 class="text-xl nepali-font font-semibold mb-4 text-gray-700"
                    x-text="category.name_nepali"></h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="dish in category.dishes" :key="dish.id">
                        <div class="dish-card bg-gray-50 p-4 rounded-lg cursor-pointer hover:bg-blue-50 transition"
                             @click="addToCart(dish)">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="nepali-font font-medium text-lg"
                                        x-text="dish.name_nepali"></h3>
                                    <p class="text-gray-600 nepali-font"
                                       x-text="'रु ' + dish.price"></p>
                                </div>
                                <span class="text-blue-500">+ थप्नुहोस्</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <!-- अर्डर आइटमहरू -->
        <div class="mt-8 border-t pt-6">
            <h3 class="text-xl nepali-font font-semibold mb-4">तपाईंको अर्डर</h3>

            <template x-for="(item, index) in cart" :key="item.dish.id">
                <div class="flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h4 x-text="item.dish.name_nepali"
                            class="nepali-font font-medium"></h4>
                        <div class="flex items-center gap-2 mt-2">
                            <button @click="decrement(index)"
                                    class="px-3 py-1 bg-gray-200 rounded">-</button>
                            <input type="number" x-model="item.quantity"
                                   class="w-16 text-center border rounded"
                                   min="1" max="10">
                            <button @click="increment(index)"
                                    class="px-3 py-1 bg-gray-200 rounded">+</button>
                        </div>
                        <textarea x-model="item.instructions"
                                  class="w-full mt-2 p-2 nepali-font text-sm border rounded"
                                  placeholder="विशेष निर्देशन"></textarea>
                    </div>
                    <div class="text-right">
                        <p x-text="'रु ' + (item.dish.price * item.quantity).toFixed(2)"
                           class="font-medium"></p>
                        <button @click="removeItem(index)"
                                class="text-red-500 hover:text-red-700 mt-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </template>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="nepali-font font-bold text-lg">जम्मा रकम:</span>
                    <span x-text="'रु ' + totalAmount.toFixed(2)"
                          class="text-2xl font-bold text-blue-600"></span>
                </div>
            </div>
        </div>

        <!-- फारम सबमिट -->
        <form method="POST" action="{{ route('orders.store') }}" class="mt-8">
            @csrf

            <!-- Hidden field for cart items -->
            <input type="hidden" name="items" x-bind:value="JSON.stringify(cart.map(item => ({
                dish_id: item.dish.id,
                quantity: item.quantity,
                note: item.instructions
            })))">

            <!-- Customer Information -->
            @guest
            <div class="mb-6">
                <label class="block nepali-font mb-2">ग्राहकको नाम:</label>
                <input type="text" name="customer_name" required
                       class="w-full p-3 border rounded-lg">
            </div>
            @endguest

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block nepali-font mb-2">फोन नम्बर</label>
                    <input type="text" name="phone" required
                           class="w-full p-3 border rounded-lg"
                           placeholder="९८४१२३४५६७">
                </div>

                <div>
                    <label class="block nepali-font mb-2">भुक्तानी विधि</label>
                    <select name="payment_method" required
                            class="w-full p-3 border rounded-lg">
                        <option value="cash">नगद</option>
                        <option value="esewa">eSewa</option>
                        <option value="khalti">खल्ती</option>
                        <option value="card">कार्ड</option>
                    </select>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="mt-6">
                <label class="block nepali-font mb-2">ठेगाना</label>
                <textarea name="address" required
                          class="w-full p-3 border rounded-lg"
                          rows="3"></textarea>
            </div>

            <!-- Additional Options -->
            <div class="mt-6">
                <label class="block nepali-font mb-2">विशेष निर्देशनहरू:</label>
                <textarea name="special_instructions"
                          class="w-full p-3 border rounded-lg"
                          rows="3"
                          placeholder="उदाहरण: धेरै मसला नहाल्नुहोस्"></textarea>
            </div>

            <div class="mt-6">
                <label class="block nepali-font mb-2">प्राथमिक डेलिभरी समय:</label>
                <input type="datetime-local" name="preferred_delivery_time"
                       class="w-full p-3 border rounded-lg">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="mt-6 w-full bg-green-500 hover:bg-green-600 text-white nepali-font py-3 rounded-lg text-lg">
                <i class="fas fa-paper-plane mr-2"></i> अर्डर पेश गर्नुहोस्
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function orderSystem() {
    return {
        searchQuery: '',
        categories: @json($categories->load('dishes')),
        cart: [],

        get filteredCategories() {
            // Avoid mutating original data
            return this.categories
                .map(category => ({
                    ...category,
                    dishes: category.dishes.filter(dish =>
                        dish.name_nepali.toLowerCase().includes(this.searchQuery.toLowerCase())
                    )
                }))
                .filter(cat => cat.dishes.length > 0);
        },

        addToCart(dish) {
            const existing = this.cart.find(item => item.dish.id === dish.id);
            if(existing) {
                existing.quantity++;
            } else {
                this.cart.push({
                    dish: dish,
                    quantity: 1,
                    instructions: ''
                });
            }
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        increment(index) {
            if(this.cart[index].quantity < 10) this.cart[index].quantity++;
        },

        decrement(index) {
            if(this.cart[index].quantity > 1) this.cart[index].quantity--;
        },

        get totalAmount() {
            return this.cart.reduce((sum, item) =>
                sum + (item.dish.price * item.quantity), 0);
        }
    }
}
</script>
@endpush
@endsection
