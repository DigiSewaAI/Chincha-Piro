@extends('layouts.app')

@section('title', 'फोटो ग्यालरी')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="bg-white rounded-xl shadow-md p-6">
        <h1 class="text-4xl font-bold text-orange-600 mb-4">फोटो ग्यालरी</h1>
        <p class="text-gray-600 nepali-font mb-6">हाम्रो रेस्टुरेन्ट र कार्यक्रमहरूका झलकहरू हेर्नुहोस्।</p>

        @if($galleryItems->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($galleryItems as $item)
                    <div class="rounded overflow-hidden shadow-lg group bg-white dark:bg-gray-800">
                        @if($item->type === 'photo')
                            <img src="{{ asset('storage/' . $item->file_path) }}"
                                 alt="{{ $item->title }}"
                                 class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                        @elseif($item->type === 'video')
                            <div class="aspect-w-16 aspect-h-9">
                                <iframe src="{{ $item->file_path }}"
                                        title="{{ $item->title }}"
                                        allowfullscreen
                                        class="w-full h-full"></iframe>
                            </div>
                        @endif
                        <div class="p-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $item->title }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($item->type) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $galleryItems->links() }}
            </div>
        @else
            <p class="text-center text-gray-500 text-lg">हाललाई ग्यालरीमा कुनै सामग्री उपलब्ध छैन।</p>
        @endif
    </div>
</div>
@endsection
