@if($menus->isEmpty())
    <div class="col-span-full text-center py-20">
        <p class="text-xl text-gray-500">कुनै पनि मेनु भेटिएन</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($menus as $menu)
        <div class="menu-card animate-fadeIn" data-category="{{ $menu->category_id }}">
            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full hover:shadow-xl hover:scale-105 transition-transform duration-300">
                <!-- मेनु छवि -->
                <div class="h-48 bg-gray-100 relative overflow-hidden">
                    <img src="{{ $menu->image ? asset('storage/'.$menu->image) : asset('images/placeholder.jpg') }}"
                         alt="{{ $menu->name }}"
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                    @if($menu->is_featured)
                        <span class="absolute top-3 right-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full font-bold">FEATURED</span>
                    @endif
                </div>

                <!-- मेनु विवरण -->
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">{{ $menu->category->name }}</div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ $menu->name }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $menu->description }}</p>
                    </div>

                    <div class="flex justify-between items-center mt-auto">
                        <span class="text-xl font-bold text-red-600">रु {{ number_format($menu->price, 2) }}</span>
                        <form action="{{ route('cart.add', $menu->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-cart-btn bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-300">
                                <i class="fas fa-cart-plus mr-2"></i>थप्नुहोस्
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $menus->links('pagination::bootstrap-5') }}
    </div>
@endif
