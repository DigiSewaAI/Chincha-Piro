<!DOCTYPE html>
<html lang="ne" x-data="{ sidebarOpen: true, darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="() => { if (darkMode) document.documentElement.classList.add('dark'); }"
      :class="{'dark': darkMode}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>चिञ्‍चा पिरो - @yield('title')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Heroicons -->
    <script src="https://unpkg.com/@heroicons/v2/24/outline/index.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: all 0.3s ease; }

        @font-face {
            font-family: 'Preeti';
            src: url('/fonts/preeti.ttf') format('truetype');
        }
        .nepali-font { font-family: 'Preeti', 'Mangal', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900" x-cloak>
    <div class="flex h-screen overflow-hidden">
        <!-- साइडबार -->
        <aside class="sidebar-transition bg-white dark:bg-gray-800 shadow-lg z-20"
               :class="sidebarOpen ? 'w-64' : 'w-16'">
            <!-- साइडबार हेडर -->
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <span x-show="sidebarOpen" class="text-xl font-bold text-red-600 dark:text-red-400 nepali-font">
                    चिञ्‍चा पिरो
                </span>
                <button @click="sidebarOpen = !sidebarOpen" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            <!-- नेभिगेसन मेनु -->
            <nav class="mt-4">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="sidebarOpen" class="flex-1 nepali-font">मुख्य पृष्ठ</span>
                </a>
                @foreach ([
                    ['route' => 'dashboard', 'label' => 'ड्यासबोर्ड', 'icon' => 'chart-bar'],
                    ['route' => 'menu', 'label' => 'मेनु व्यवस्थापन', 'icon' => 'book-open'],
                    ['route' => 'gallery', 'label' => 'फोटो ग्यालरी', 'icon' => 'photo'],
                    ['route' => 'contact', 'label' => 'सम्पर्क जानकारी', 'icon' => 'phone'],
                ] as $item)
                <a href="{{ route($item['route']) }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($item['icon'] == 'chart-bar')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        @elseif($item['icon'] == 'book-open')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        @elseif($item['icon'] == 'photo')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        @elseif($item['icon'] == 'phone')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        @endif
                    </svg>
                    <span x-show="sidebarOpen" class="flex-1 nepali-font">{{ $item['label'] }}</span>
                </a>
                @endforeach
            </nav>
        </aside>
        <!-- मुख्य कन्टेन्ट -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- टप हेडर -->
            <header class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="md:hidden flex items-center text-red-600 dark:text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 nepali-font">@yield('title')</h1>
                </div>
                <!-- सामाजिक सञ्जाल र डार्क मोड -->
                <div class="flex items-center space-x-6">
                    <div class="flex space-x-4">
                        <a href="https://facebook.com/chinchapiro" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com/chinchapiro" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-pink-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                    </div>
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!darkMode" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            <path x-show="darkMode" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                        </svg>
                    </button>
                </div>
            </header>
            <!-- मुख्य कन्टेन्ट -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
                <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-6 nepali-font">
                    <a href="{{ route('home') }}" class="hover:text-red-600">मुख्य पृष्ठ</a>
                    <span>/</span>
                    <span class="text-gray-600 dark:text-gray-300">@yield('title')</span>
                </div>
                @yield('content')
            </main>
        </div>
    </div>

    <!-- WhatsApp बटन -->
<a href="https://wa.me/9779846216711"
target="_blank"
class="fixed bottom-5 right-5 bg-green-600 hover:bg-green-700 text-white p-4 rounded-full shadow-xl transition-all duration-300 hover:scale-110"
aria-label="WhatsApp us">
<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30">
    <path fill="#25D366" d="M15 0a15 15 0 1 0 0 30 15 15 0 0 0 0-30"/>
    <path fill="#FFF" d="M6.6 23.3l1.1-4.1a9.6 9.6 0 0 1-1.3-4.8c0-5.3 4.3-9.6 9.6-9.6 2.6 0 5 1 6.8 2.8a9.5 9.5 0 0 1 2.8 6.8c0 5.3-4.3 9.6-9.6 9.6h-0.1a9.6 9.6 0 0 1-4.6-1.2l-4.2 1.1 1.1-4z"/>
    <path fill="#FFF" d="M12.3 10.5l-1.9-.3c-.6 0-1.1.3-1.4.8-0.3.5-1.1 1.8-1.1 3.5 0 1.7.7 3.3 1.3 4.4 0.6 1.1 2.1 2.4 4.4 2.4 1.8 0 2.8-0.7 3.2-1.1 0.4-0.4 0.7-1 0.7-1.6 0-0.3-0.1-0.5-0.3-0.7l-1.1-1.1c-0.2-0.2-0.5-0.3-0.7-0.3-0.1 0-0.3 0-0.4 0.1l-1.6 0.9c-0.2 0.1-0.4 0.1-0.6-0.1-0.5-0.5-1-1.2-1.3-1.8-0.1-0.2-0.1-0.4 0.1-0.6l0.6-0.7c0.2-0.2 0.3-0.4 0.3-0.7 0-0.3-0.1-0.5-0.3-0.7l-0.6-0.6c-0.2-0.2-0.5-0.3-0.7-0.3-0.3 0-0.6 0.2-0.8 0.4-0.5 0.5-0.9 1.3-0.9 2.1 0 0.8 0.3 1.6 0.8 2.3 0.5 0.7 1.2 1.3 2.1 1.6 0.9 0.3 1.8 0.4 2.7 0.1 0.9-0.3 1.6-0.9 2.1-1.7 0.5-0.8 0.8-1.8 0.8-2.8 0-1.7-0.7-3.3-2-4.5-1.3-1.2-3-1.9-4.8-1.9z"/>
</svg>
</a>
