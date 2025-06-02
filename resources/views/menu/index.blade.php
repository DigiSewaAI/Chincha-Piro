@extends('layouts.app')
@section('title', 'मेनु - Chincha Piro')
@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl md:text-5xl font-bold text-center text-red-600 mb-6">हाम्रो मेनु</h1>
    <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
        हाम्रा विविध नेपाली पकवानहरूको स्वाद लिनुहोस् - ताजा, स्वादिष्ट र स्वस्थ।
    </p>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap justify-center gap-4 mb-10">
        <button onclick="filterMenu('all')" class="filter-btn active">सबै पकवान</button>
        @foreach($categories as $category)
            <button
                onclick="filterMenu('{{ $category->id }}')"
                class="filter-btn"
                aria-label="फिल्टर गर्नुहोस्: {{ $category->name }}"
            >
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Menu Grid -->
    <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($menus as $menu)
        <div
            class="menu-item"
            data-category="{{ $menu->category_id }}"
            itemscope itemtype="https://schema.org/FoodEstablishment"
        >
            <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col h-full hover:shadow-xl hover:scale-105 transition-transform duration-300">
                <div class="h-48 bg-gray-100 relative overflow-hidden">
                    <img
                        src="{{ Storage::url($menu->image) }}"
                        alt="{{ $menu->name }}"
                        loading="lazy"
                        itemprop="image"
                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                    >
                    @if($menu->is_featured)
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full font-bold">FEATURED</span>
                    @endif
                </div>
                <div class="p-6 flex-grow flex flex-col">
                    <div class="text-sm text-gray-500 mb-1">{{ $menu->category->name }}</div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2" itemprop="name">{{ $menu->name }}</h2>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $menu->description }}</p>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xl font-bold text-red-600" itemprop="price">रु {{ number_format($menu->price, 2) }}</span>
                        <span class="text-sm text-green-600 font-medium">उपलब्ध</span>
                    </div>
                    <button
                        onclick="toggleModal('orderModal{{ $menu->id }}')"
                        class="mt-auto w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300"
                        aria-label="अर्डर गर्नुहोस् {{ $menu->name }}"
                    >
                        अर्डर गर्नुहोस्
                    </button>
                </div>
            </div>

            <!-- Order Modal -->
            <div
                id="orderModal{{ $menu->id }}"
                class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4"
                role="dialog"
                aria-labelledby="orderModalLabel{{ $menu->id }}"
                aria-hidden="true"
            >
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative animate-fadeIn">
                    <button
                        onclick="toggleModal('orderModal{{ $menu->id }}')"
                        class="absolute top-4 right-4 text-gray-600 text-2xl hover:text-black"
                        aria-label="मोडल बन्द गर्नुहोस्"
                    >
                        &times;
                    </button>
                    <h3 id="orderModalLabel{{ $menu->id }}" class="text-2xl font-bold text-gray-800 mb-4">अर्डर गर्नुहोस्: {{ $menu->name }}</h3>
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <div class="mb-4">
                            <label for="customer_name_{{ $menu->id }}" class="block text-gray-700 mb-2">ग्राहकको नाम</label>
                            <input
                                type="text"
                                name="customer_name"
                                id="customer_name_{{ $menu->id }}"
                                required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-red-500"
                            >
                        </div>
                        <div class="mb-4">
                            <label for="phone_{{ $menu->id }}" class="block text-gray-700 mb-2">फोन नम्बर</label>
                            <input
                                type="tel"
                                name="phone"
                                id="phone_{{ $menu->id }}"
                                required
                                pattern="98\d{8}|97\d{8}"
                                placeholder="98XXXXXXXX"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-red-500"
                            >
                            <small class="text-gray-500">उदाहरण: 9841234567</small>
                        </div>
                        <div class="mb-4">
                            <label for="address_{{ $menu->id }}" class="block text-gray-700 mb-2">ठेगाना</label>
                            <textarea
                                name="address"
                                id="address_{{ $menu->id }}"
                                required
                                rows="2"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-red-500"
                            ></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="special_instructions_{{ $menu->id }}" class="block text-gray-700 mb-2">विशेष निर्देश (वैकल्पिक)</label>
                            <textarea
                                name="special_instructions"
                                id="special_instructions_{{ $menu->id }}"
                                rows="2"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-red-500"
                            ></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="quantity_{{ $menu->id }}" class="block text-gray-700 mb-2">मात्रा</label>
                            <input
                                type="number"
                                name="quantity"
                                id="quantity_{{ $menu->id }}"
                                min="1"
                                max="20"
                                value="1"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-red-500"
                            >
                        </div>
                        <button
                            type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition"
                        >
                            अर्डर पुष्टि गर्नुहोस्
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20">
            <p class="text-xl text-gray-500">कुनै पनि पकवान भेटिएन।</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $menus->links('vendor.pagination.tailwind') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize filter on page load
    document.addEventListener('DOMContentLoaded', () => {
        const activeBtn = document.querySelector('.filter-btn.active');
        if (activeBtn) {
            filterMenu('all');
            activeBtn.setAttribute('aria-pressed', 'true');
        }
    });

    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.setAttribute('aria-hidden', modal.classList.contains('hidden'));
        }
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="orderModal"]').forEach(modal => {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
            });
        }
    });

    document.querySelectorAll('[id^="orderModal"]').forEach(modal => {
        modal.addEventListener('click', e => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
            }
        });
    });

    function filterMenu(categoryId) {
        const items = document.querySelectorAll('.menu-item');
        items.forEach(item => {
            item.style.display = (categoryId === 'all' || item.dataset.category === categoryId) ? 'block' : 'none';
        });
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-pressed', 'false');
        });
        if (categoryId !== 'all') {
            const activeBtn = document.querySelector(`[onclick="filterMenu('${categoryId}')"]`);
            if (activeBtn) {
                activeBtn.classList.add('active');
                activeBtn.setAttribute('aria-pressed', 'true');
            }
        }
    }
</script>
@endpush

@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .filter-btn {
        @apply px-4 py-2 rounded-full text-sm font-medium border border-red-600 text-red-600 hover:bg-red-100 transition-colors;
    }
    .filter-btn.active {
        @apply bg-red-600 text-white;
    }
</style>
@endpush
