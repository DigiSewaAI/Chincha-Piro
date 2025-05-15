@extends('layouts.app', ['title' => 'नयाँ अर्डर बनाउनुहोस्'])

@section('content')
<div class="container mx-auto py-12">
    <h1 class="text-3xl font-bold mb-6 nepali-font">नयाँ अर्डर बनाउनुहोस्</h1>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-2 nepali-font">पकवान चयन गर्नुहोस्:</label>
            <select name="items[0][dish_id]" class="w-full border rounded p-2" required>
                @foreach($dishes as $dish)
                    <option value="{{ $dish->id }}">{{ $dish->name }} (रु {{ $dish->price }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 nepali-font">मात्रा:</label>
            <input type="number" name="items[0][quantity]" min="1" max="10" value="1" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-2 nepali-font">भुक्तानी विधि:</label>
            <select name="payment_method" class="w-full border rounded p-2">
                <option value="cash">नगद</option>
                <option value="esewa">ईसेवा</option>
                <option value="khalti">खल्ती</option>
                <option value="card">कार्ड</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 nepali-font">ठेगाना:</label>
            <input type="text" name="address" placeholder="ठेगाना प्रविष्ट गर्नुहोस्" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-2 nepali-font">फोन नम्बर:</label>
            <input type="text" name="phone" placeholder="98XXXXXXXX प्रारूपमा" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-2 nepali-font">विशेष निर्देशनहरू:</label>
            <textarea name="special_instructions" rows="3" class="w-full border rounded p-2" placeholder="उदाहरण: धेरै मसला नहाल्नुहोस्"></textarea>
        </div>

        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg nepali-font">
            अर्डर पेश गर्नुहोस्
        </button>
    </form>
</div>
@endsection