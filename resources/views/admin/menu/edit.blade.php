@extends('layouts.admin')

@section('content')
<div class="container max-w-3xl mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-6 nepali-font">मेनु सम्पादन गर्नुहोस्</h2>

    <form action="{{ route('admin.menu.update', $menu) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- नाम -->
        <div>
            <label for="name" class="block font-medium text-gray-700 dark:text-gray-300">मेनुको नाम *</label>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name', $menu->name) }}"
                   required
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-white">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- विवरण -->
        <div>
            <label for="description" class="block font-medium text-gray-700 dark:text-gray-300">विवरण</label>
            <textarea name="description"
                      id="description"
                      rows="3"
                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-white">{{ old('description', $menu->description) }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- मूल्य र श्रेणी -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- मूल्य -->
            <div>
                <label for="price" class="block font-medium text-gray-700 dark:text-gray-300">मूल्य (रु) *</label>
                <input type="number"
                       name="price"
                       id="price"
                       step="0.01"
                       value="{{ old('price', $menu->price) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-white">
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- श्रेणी -->
            <div>
                <label for="category_id" class="block font-medium text-gray-700 dark:text-gray-300">श्रेणी *</label>
                <select name="category_id"
                        id="category_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 dark:bg-gray-800 dark:text-white">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Featured र Status -->
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Featured -->
            <div class="flex items-center space-x-2">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox"
                       name="is_featured"
                       id="isFeatured"
                       value="1"
                       {{ old('is_featured', $menu->is_featured) ? 'checked' : '' }}
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
                       {{ old('status', $menu->status ?? true) ? 'checked' : '' }}
                       class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                <label for="status" class="font-medium text-gray-700 dark:text-gray-300">सक्रिय मेनु</label>
            </div>
        </div>

        <!-- हालको तस्वीर -->
        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">हालको तस्वीर</label>
            @if($menu->image && Storage::disk('public')->exists($menu->image))
                <div class="mb-4">
                    <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}" class="w-40 h-40 object-cover rounded-lg shadow-md">
                </div>
            @else
                <p class="text-red-600 mb-2">तस्वीर भेटिएन</p>
            @endif
        </div>

        <!-- नयाँ तस्वीर -->
        <div>
            <label for="image" class="block font-medium text-gray-700 dark:text-gray-300 mb-1">नयाँ तस्वीर (वैकल्पिक)</label>
            <div class="flex items-center gap-4">
                <input type="file"
                       name="image"
                       id="customFile"
                       accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-800 dark:text-white">
                <span id="fileName" class="text-gray-600 dark:text-gray-400 truncate max-w-xs">{{ $menu->image ? basename($menu->image) : 'छान्नुहोस्' }}</span>
            </div>
            <small class="text-gray-500 text-sm mt-1 block">अधिकतम साइज: 2MB | स्वीकार्य: JPG, PNG</small>
            @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- सबमिट बटन -->
        <div class="mt-8">
            <button type="submit"
                    class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.573C6.68 12.66 6.99 17 11 17v-3m-8 3a9 9 0 009 9c4.478 0 8.268-3.233 9.542-7.477C20.542 9.233 16.752 6 12.334 6H4m8 9v3m0 0H4m0 0v-7h7v7z"/>
                </svg>
                अपडेट गर्नुहोस्
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // फाइल इनपुट लेबल अपडेट
    document.getElementById('customFile')?.addEventListener('change', function () {
        const fileName = this.files[0]?.name || 'छान्नुहोस्';
        document.getElementById('fileName').textContent = fileName;
    });
</script>
@endpush
@endsection
