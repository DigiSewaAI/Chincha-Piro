@extends('layouts.app')

@section('title', 'Our Menu')

@section('content')
<section class="py-12 bg-gray-50">
  <div class="container mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-12">हाम्रो मेनु</h2>
    <div class="grid md:grid-cols-3 gap-8">
      @forelse($menus as $item)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
          <img src="{{ asset('storage/' . $item->image) }}"
               alt="{{ $item->name }}"
               class="w-full h-48 object-cover object-center">
          <div class="p-6">
            <h3 class="text-2xl font-semibold mb-2">{{ $item->name }}</h3>
            <p class="text-gray-600 mb-4">{{ $item->description }}</p>
            <span class="text-lg font-bold text-red-600">रु {{ number_format($item->price, 2) }}</span>
          </div>
        </div>
      @empty
        <p class="text-center text-gray-500 col-span-3">मेनु आइटमहरू उपलब्ध छैनन्।</p>
      @endforelse
    </div>
  </div>
</section>
@endsection
