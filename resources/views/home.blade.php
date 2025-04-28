@extends('layouts.app')
@section('title', 'Home')
@section('content')
  <header class="relative">
    <img src="/images/hero.jpg" alt="Hero" class="w-full h-64 object-cover rounded-lg shadow">
    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
      <h1 class="text-white text-4xl font-extrabold">स्वागत छ! Chincha Piro मा तपाईलाई स्वागत छ</h1>
    </div>
  </header>

  <section class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Example: signature dishes -->
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
      <img src="/images/dish1.jpg" class="h-40 w-full object-cover rounded" alt="Dish">
      <h3 class="mt-4 text-xl font-semibold">Signature Dish 1</h3>
      <p class="mt-2 text-gray-600">चिप्ला, मसालेदार स्वाद र ताजा सामग्रीको मज्जा लिनुहोस्।</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
      <img src="/images/dish2.jpg" class="h-40 w-full object-cover rounded" alt="Dish">
      <h3 class="mt-4 text-xl font-semibold">Signature Dish 2</h3>
      <p class="mt-2 text-gray-600">चिल्ली पनिर र सुख्खा मसालासँग तयार गरिएको स्वादिष्ट पकवान।</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition">
      <img src="/images/dish3.jpg" class="h-40 w-full object-cover rounded" alt="Dish">
      <h3 class="mt-4 text-xl font-semibold">Signature Dish 3</h3>
      <p class="mt-2 text-gray-600">ताजा तरकारी र हाम्रो विशेष ग्रेवीमा पकाएको पकवान।</p>
    </div>
  </section>
@endsection
