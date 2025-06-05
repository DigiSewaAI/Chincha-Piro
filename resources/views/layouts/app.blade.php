<!DOCTYPE html>
<html lang="ne"
      x-data="{ sidebarOpen: true, darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="() => { if (darkMode) document.documentElement.classList.add('dark'); }"
      :class="{'dark': darkMode}">
<head>
    @livewireStyles
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>चिञ्‍चा पिरो - @yield('title')</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Styles & Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <!-- Local Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
    <!-- Nepali Font & Cart Icon Style -->
    <style>
        .nepali-font {
            font-family: 'Noto Sans Devanagari', 'Lohit Devanagari', 'Mangal', sans-serif;
        }
        .cart-icon {
            font-size: 1.2rem;
            color: #333;
            position: relative;
            margin-left: 1rem;
        }
        #cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 0.8rem;
            min-width: 20px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans" x-cloak>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar-transition bg-white dark:bg-gray-800 shadow-lg z-20"
               :class="sidebarOpen ? 'w-64' : 'w-16'">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 rounded-full" />
                    <span x-show="sidebarOpen" class="text-xl font-bold text-red-600 dark:text-red-400 nepali-font">
                        चिञ्‍चा पिरो
                    </span>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
            <!-- Navigation -->
            <nav class="mt-4">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-home me-3"></i>
                    <span x-show="sidebarOpen" class="flex-1 nepali-font">मुख्य पृष्ठ</span>
                </a>
                @foreach ([
                    ['route' => 'dashboard', 'label' => 'ड्यासबोर्ड', 'icon' => 'chart-bar'],
                    ['route' => 'menu.index', 'label' => 'मेनु व्यवस्थापन', 'icon' => 'book-open'],
                    ['route' => 'admin.gallery.index', 'label' => 'फोटो ग्यालरी', 'icon' => 'photo'],
                    ['route' => 'contact', 'label' => 'सम्पर्क जानकारी', 'icon' => 'phone'],
                    ['route' => 'reservations.index', 'label' => 'रिजर्भेसन', 'icon' => 'calendar']
                ] as $item)
                    <a href="{{ route($item['route']) }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        @switch($item['icon'])
                            @case('chart-bar') <i class="fas fa-chart-bar me-3"></i> @break
                            @case('book-open') <i class="fas fa-book-open me-3"></i> @break
                            @case('photo') <i class="fas fa-photo-video me-3"></i> @break
                            @case('phone') <i class="fas fa-phone me-3"></i> @break
                            @case('calendar') <i class="fas fa-calendar me-3"></i> @break
                        @endswitch
                        <span x-show="sidebarOpen" class="flex-1 nepali-font">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="md:hidden flex items-center text-red-600 dark:text-red-400">
                        <i class="fas fa-home"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 nepali-font">@yield('title')</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="flex space-x-4">
                        <a href="https://facebook.com/chinchapiro"  target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://instagram.com/chinchapiro"  target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-pink-600"><i class="fab fa-instagram"></i></a>
                        <a href="https://tiktok.com/@chinchapiro"  target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-gray-100"><i class="fab fa-tiktok"></i></a>
                    </div>
                    <!-- ✅ Corrected Cart Route -->
                    <a href="{{ route('cart.index') }}" class="cart-icon">
                        🛒 <span id="cart-count">0</span>
                    </a>
                    <!-- 🌙 Theme Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300">
                        <i x-show="!darkMode" class="fas fa-sun text-gray-600 dark:text-gray-300"></i>
                        <i x-show="darkMode" class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
                    </button>
                </div>
            </header>
            <!-- Main -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
                <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-6 nepali-font">
                    <a href="{{ route('home') }}" class="hover:text-red-600">मुख्य पृष्ठ</a>
                    <span>/</span>
                    <span class="text-gray-600 dark:text-gray-300">@yield('title')</span>
                </div>
                @yield('content')
                <!-- Footer -->
                <footer class="mt-8 pt-6 border-t dark:border-gray-700">
                    <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="text-center md:text-left">
                            <h3 class="nepali-font text-lg font-bold text-red-600">हाम्रो ठेगाना</h3>
                            <p class="dark:text-gray-300">काठमाडौँ-३२, तिनकुने<br>गैरिगाउ, चिञ्‍चा पिरो भवन</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <h3 class="nepali-font text-lg font-bold text-red-600 mb-2">सामाजिक सञ्जाल</h3>
                            <div class="flex space-x-4">
                                <a href="https://facebook.com/chinchapiro"  target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://instagram.com/chinchapiro"  target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-pink-600"><i class="fab fa-instagram"></i></a>
                                <a href="https://tiktok.com/@chinchapiro"  target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-gray-100"><i class="fab fa-tiktok"></i></a>
                            </div>
                        </div>
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
    <!-- WhatsApp -->
    <a href="https://wa.me/9779846216711"  target="_blank"
       class="fixed bottom-5 right-5 bg-green-600 hover:bg-green-700 text-white p-4 rounded-full shadow-xl hover:scale-110 transition-transform">
        <i class="fab fa-whatsapp fa-lg"></i>
    </a>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
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
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
    @livewireScripts
    <!-- 🛒 कार्टको संख्या अपडेट गर्ने -->
    <script>
        $(document).ready(function () {
            // 📦 Order Now बटनको AJAX
            $('.order-now').on('click', function(e) {
                e.preventDefault(); // 🚫 पृष्ठ रिफ्रेस रोक्नुहोस्

                const itemId = $(this).data('id');
                const expectedPrice = $(this).data('price');

                $.ajax({
                    url: "{{ route('cart.add', '') }}/" + itemId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: 1,
                        expected_price: expectedPrice
                    },
                    success: function(response) {
                        $('#cart-count').text(response.cart_count); // 📦 कार्ट संख्या अपडेट
                        Toastify({
                            text: response.success,
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                    },
                    error: function(xhr) {
                        Toastify({
                            text: xhr.responseJSON.error || 'त्रुटि भयो',
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)"
                        }).showToast();
                    }
                });
            });

            // 📦 प्रारम्भिक कार्ट संख्या अपडेट
            function loadCartCount() {
                fetch("{{ route('cart.count') }}")
                    .then(res => res.json())
                    .then(data => {
                        $('#cart-count').text(data.count);
                    });
            }
            loadCartCount();
            setInterval(loadCartCount, 5000); // 🕒 प्रत्येक 5 सेकण्डमा
        });
    </script>
</body>
</html>
