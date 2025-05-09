@extends('layouts.app')

@section('title', 'फोटो ग्यालरी - Chincha Piro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center text-orange-600 mb-10">हाम्रो फोटो ग्यालरी</h1>

    <!-- Nepali Font Alert -->
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
        <p class="nepali-font">कृपया नेपाली फन्ट सक्रिय गर्नुहोस् ताकि सबै पाठ सही रूपमा प्रदर्शित होस्।</p>
    </div>

    <!-- Featured Images -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="relative group overflow-hidden rounded-xl shadow-lg">
            <img src="{{ asset('images/gallery/featured-1.jpg') }}"
                 alt="फिचर्ड पकवान"
                 class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold">हाम्रा लोकप्रिय पकवानहरू</h2>
                    <p class="mt-2 text-sm nepali-font">ताजा र स्वादिष्ट भोजनका केही उदाहरणहरू</p>
                </div>
            </div>
        </div>

        <div class="relative group overflow-hidden rounded-xl shadow-lg">
            <img src="{{ asset('images/gallery/featured-2.jpg') }}"
                 alt="रेस्टुरेन्ट दृश्य"
                 class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold">हाम्रो रेस्टुरेन्ट</h2>
                    <p class="mt-2 text-sm nepali-font">आरामदायक वातावरणमा खाना स्वाद लिनुहोस्</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($galleryItems as $item)
        <div class="group relative overflow-hidden rounded-lg shadow-md">
            <!-- Image -->
            <img src="{{ asset('storage/' . $item->image_path) }}"
                 alt="{{ $item->title }}"
                 class="w-full h-60 object-cover transition-transform duration-300 group-hover:scale-110">

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <button onclick="openLightbox('{{ asset('storage/' . $item->image_path) }}', '{{ $item->title }}')"
                        class="bg-white text-orange-600 px-4 py-2 rounded-lg font-medium flex items-center space-x-2 hover:bg-orange-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>हेर्नुहोस्</span>
                </button>
            </div>

            <!-- Title -->
            <div class="p-3 bg-white dark:bg-gray-800 border-t">
                <p class="font-medium text-gray-800 dark:text-gray-200 nepali-font">{{ $item->title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 nepali-font">{{ $item->category }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10">
            <p class="text-xl text-gray-500 nepali-font">ग्यालरीमा कुनै तस्बिर छैन।</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $galleryItems->links() }}
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 bg-black/90 hidden z-50 flex items-center justify-center p-4" onclick="closeLightbox()">
        <div class="relative max-w-4xl w-full">
            <img id="lightbox-img" src="" alt="Full Size Image" class="w-full h-auto rounded-lg">
            <button class="absolute top-4 right-4 text-white bg-black/50 p-2 rounded-full hover:bg-black transition">
                ✕
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openLightbox(src, title) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').classList.remove('hidden');
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.getElementById('lightbox-img').src = '';
    }

    // ESC key सँगै lightbox बन्द गर्नुहोस्
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closeLightbox();
    });

    // Toast Notification
    @if(session('success'))
        alert("{{ session('success') }}");
    @endif
</script>
@endsection
