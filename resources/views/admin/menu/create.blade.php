@extends('layouts.admin')

@section('content')
<div class="container max-w-3xl mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-6 nepali-font">नयाँ मेनु थप्नुहोस्</h2>

    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- मेनुको नाम -->
        <div>
            <label for="name" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">मेनुको नाम *</label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   required
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:outline-none dark:bg-gray-800 dark:text-white">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- विवरण -->
        <div>
            <label for="description" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">विवरण</label>
            <textarea name="description"
                      id="description"
                      rows="3"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-white">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- मूल्य र श्रेणी (Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- मूल्य -->
            <div>
                <label for="price" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">मूल्य (रु) *</label>
                <input type="number"
                       name="price"
                       id="price"
                       step="0.01"
                       value="{{ old('price') }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-white">
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- श्रेणी -->
            <div>
                <label for="category_id" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">श्रेणी *</label>
                <select name="category_id"
                        id="category_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-white">
                    <option value="">-- श्रेणी छान्नुहोस् --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- तस्वीर -->
        <div>
            <label for="image" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">तस्वीर *</label>
            <input type="file"
                   name="image"
                   id="image"
                   accept="image/*"
                   required
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-white">
            <small class="text-gray-500 text-sm mt-1 block">अधिकतम साइज: 2MB | स्वीकार्य: JPG, PNG</small>
            @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Featured र Status बक्स -->
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Featured -->
            <div class="flex items-center space-x-2">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox"
                       name="is_featured"
                       id="isFeatured"
                       value="1"
                       {{ old('is_featured') ? 'checked' : '' }}
                       class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                <label for="isFeatured" class="font-medium text-gray-700 dark:text-gray-300">Featured डिश</label>
            </div>

            <!-- Status -->
            <div class="flex items-center space-x-2">
                <input type="hidden" name="status" value="0">
                <input type="checkbox"
                       name="status"
                       id="status"
                       value="1"
                       {{ old('status', true) ? 'checked' : '' }}
                       class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                <label for="status" class="font-medium text-gray-700 dark:text-gray-300">सक्रिय गर्नुहोस्</label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button type="submit"
                    class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17H11v-7h2v7zm1-9h-4V7h4v3z"/>
                </svg>
                सुरक्षित गर्नुहोस्
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Alpine.js वा Livewire को लागि कस्टम JS यदि आवश्यक छ भने
</script>
@endpush

@push('styles')
<style>
    /* आवश्यकता अनुसार कस्टम स्टाइल यहाँ थप्नुहोस् */
</style>
@endpush
