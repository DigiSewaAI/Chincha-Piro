@extends('layouts.app') <!-- मुख्य layout लोड गर्दछ -->

@section('title', 'अर्डर स्थिति') <!-- पृष्ठको शीर्षक -->

@section('content')
<!-- Container -->
<div class="container mx-auto p-6 text-center">
    <!-- शीर्षक -->
    <h2 class="text-2xl font-bold nepali-font mb-4">अर्डर स्थिति</h2>

    <!-- अर्डर ID र ग्राहकको नाम -->
    <p class="nepali-font mb-2">अर्डर #{{ $order->id }}</p>
    <p class="nepali-font mb-4">ग्राहक: {{ $order->customer_name }}</p>

    <!-- अर्डरको स्थिति (pending, confirmed, completed, cancelled) -->
    <div class="mb-6">
        @switch($order->status)
            @case('pending')
                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded nepali-font">पुष्टि हुन बाँकी</span>
                @break
            @case('confirmed')
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded nepali-font">पुष्टि भएको</span>
                @break
            @case('completed')
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded nepali-font">पूरा भएको</span>
                @break
            @case('cancelled')
                <span class="px-4 py-2 bg-red-100 text-red-800 rounded nepali-font">रद्द गरिएको</span>
                @break
            @default
                <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded nepali-font">अज्ञात</span>
        @endswitch
    </div>

    <!-- QR कोड -->
    <div class="mt-6">
        {!! QrCode::size(150)->generate(route('orders.track', $order->id)) !!}
        <p class="nepali-font mt-2">यो QR कोड स्क्यान गरेर अर्डर स्थिति हेर्नुहोस्</p>
    </div>
</div>
@endsection