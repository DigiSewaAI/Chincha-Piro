@extends('layouts.admin')

@section('title', $gallery ? 'ग्यालरी सम्पादन गर्नुहोस्' : 'नयाँ ग्यालरी थप्नुहोस्')

@section('content')
<div class="container-fluid px-4 py-5">
    <h1 class="text-3xl font-bold mb-6 nepali-font">
        {{ $gallery ? 'ग्यालरी सम्पादन गर्नुहोस्' : 'नयाँ ग्यालरी थप्नुहोस्' }}
    </h1>

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="list-disc pl-5 nepali-font">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ $gallery ? route('admin.gallery.update', $gallery->id) : route('admin.gallery.store') }}"
                  method="POST" enctype="multipart/form-data" class="row g-4">

                @csrf
                @if($gallery)
                    @method('PUT')
                @endif

                <!-- Title -->
                <div class="col-md-6">
                    <label class="form-label nepali-font">शीर्षक</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title', $gallery->title ?? '') }}" required>
                </div>

                <!-- Category -->
                <div class="col-md-6">
                    <label class="form-label nepali-font">श्रेणी</label>
                    <select name="category" class="form-select" required>
                        <option value="">-- छान्नुहोस् --</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category', $gallery->category ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div class="col-md-6">
                    <label class="form-label nepali-font">प्रकार</label>
                    <select name="type" id="typeSelect" class="form-select" required>
                        <option value="">-- छान्नुहोस् --</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $gallery->type ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label nepali-font">स्टेटस</label>
                    <select name="is_active" class="form-select" required>
                        <option value="1" {{ old('is_active', $gallery->is_active ?? 1) == 1 ? 'selected' : '' }}>सक्रिय</option>
                        <option value="0" {{ old('is_active', $gallery->is_active ?? 1) == 0 ? 'selected' : '' }}>निष्क्रिय</option>
                    </select>
                </div>

                <!-- Featured -->
                <div class="col-md-6">
                    <label class="form-label nepali-font">फिचर्ड</label>
                    <select name="featured" class="form-select" required>
                        <option value="1" {{ old('featured', $gallery->featured ?? 0) == 1 ? 'selected' : '' }}>हो</option>
                        <option value="0" {{ old('featured', $gallery->featured ?? 0) == 0 ? 'selected' : '' }}>होईन</option>
                    </select>
                </div>

                <!-- File Upload -->
                <div id="fileUploadField" class="col-md-12 mt-3">
                    <label class="form-label nepali-font" id="fileLabel">फाइल अपलोड गर्नुहोस्</label>
                    <input type="file" name="file" class="form-control" id="fileInput" accept="image/*,video/*">

                    @if($gallery && $gallery->isPhoto())
                        <img src="{{ asset('storage/' . $gallery->image_path) }}" class="img-thumbnail mt-2" style="max-height: 200px;">
                    @elseif($gallery && $gallery->isVideo())
                        <video controls class="mt-2" style="max-height: 200px;">
                            <source src="{{ asset('storage/' . $gallery->image_path) }}" type="video/mp4">
                            तपाईको ब्राउजरले यो भिडियो सपोर्ट गर्दैन।
                        </video>
                    @endif
                </div>

                <!-- Video URL -->
                <div id="videoFields" class="col-md-12 mt-3 d-none">
                    <label class="form-label nepali-font">भिडियो URL</label>
                    <input type="url" name="video_url" id="videoUrlInput" class="form-control"
                           placeholder="उदाहरण: https://youtu.be/xxxxxxx"
                           value="{{ old('video_url', $gallery?->type == 'external_video' ? $gallery->image_path : '') }}">
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label nepali-font">वर्णन</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $gallery->description ?? '') }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="col-md-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $gallery ? 'अपडेट गर्नुहोस्' : 'थप्नुहोस्' }}
                    </button>
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">रद्द गर्नुहोस्</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('typeSelect');
    const fileInput = document.getElementById('fileInput');
    const fileLabel = document.getElementById('fileLabel');
    const fileUploadField = document.getElementById('fileUploadField');
    const videoFields = document.getElementById('videoFields');
    const videoUrlInput = document.getElementById('videoUrlInput');

    function toggleFields() {
        const selectedType = typeSelect.value;

        // Reset
        fileInput.removeAttribute('required');
        if (videoUrlInput) videoUrlInput.removeAttribute('required');
        fileInput.accept = "image/*,video/*";

        if (selectedType === 'photo') {
            fileLabel.textContent = 'छवि अपलोड गर्नुहोस्';
            fileInput.accept = "image/*";
            fileUploadField.style.display = 'block';
            videoFields.classList.add('d-none');
            fileInput.setAttribute('required', 'required');
        } else if (selectedType === 'video') {
            fileLabel.textContent = 'भिडियो अपलोड गर्नुहोस्';
            fileInput.accept = "video/*";
            fileUploadField.style.display = 'block';
            videoFields.classList.add('d-none');
            fileInput.setAttribute('required', 'required');
        } else if (selectedType === 'external_video') {
            fileUploadField.style.display = 'none';
            videoFields.classList.remove('d-none');
            if (videoUrlInput) videoUrlInput.setAttribute('required', 'required');
        } else {
            fileUploadField.style.display = 'none';
            videoFields.classList.add('d-none');
        }
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleFields);
        toggleFields();
    }
});
</script>
@endsection
