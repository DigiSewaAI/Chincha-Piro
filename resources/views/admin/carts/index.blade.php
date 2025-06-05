@extends('layouts.admin')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">कार्ट गतिविधि</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">उपयोगकर्ता</th>
                    <th class="px-6 py-3 text-left">आइटमहरू</th>
                    <th class="px-6 py-3 text-left">कुल</th>
                    <th class="px-6 py-3 text-left">अद्यावधिक गरिएको</th>
                    <th class="px-6 py-3 text-right">क्रियाहरू</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($carts as $cart)
                <tr>
                    <td class="px-6 py-4">{{ $cart->user?->name ?? 'अतिथि' }}</td>
                    <td class="px-6 py-4">{{ $cart->items->count() }} आइटम</td>
                    <td class="px-6 py-4">रु {{ number_format($cart->items->sum(fn($i) => $i->price * $i->quantity) * 1.13, 2) }}</td>
                    <td class="px-6 py-4">{{ $cart->updated_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.carts.show', $cart->id) }}" class="text-blue-600 hover:text-blue-800">हेर्नुहोस्</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $carts->links() }}
    </div>
</div>
@endsection
