@extends('layouts.app')

@section('title', 'फोटो ग्यालरी - Chincha Piro')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-4xl font-extrabold text-center text-red-600 mb-8 nepali-font">
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
                    @if($item->isPhoto() && $item->photo_url)
                        <a data-fancybox="featured-gallery" href="{{ $item->photo_url }}" data-caption="{{ $item->title }}">
                            <img src="{{ $item->photo_url }}" alt="{{ $item->title }}" class="object-cover w-full h-80 group-hover:scale-110 transition-transform duration-500 cursor-pointer">
                        </a>
                    @elseif($item->isLocalVideo() && $item->video_url)
                        <a data-fancybox data-type="video" href="{{ $item->video_url }}" data-caption="{{ $item->title }}">
                            <video class="w-full h-80 object-cover group-hover:scale-110 transition-transform duration-500" muted autoplay loop>
                                <source src="{{ $item->video_url }}" type="video/mp4">
                                तपाईको browser ले यो भिडियो support गर्दैन।
                            </video>
                        </a>
                    @elseif($item->isExternalVideo() && $item->video_embed_url)
                        <a data-fancybox data-type="iframe" data-src="{{ $item->video_embed_url }}" href="javascript:;" data-caption="{{ $item->title }}">
                            @php
                                $youtubeId = null;
                                $url = $item->video_url;
                                if (preg_match('%(?:youtube\.com/(?:embed/|v/|watch\?v=)|youtu\.be/)([\w-]{11})%', $url, $matches)) {
                                    $youtubeId = $matches[1];
                                }
                            @endphp

                            @if ($youtubeId)
                                <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg"
                                     alt="{{ $item->title }}"
                                     class="w-full h-80 object-cover group-hover:scale-110 transition-transform duration-500 cursor-pointer">
                            @else
                                <div class="w-full h-80 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">अमान्य YouTube URL</span>
                                </div>
                            @endif
                        </a>
                    @else
                        <div class="w-full h-80 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">कुनै मिडिया छैन</span>
                        </div>
                    @endif

                    <!-- Title & Description -->
                    @if($item->title || $item->description)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 text-white">
                                @if($item->title)
                                    <h2 class="text-2xl font-bold">{{ $item->title }}</h2>
                                @endif
                                @if($item->description)
                                    <p class="text-sm mt-1 nepali-font">{{ $item->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
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
            <div class="bg-white rounded-lg shadow-sm overflow-hidden dark:bg-gray-800">
                @if($item->isPhoto() && $item->photo_url)
                    <a data-fancybox="gallery" href="{{ $item->photo_url }}" data-caption="{{ $item->title }} {{ $item->description }}">
                        <img src="{{ $item->photo_url }}" alt="{{ $item->title }}" class="w-full h-60 object-cover hover:opacity-80 transition cursor-pointer">
                    </a>
                @elseif($item->isLocalVideo() && $item->video_url)
                    <a data-fancybox data-type="video" href="{{ $item->video_url }}" data-caption="{{ $item->title }}">
                        <video class="w-full h-60 object-cover hover:opacity-80 transition" muted autoplay loop>
                            <source src="{{ $item->video_url }}" type="video/mp4">
                            तपाईको browser ले यो भिडियो support गर्दैन।
                        </video>
                    </a>
                @elseif($item->isExternalVideo() && $item->video_embed_url)
                    <a data-fancybox data-type="iframe" data-src="{{ $item->video_embed_url }}" href="javascript:;" data-caption="{{ $item->title }}">
                        @php
                            $youtubeId = null;
                            $url = $item->video_url;
                            if (preg_match('%(?:youtube\.com/(?:embed/|v/|watch\?v=)|youtu\.be/)([\w-]{11})%', $url, $matches)) {
                                $youtubeId = $matches[1];
                            }
                        @endphp

                        @if ($youtubeId)
                            <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg"
                                 alt="{{ $item->title }}"
                                 class="w-full h-60 object-cover hover:opacity-80 transition cursor-pointer">
                        @else
                            <div class="w-full h-60 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">अमान्य YouTube URL</span>
                            </div>
                        @endif
                    </a>
                @else
                    <div class="w-full h-60 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">कुनै मिडिया छैन</span>
                    </div>
                @endif

                <!-- Item Details -->
                <div class="p-3 border-t dark:border-gray-700">
                    <p class="font-semibold text-lg text-gray-800 dark:text-gray-200 nepali-font">{{ $item->title }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 nepali-font capitalize">{{ $item->typeLabel }}</p>
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
</div>
@endsection

@section('scripts')
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
@endsection
