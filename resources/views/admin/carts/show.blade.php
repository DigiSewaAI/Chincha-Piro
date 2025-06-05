@extends('layouts.admin')
@section('title', "कार्ट #{$cart->id} - एडमिन")

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- 📦 कार्ट विवरण -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">कार्ट #{{ $cart->id }}</h1>
            <span class="text-sm text-gray-500">अद्यावधिक गरिएको: {{ $cart->updated_at->format('M d, Y H:i') }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-600 mb-2">प्रयोगकर्ता</h3>
                <p class="text-lg">{{ $cart->user?->name ?? 'अतिथि' }}</p>
                <p class="text-sm text-gray-500">{{ $cart->user?->email ?? 'अतिथि' }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-600 mb-2">आइटमहरू</h3>
                <p class="text-lg">{{ $cart->items->sum('quantity') }} आइटमहरू</p>
                <p class="text-sm text-gray-500">उपलब्ध आइटमहरूको सूची</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-600 mb-2">कुल मूल्य</h3>
                <p class="text-lg">रु {{ number_format($analysis['total_value'], 2) }}</p>
                <p class="text-sm text-gray-500">ट्याक्स सहित: रु {{ number_format($analysis['total_with_tax'], 2) }}</p>
            </div>
        </div>

        <!-- 📦 कार्ट आइटमहरू -->
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">कार्ट आइटमहरू</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">आइटम</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">मूल्य</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">मात्रा</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">कुल</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($cart->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->menu->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">रु {{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">रु {{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center">कुनै आइटम छैन</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-100">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-bold">कुल:</td>
                            <td class="px-6 py-3 font-bold">रु {{ number_format($analysis['total_value'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-bold">ट्याक्स (13%):</td>
                            <td class="px-6 py-3 font-bold">रु {{ number_format($analysis['tax_amount'], 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-bold">कुल (ट्याक्स सहित):</td>
                            <td class="px-6 py-3 font-bold text-lg">रु {{ number_format($analysis['total_with_tax'], 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
