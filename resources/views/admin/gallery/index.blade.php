@extends('layouts.admin')

@section('content')
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabTrigger = new bootstrap.Tab(document.querySelector('#viewTypeTab .nav-link.active'));
        tabTrigger.show();
    });
</script>
@endsection
