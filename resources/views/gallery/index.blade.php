@extends('layouts.app')

@section('title', 'फोटो ग्यालरी - Chincha Piro')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-4xl font-extrabold text-center text-orange-600 mb-8 nepali-font">
        हाम्रो फोटो तथा भिडियो ग्यालरी
    </h1>

    <!-- Alert -->
    <div class="bg-blue-100 border border-blue-300 text-blue-800 text-sm rounded-md p-4 mb-6 shadow-sm">
        कृपया नेपाली फन्ट सक्रिय गर्नुहोस् ताकि सबै पाठ सही रूपमा प्रदर्शित होस्।
    </div>

    <!-- Featured Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        @if(isset($featuredItems) && $featuredItems->isNotEmpty())
            @foreach($featuredItems as $item)
                <div class="relative overflow-hidden rounded-xl group shadow-md">
                    @if($item->type === 'photo')
                        <img src="{{ asset('storage/' . $item->image_path) }}"
                             alt="{{ $item->title }}"
                             class="object-cover w-full h-80 group-hover:scale-110 transition-transform duration-500">
                    @else
                        <iframe src="{{ $item->image_path }}"
                                title="{{ $item->title }}"
                                class="w-full h-80 group-hover:scale-110 transition-transform duration-500"
                                allowfullscreen></iframe>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-4 text-white">
                            <h2 class="text-2xl font-bold">{{ $item->title }}</h2>
                            <p class="text-sm mt-1 nepali-font">{{ $item->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-full text-center py-12">
                <p class="text-lg text-gray-600 nepali-font">फिचर्ड आइटम उपलब्ध छैनन्।</p>
            </div>
        @endif
    </section>

    <!-- Gallery Items -->
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($galleries as $item)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden group dark:bg-gray-800">
            @if($item->type === 'photo')
                <div class="relative">
                    <img src="{{ asset('storage/' . $item->image_path) }}"
                         alt="{{ $item->title }}"
                         class="w-full h-60 object-cover group-hover:scale-110 transition-transform duration-300">

                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <button onclick="openLightbox('{{ asset('storage/' . $item->image_path) }}')"
                                class="text-white bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-md font-medium transition">
                            हेर्नुहोस्
                        </button>
                    </div>
                </div>
            @elseif($item->type === 'video')
                <div class="aspect-video">
                    <iframe src="{{ $item->image_path }}"
                            title="{{ $item->title }}"
                            class="w-full h-full rounded-t-md"
                            allowfullscreen></iframe>
                </div>
            @endif

            <!-- Meta Info -->
            <div class="p-3 border-t dark:border-gray-700">
                <p class="font-semibold text-lg text-gray-800 dark:text-gray-200 nepali-font">{{ $item->title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 nepali-font capitalize">{{ $item->type }}</p>
                @if(!empty($item->description))
                <p class="text-sm mt-1 text-gray-600 dark:text-gray-400 nepali-font">{{ $item->description }}</p>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-lg text-gray-600 nepali-font">हाललाई कुनै ग्यालरी आइटम उपलब्ध छैन।</p>
        </div>
        @endforelse
    </section>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $galleries->links() }}
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox"
         class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4"
         onclick="closeLightbox()">
        <div class="relative max-w-4xl w-full">
            <img id="lightbox-img" src="" alt="Full Size Image" class="w-full h-auto rounded-lg shadow-lg">
            <button class="absolute top-4 right-4 bg-white/20 hover:bg-white/30 text-white p-2 rounded-full transition">
                ✕
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openLightbox(src) {
        const modal = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        img.src = src;
        modal.classList.remove('hidden');
    }

    function closeLightbox() {
        const modal = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        modal.classList.add('hidden');
        img.src = '';
    }

    // Escape key handler
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            closeLightbox();
        }
    });

    @if(session('success'))
        window.addEventListener('DOMContentLoaded', () => {
            alert("{{ session('success') }}");
        });
    @endif
</script>
@endsection
