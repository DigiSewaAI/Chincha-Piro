<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chincha Piro - @yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <nav class="bg-white shadow mb-8">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-between items-center py-4">
        <a href="{{ route('home') }}" class="text-2xl font-bold text-purple-600">Chincha Piro</a>
        <div class="space-x-4">
          <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded hover:bg-purple-50">Dashboard</a>
          <a href="{{ route('menu') }}" class="px-3 py-2 rounded hover:bg-purple-50">Menu</a>
          <a href="{{ route('gallery') }}" class="px-3 py-2 rounded hover:bg-purple-50">Gallery</a>
          <a href="{{ route('contact') }}" class="px-3 py-2 rounded hover:bg-purple-50">Contact</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="container mx-auto px-4">
    @yield('content')
  </div>
</body>
</html>
