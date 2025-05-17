@extends('layouts.admin')

@section('title', 'नयाँ ग्यालरी आइटम थप्नुहोस्')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-center text-red-600 mb-8 nepali-font">नयाँ ग्यालरी आइटम थप्नुहोस्</h1>

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md dark:bg-gray-800">
        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside nepali-font">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

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
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleFields() {
        const type = document.getElementById('type').value;
        const fileField = document.getElementById('file-field');

        // Always show file field for both image and video
        fileField.style.display = type ? 'block' : 'none';
    }

    // Initialize fields based on old input (in case of validation error)
    document.addEventListener('DOMContentLoaded', toggleFields);
</script>
@endsection
