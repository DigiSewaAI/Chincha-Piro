@extends('layouts.admin')

@section('title', $gallery ? 'ग्यालरी सम्पादन गर्नुहोस्' : 'नयाँ ग्यालरी थप्नुहोस्')

@section('content')
<<<<<<< HEAD
<div class="container-fluid px-4 py-5">
    <h1 class="text-3xl font-bold mb-6 nepali-font">
        {{ $gallery ? 'ग्यालरी सम्पादन गर्नुहोस्' : 'नयाँ ग्यालरी थप्नुहोस्' }}
    </h1>
=======
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-center text-red-600 mb-8 nepali-font">नयाँ ग्यालरी आइटम थप्नुहोस्</h1>
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46

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

<<<<<<< HEAD
                @csrf
                @if($gallery)
                    @method('PUT')
                @endif

                <!-- Title -->
                <div class="col-md-6">
                    <label class="form-label nepali-font">शीर्षक</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $gallery->title ?? '') }}" required>
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
                <div id="photoFields" class="col-md-12 mt-3 {{ old('type', $gallery->type ?? '') == 'photo' || !$gallery ? 'd-block' : 'd-none' }}">
                    <label class="form-label nepali-font">छवि अपलोड गर्नुहोस्</label>
                    <input type="file" name="file" class="form-control" accept="image/*">

                    @if($gallery && $gallery->isPhoto())
                        <img src="{{ asset('storage/' . $gallery->image_path) }}" class="img-thumbnail mt-2" style="max-height: 200px;">
                    @endif
                </div>

                <!-- Video URL -->
                <div id="videoFields" class="col-md-12 mt-3 {{ old('type', $gallery->type ?? '') == 'video' ? 'd-block' : 'd-none' }}">
                    <label class="form-label nepali-font">भिडियो URL</label>
                    <input type="url" name="video_url" class="form-control"
                           placeholder="उदाहरण: https://youtu.be/xxxxxxx    "
                           value="{{ old('video_url', $gallery->image_path ?? '') }}">
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
=======
            <!-- Title -->
            <div>
                <label for="title" class="block font-medium text-gray-700 dark:text-gray-200 nepali-font">शीर्षक</label>
                <input type="text" name="title" id="title" required
                       value="{{ old('title') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block font-medium text-gray-700 dark:text-gray-200 nepali-font">वर्णन (वैकल्पिक)</label>
                <textarea name="description" id="description"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                          rows="3">{{ old('description') }}</textarea>
            </div>

            <!-- Type Selection -->
            <div>
                <label for="type" class="block font-medium text-gray-700 dark:text-gray-200 nepali-font">प्रकार</label>
                <select name="type" id="type" required onchange="toggleFields()"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- छान्नुहोस् --</option>
                    <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>फोटो</option>
                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>भिडियो</option>
                </select>
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block font-medium text-gray-700 dark:text-gray-200 nepali-font">श्रेणी</label>
                <select name="category" id="category" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- छान्नुहोस् --</option>
                    <option value="dish" {{ old('category') == 'dish' ? 'selected' : '' }}>खाना</option>
                    <option value="restaurant" {{ old('category') == 'restaurant' ? 'selected' : '' }}>रेस्टुरेन्ट</option>
                    <option value="video" {{ old('category') == 'video' ? 'selected' : '' }}>भिडियो</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>अन्य</option>
                </select>
            </div>

            <!-- File Upload -->
            <div id="file-field">
                <label for="file" class="block font-medium text-gray-700 dark:text-gray-200 nepali-font">फाइल अपलोड गर्नुहोस्</label>
                <input type="file" name="file" id="file" required
                       class="mt-1 block w-full text-gray-700 dark:text-white border border-gray-300 dark:border-gray-600 rounded-md p-2 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-red-100 file:text-red-700 hover:file:bg-red-200">
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-md transition nepali-font">
                    सेभ गर्नुहोस्
                </button>
            </div>
        </form>
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46
    </div>
</div>
@endsection

@section('scripts')
<script>
<<<<<<< HEAD
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('typeSelect');
    const photoFields = document.getElementById('photoFields');
    const videoFields = document.getElementById('videoFields');

    function toggleFields() {
        const selectedType = typeSelect.value;
        photoFields.style.display = selectedType === 'photo' ? 'block' : 'none';
        videoFields.style.display = selectedType === 'video' ? 'block' : 'none';
=======
    function toggleFields() {
        const type = document.getElementById('type').value;
        const fileField = document.getElementById('file-field');

        // Always show file field for both image and video
        fileField.style.display = type ? 'block' : 'none';
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleFields);
        toggleFields();
    }
});
</script>
@endsection
