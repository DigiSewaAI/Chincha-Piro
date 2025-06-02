@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">मेनु सम्पादन गर्नुहोस्</h2>

    <form action="{{ route('admin.menu.update', $menu) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- नाम -->
        <div class="form-group mb-3">
            <label for="name">मेनुको नाम *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $menu->name) }}" required>
            @error('name')
                <span class="text-danger text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- विवरण -->
        <div class="form-group mb-3">
            <label for="description">विवरण</label>
            <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $menu->description) }}</textarea>
            @error('description')
                <span class="text-danger text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- मूल्य र श्रेणी -->
        <div class="row">
            <div class="form-group col-md-6 mb-3">
                <label for="price">मूल्य (रु) *</label>
                <input type="number" name="price" id="price" step="0.01" class="form-control" value="{{ old('price', $menu->price) }}" required>
                @error('price')
                    <span class="text-danger text-sm">{{ $message }}</span>
            </div>

            <div class="form-group col-md-6 mb-3">
                <label for="category_id">श्रेणी *</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Featured -->
        <div class="form-group mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="isFeatured" name="is_featured" value="1"
                    {{ old('is_featured', $menu->is_featured) ? 'checked' : '' }}>
                <label class="form-check-label" for="isFeatured">Featured डिश</label>
            </div>
            @error('is_featured')
                <span class="text-danger text-sm d-block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- हालको तस्वीर -->
        <div class="form-group mb-3">
            <label>हालको तस्वीर</label><br>
            <!-- ✅ Storage::url() प्रयोग गरेर सही URL जनरेट -->
            @if($menu->image && Storage::disk('public')->exists($menu->image))
                <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}" width="150" class="img-thumbnail mb-2">
            @else
                <p class="text-danger">तस्वीर भेटिएन</p>
            @endif
        </div>

        <!-- नयाँ तस्वीर -->
        <div class="form-group mb-3">
            <label for="image">नयाँ तस्वीर (वैकल्पिक)</label>
            <div class="input-group">
                <!-- ✅ name="image" पहिले नै सही छ -->
                <input type="file" name="image" class="form-control" id="customFile" accept=".jpg,.jpeg,.png">
                <label class="input-group-text" for="customFile">छान्नुहोस्</label>
            </div>
            <small class="form-text text-muted mt-1">अधिकतम साइज: 2MB | स्वीकार्य: JPG, PNG</small>
            @error('image')
                <span class="text-danger text-sm d-block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- सबमिट बटन -->
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sync-alt me-1"></i> अपडेट गर्नुहोस्
        </button>
    </form>
</div>

@push('scripts')
<script>
    // फाइल इनपुट लेबल अपडेट
    document.getElementById('customFile').addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'छान्नुहोस्';
        this.nextElementSibling.textContent = fileName;
    });
</script>
@endpush
@endsection
