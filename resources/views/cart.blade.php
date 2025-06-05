@extends('layouts.app')
@section('title', 'कार्ट - Chincha Piro')
@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-red-600 mb-8">तपाईंको कार्ट</h1>

    @if($cartItems->isEmpty())
        <div class="text-center py-16 bg-gray-50 rounded-lg shadow-md">
            <p class="text-gray-600 text-xl mb-4">तपाईंको कार्ट खाली छ</p>
            <a href="{{ route('menu.index') }}"
               class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-300">
                मेनु हेर्नुहोस्
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- कार्ट आइटमहरू -->
            <div class="lg:col-span-2 space-y-6">
                @foreach($cartItems as $item)
                <div class="bg-white p-4 rounded-lg shadow-md flex items-center hover:shadow-lg transition duration-300">
                    <!-- आइटम छवि -->
                    <img src="{{ $item->menu->image ? asset('storage/'.$item->menu->image) : asset('images/placeholder.jpg') }}"
                         alt="{{ $item->menu->name }}"
                         class="w-20 h-20 object-cover rounded mr-4">

                    <!-- आइटम विवरण -->
                    <div class="flex-grow">
                        <h3 class="font-semibold text-lg">{{ $item->menu->name }}</h3>
                        <p class="text-gray-600">रु {{ number_format($item->price, 2) }} x {{ $item->quantity }}</p>
                    </div>

                    <!-- क्रियाहरू -->
                    <div class="flex items-center space-x-4">
                        <!-- मात्रा अद्यावधिक -->
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PUT')
                            <input type="number"
                                   name="quantity"
                                   value="{{ $item->quantity }}"
                                   min="1"
                                   max="20"
                                   class="w-16 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                            <button type="submit" class="ml-2 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-sync"></i>
                            </button>
                        </form>

                        <!-- आइटम हटाउनुहोस् -->
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- कार्ट सारांश -->
            <div class="bg-gray-50 p-6 rounded-lg shadow-md sticky top-24">
                <h2 class="text-xl font-bold mb-4">कार्ट सारांश</h2>

                <!-- उप-कुल -->
                <div class="border-t pt-4">
                    <div class="flex justify-between mb-2">
                        <span>उप-कुल:</span>
                        <span>रु {{ number_format($cartItems->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                    </div>

                    <!-- ट्याक्स (13%) -->
                    <div class="flex justify-between mb-2">
                        <span>ट्याक्स (13%):</span>
                        <span>रु {{ number_format($cartItems->sum(fn($i) => $i->price * $i->quantity) * 0.13, 2) }}</span>
                    </div>

                    <!-- कुल रकम -->
                    <div class="flex justify-between font-bold text-lg mt-2 pt-2 border-t">
                        <span>कुल:</span>
                        <span>रु {{ number_format($cartItems->sum(fn($i) => $i->price * $i->quantity) * 1.13, 2) }}</span>
                    </div>
                </div>

                <!-- चेकआउट बटन -->
                <a href="{{ route('orders.create') }}"
                   class="mt-6 block bg-red-600 text-white text-center py-3 rounded-lg hover:bg-red-700 transition duration-300">
                    अर्डर पेश गर्नुहोस्
                </a>

                <!-- कार्ट सफा गर्नुहोस् -->
                <form action="{{ route('cart.clear') }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 transition duration-300"
                            onclick="return confirm('के तपाईं कार्ट सफा गर्न निश्चित हुनुहुन्छ?')">
                        कार्ट सफा गर्नुहोस्
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
