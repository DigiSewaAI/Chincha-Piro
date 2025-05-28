<!DOCTYPE html>
<html lang="ne"
      x-data="{ sidebarOpen: true, darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="() => { if (darkMode) document.documentElement.classList.add('dark'); }"
      :class="{'dark': darkMode}">
<head>
    @livewireStyles

    <!-- ✅ Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ @fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    <!-- 🌐 Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 🎯 Title -->
    <title>चिञ्‍चा पिरो - @yield('title')</title>

    <!-- 💧 Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com "></script>

    <!-- 🌩 Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs @3.x.x/dist/cdn.min.js"></script>

    <!-- 📚 Nepali Unicode Font (Google Fonts) -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap " rel="stylesheet">

    <!-- 🎨 Main CSS with Unicode Font -->
    <style>
        /* 🧠 Nepali Font Class (Preeti हटाइएको, केवल Unicode Fonts प्रयोग भएको) */
        .nepali-font {
            font-family: 'Noto Sans Devanagari', 'Lohit Devanagari', 'Mangal', sans-serif;
        }

        /* 🔄 Sidebar Transition */
        .sidebar-transition {
            transition: all 0.3s ease;
        }

        /* 🧠 Hide Alpine.js Elements Until Ready */
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans" x-cloak>
    <div class="flex h-screen overflow-hidden">
        <!-- 🧭 Sidebar -->
        <aside class="sidebar-transition bg-white dark:bg-gray-800 shadow-lg z-20"
               :class="sidebarOpen ? 'w-64' : 'w-16'">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <span x-show="sidebarOpen" class="text-xl font-bold text-red-600 dark:text-red-400 nepali-font">
                    चिञ्‍चा पिरो
                </span>
                <button @click="sidebarOpen = !sidebarOpen" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-4">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="sidebarOpen" class="flex-1 nepali-font">मुख्य पृष्ठ</span>
                </a>

                @foreach ([
                    ['route' => 'dashboard', 'label' => 'ड्यासबोर्ड', 'icon' => 'chart-bar'],
                    ['route' => 'menu.index', 'label' => 'मेनु व्यवस्थापन', 'icon' => 'book-open'],
                    ['route' => 'admin.gallery.index', 'label' => 'फोटो ग्यालरी', 'icon' => 'photo'],
                    ['route' => 'contact', 'label' => 'सम्पर्क जानकारी', 'icon' => 'phone'],
                    ['route' => 'reservations.index', 'label' => 'रिजर्भेसन', 'icon' => 'calendar']
                ] as $item)
                <a href="{{ route($item['route']) }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 group transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @switch($item['icon'])
                            @case('chart-bar')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                @break
                            @case('book-open')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                @break
                            @case('photo')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                @break
                            @case('phone')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                @break
                            @case('calendar')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                @break
                        @endswitch
                    </svg>
                    <span x-show="sidebarOpen" class="flex-1 nepali-font">{{ $item['label'] }}</span>
                </a>
                @endforeach
            </nav>
        </aside>

        <!-- 🖥️ Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- 🔝 Header -->
            <header class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="md:hidden flex items-center text-red-600 dark:text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 nepali-font">@yield('title')</h1>
                </div>

                <!-- 🌙 Dark Mode Toggle + Social Icons -->
                <div class="flex items-center space-x-6">
                    <div class="flex space-x-4">
                        <a href="https://facebook.com/chinchapiro " target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com/chinchapiro " target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-pink-600 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.78-2.618 6.98-6.98.057-1.28.072-1.689.072-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.618-6.78-6.98-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                        <a href="https://tiktok.com/ @chinchapiro" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-gray-100 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.589 6.686a4.793 4.793 0 01-3.77-4.245V2H12.374v13.672a2.896 2.896 0 01-5.201 1.743l.002.001a2.895 2.895 0 013.183-4.51V8.687a6.329 6.329 0 00-5.394 10.692 6.33 6.33 0 0010.857-4.424V6.79a4.831 4.831 0 01-3.77-1.105z"/>
                            </svg>
                        </a>
                    </div>

                    <!-- 🌙 Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300"
                            aria-label="डार्क मोड स्विच गर्नुहोस्">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!darkMode" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            <path x-show="darkMode" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- 📄 Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
                <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-6 nepali-font">
                    <a href="{{ route('home') }}" class="hover:text-red-600 transition-colors">मुख्य पृष्ठ</a>
                    <span>/</span>
                    <span class="text-gray-600 dark:text-gray-300">@yield('title')</span>
                </div>

                <!-- 🧱 Page Content -->
                @yield('content')

                <!-- 📄 Footer -->
                <footer class="mt-8 pt-6 border-t dark:border-gray-700">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        <!-- Address -->
                        <div class="text-center md:text-left">
                            <h3 class="nepali-font text-lg font-bold text-red-600">हाम्रो ठेगाना</h3>
                            <p class="dark:text-gray-300">काठमाडौँ-३२, तिनकुने<br>गैरिगाउ, चिञ्‍चा पिरो भवन</p>
                        </div>

                        <!-- Social Media -->
                        <div class="flex flex-col items-center">
                            <h3 class="nepali-font text-lg font-bold text-red-600 mb-2">सामाजिक सञ्जाल</h3>
                            <div class="flex space-x-4">
                                <a href="https://facebook.com/chinchapiro " target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition-colors">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="https://instagram.com/chinchapiro " target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-pink-600 transition-colors">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.78-2.618 6.98-6.98.057-1.28.072-1.689.072-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.618-6.78-6.98-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                    </svg>
                                </a>
                                <a href="https://tiktok.com/ @chinchapiro" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-gray-100 transition-colors">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.589 6.686a4.793 4.793 0 01-3.77-4.245V2H12.374v13.672a2.896 2.896 0 01-5.201 1.743l.002.001a2.895 2.895 0 013.183-4.51V8.687a6.329 6.329 0 00-5.394 10.692 6.33 6.33 0 0010.857-4.424V6.79a4.831 4.831 0 01-3.77-1.105z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="text-center md:text-right">
                            <h3 class="nepali-font text-lg font-bold text-red-600 mb-2">सम्पर्क</h3>
                            <p class="dark:text-gray-300">
                                📞 ०१-४११२४४८<br>
                                📱 ९८४६२१६७११
                            </p>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- 📞 WhatsApp Button -->
    <a href="https://wa.me/9779846216711 " target="_blank"
       class="fixed bottom-5 right-5 bg-green-600 hover:bg-green-700 text-white p-4 rounded-full shadow-xl transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm.01 1.67c2.33 0 4.52.91 6.17 2.56a8.677 8.677 0 012.55 6.17c0-4.84-3.94-8.78-8.78-8.78-1.48 0-2.93-.37-4.19-1.07l-.3-.15-3.12.82.83-3.04-.18-.28c-.76-1.15-1.17-2.49-1.17-3.88 0-4.84 3.94-8.78 8.78-8.78"/>
        </svg>
    </a>

    <!-- 📊 Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js "></script>

    <!-- ✅ Fancybox JS -->
    <script src="https://cdn.jsdelivr.net/npm/ @fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <!-- 📷 Initialize Fancybox -->
    <script>
        Fancybox.bind("[data-fancybox]", {
            Thumbs: false,
            Toolbar: true,
            Infobar: true,
            Buttons: ["zoom", "slideShow", "fullScreen", "download", "close"],
            Carousel: { infinite: true },
            Video: { autoStart: true }
        });
    </script>

    @livewireScripts
</body>
</html>
