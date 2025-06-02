@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">नयाँ मेनु थप्नुहोस्</h2>

    <!-- ✅ enctype="multipart/form-data" पहिले नै सही छ -->
    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- नाम -->
        <div class="form-group mb-3">
            <label for="name">मेनुको नाम *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <span class="text-danger text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- विवरण -->
        <div class="form-group mb-3">
            <label for="description">विवरण</label>
            <textarea name="description" id="description" class="form-control" rows="2">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="row">
            <!-- मूल्य -->
            <div class="form-group col-md-6 mb-3">
                <label for="price">मूल्य (रु) *</label>
                <input type="number" name="price" id="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
                @error('price')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- श्रेणी -->
            <div class="form-group col-md-6 mb-3">
                <label for="category_id">श्रेणी *</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- श्रेणी छान्नुहोस् --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- तस्वीर -->
        <div class="form-group mb-3">
            <label for="image">तस्वीर *</label>
            <!-- ✅ name="image" पहिले नै सही छ -->
            <input type="file" name="image" id="image" class="form-control" accept=".jpg,.jpeg,.png" required>
            <small class="form-text text-muted">अधिकतम साइज: 2MB | स्वीकार्य: JPG, PNG</small>
            @error('image')
                <span class="text-danger text-sm d-block mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Featured -->
        <div class="form-group mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="isFeatured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <label class="form-check-label" for="isFeatured">Featured डिश</label>
            </div>
        </div>

        <!-- बटन -->
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> सुरक्षित गर्नुहोस्
        </button>
    </form>
</div>
@endsection
