@extends('layouts.admin')

@section('content')
<<<<<<< HEAD
<div class="container-fluid px-4 py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-3xl font-bold nepali-font">ग्यालरी प्रबन्धन</h1>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-success btn-lg">
            <i class="bi bi-plus-circle me-2"></i> नयाँ आइटम थप्नुहोस्
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search & Sort Form -->
    <form class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="शीर्षक वा श्रेणीमा खोज्नुहोस्">
            </div>
            <div class="col-md-3">
                <select name="sort" class="form-select">
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>शीर्षक अनुसार</option>
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>मिति अनुसार</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="order" class="form-select">
                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>उतरतो</option>
                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>उर्ध्वगामी</option>
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-primary" type="submit">खोज्नुहोस्</button>
            </div>
        </div>
    </form>

    <!-- View Toggle -->
    <ul class="nav nav-tabs mb-3" id="viewTypeTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="table-view-tab" data-bs-toggle="tab" data-bs-target="#tableView" type="button" role="tab">तालिका दृश्य</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="card-view-tab" data-bs-toggle="tab" data-bs-target="#cardView" type="button" role="tab">कार्ड दृश्य</button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Table View -->
        <div class="tab-pane fade show active" id="tableView" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>शीर्षक</th>
                            <th>श्रेणी</th>
                            <th>प्रकार</th>
                            <th>स्टेटस</th>
                            <th>फीचर्ड</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galleries as $gallery)
                            <tr>
                                <td>{{ $gallery->title }}</td>
                                <td>{{ $gallery->category_label }}</td>
                                <td>
                                    <span class="badge bg-{{ $gallery->isPhoto() ? 'success' : ($gallery->isLocalVideo() ? 'info' : 'primary') }}">
                                        {{ $gallery->type_label }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.gallery.toggleStatus', $gallery) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $gallery->is_active ? 'btn-success' : 'btn-danger' }}">
                                            {{ $gallery->status_badge['text'] }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.gallery.markFeatured', $gallery) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $gallery->featured ? 'btn-warning' : 'btn-secondary' }}">
                                            {{ $gallery->featured ? 'फीचर्ड' : 'फीचर गर्नुहोस्' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('हटाउने कि सुनिश्चित गर्नुहोस्?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4">ग्यालरीमा कुनै आइटम छैन</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $galleries->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- Card View -->
        <div class="tab-pane fade" id="cardView" role="tabpanel">
            <div class="row g-4">
                @forelse($galleries as $gallery)
                    <div class="col-md-6 col-lg-4 col-xl-3 d-flex">
                        <div class="card h-100 shadow-sm border-0 w-100">
                            <div class="ratio ratio-16x9">
                                @if($gallery->isPhoto() && $gallery->photo_url)
                                    <img src="{{ $gallery->photo_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $gallery->title }}">
                                @elseif($gallery->isLocalVideo() && $gallery->video_url)
                                    <video controls class="w-100 h-100 object-fit-cover">
                                        <source src="{{ $gallery->video_url }}" type="video/mp4">
                                        तपाईको ब्राउजरले यो भिडियो समर्थन गर्दैन।
                                    </video>
                                @elseif($gallery->isExternalVideo() && $gallery->video_embed_url)
                                    <iframe class="w-100 h-100" src="{{ $gallery->video_embed_url }}" allowfullscreen title="{{ $gallery->title }}"></iframe>
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light text-muted w-100 h-100">
                                        <span>मिडिया उपलब्ध छैन</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <h5 class="card-title nepali-font mb-1">{{ $gallery->title }}</h5>
                                <small class="text-muted mb-2">
                                    {{ $gallery->category_label }} | {{ $gallery->type_label }}
                                </small>
                                <div class="mt-auto pt-2 d-flex justify-content-between">
                                    <form action="{{ route('admin.gallery.toggleStatus', $gallery) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $gallery->is_active ? 'btn-success' : 'btn-danger' }}">
                                            {{ $gallery->is_active ? 'सक्रिय' : 'निष्क्रिय' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.gallery.markFeatured', $gallery) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $gallery->featured ? 'btn-warning' : 'btn-secondary' }}">
                                            {{ $gallery->featured ? 'फीचर्ड' : 'फीचर गर्नुहोस्' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">ग्यालरीमा कुनै आइटम छैन</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
=======
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white nepali-font">ग्यालरी व्यवस्थापन</h1>
        <a href="{{ route('admin.gallery.create') }}"
           class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow transition duration-300 flex items-center nepali-font">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            नयाँ आइटम थप्नुहोस्
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 nepali-font" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Categories Navigation -->
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
        <nav class="flex space-x-4">
            @foreach(['dish' => 'खाना', 'restaurant' => 'रेस्टुरेन्ट', 'video' => 'भिडियो', 'other' => 'अन्य'] as $key => $label)
                <a href="#category-{{ $key }}"
                   class="py-3 px-4 text-sm font-medium rounded-t-lg border-b-2 border-transparent hover:text-red-500 hover:border-red-300 transition-all duration-300 nepali-font"
                   data-category="{{ $key }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Gallery Items -->
    @foreach(['dish' => 'खाना', 'restaurant' => 'रेस्टुरेन्ट', 'video' => 'भिडियो', 'other' => 'अन्य'] as $key => $label)
        <div id="category-{{ $key }}" class="mb-10">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700 dark:text-gray-300 nepali-font">
                {{ $label }}
                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                    ({{ $galleryItems->where('category', $key)->count() }} आइटमहरू)
                </span>
            </h2>

            @php
                $items = $galleryItems->where('category', $key);
            @endphp

            @if($items->isEmpty())
                <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 text-yellow-700 dark:text-yellow-200 p-4 rounded nepali-font">
                    <p>कुनै पनि आइटम भेटिएन। पहिला आइटम थप्नुहोस्।</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($items as $item)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:shadow-lg hover:scale-105">
                            <div class="relative aspect-video overflow-hidden bg-gray-100 dark:bg-gray-700">
                                @if($item->type === 'image')
                                    <img src="{{ asset('storage/' . $item->image_path) }}"
                                         alt="{{ $item->title }}"
                                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                         loading="lazy">
                                @else
                                    <video class="w-full h-full object-cover" muted>
                                        <source src="{{ asset('storage/' . $item->image_path) }}" type="video/mp4">
                                        तपाईंको ब्राउजरले भिडियोलाई समर्थन गर्दैन।
                                    </video>
                                @endif

                                <div class="absolute top-2 right-2 flex space-x-2">
                                    <span class="px-2 py-1 text-xs font-semibold text-white bg-gray-800 bg-opacity-70 rounded nepali-font">
                                        {{ $item->type === 'image' ? 'फोटो' : 'भिडियो' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4">
                                <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-1 nepali-font">{{ $item->title }}</h3>

                                @if($item->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 nepali-font line-clamp-2">
                                        {{ $item->description }}
                                    </p>
                                @endif

                                <!-- ✅ Corrected Delete Form -->
                                <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('के तपाईं यो आइटम हटाउन चाहनुहुन्छ?');" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900 dark:hover:bg-red-800 dark:text-red-200 py-1.5 px-3 rounded text-sm font-medium transition duration-200 flex items-center justify-center nepali-font">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        हटाउनुहोस्
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    <!-- Pagination -->
    <div class="mt-10 flex justify-center">
        {{ $galleryItems->links('vendor.pagination.tailwind') }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll to category sections
    document.querySelectorAll('[data-category]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const category = this.getAttribute('data-category');
            const target = document.getElementById(`category-${category}`);

            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@endpush
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabTrigger = new bootstrap.Tab(document.querySelector('#viewTypeTab .nav-link.active'));
        tabTrigger.show();
    });
</script>
@endsection
