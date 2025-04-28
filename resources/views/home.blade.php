@extends('layouts.app')

@section('title', 'Home')

@section('content')
  <!-- Hero Section -->
  <section class="relative overflow-hidden h-[600px]">
    <img src="/images/hero-dish.jpg" alt="Hero Dish" class="w-full h-full object-cover object-top">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-6">
      <h1 class="text-4xl md:text-6xl font-bold mb-4">स्वादको नयाँ अनुभूति - Chincha Piro</h1>
      <p class="text-lg md:text-2xl mb-6">तपाईंको स्वादलाई झन् रंगीन बनाउने हाम्रो मिशन</p>
      <a href="#menu" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-full text-lg">
        Menu हेर्नुहोस्
      </a>
    </div>
  </section>

  <!-- Signature Dishes Section -->
  <section id="menu" class="py-12 bg-gray-100">
    <div class="container mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12">हाम्रो Signature Dishes</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <!-- Dish 1 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
          <img src="/images/dish1.jpg" alt="Signature Dish 1" class="w-full h-48 object-cover object-center">
          <div class="p-6">
            <h3 class="text-2xl font-semibold mb-2">Signature Dish 1</h3>
            <p class="text-gray-600">चिप्ला, मसालेदार स्वाद र ताजा सामग्रीको मज्जा लिनुहोस्।</p>
          </div>
        </div>
        <!-- Dish 2 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
          <img src="/images/dish2.jpg" alt="Signature Dish 2" class="w-full h-48 object-cover object-center">
          <div class="p-6">
            <h3 class="text-2xl font-semibold mb-2">Signature Dish 2</h3>
            <p class="text-gray-600">चिल्ली पनिर र सुख्खा मसालासँग तयार गरिएको स्वादिष्ट पकवान।</p>
          </div>
        </div>
        <!-- Dish 3 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
          <img src="/images/dish3.jpg" alt="Signature Dish 3" class="w-full h-48 object-cover object-center">
          <div class="p-6">
            <h3 class="text-2xl font-semibold mb-2">Signature Dish 3</h3>
            <p class="text-gray-600">ताजा तरकारी र हाम्रो विशेष ग्रेवीमा पकाएको पकवान।</p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
