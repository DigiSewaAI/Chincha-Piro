@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">मेनु व्यवस्थापन</h2>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> नयाँ मेनु थप्नुहोस्
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 120px;">तस्वीर</th>
                            <th>नाम</th>
                            <th class="text-end">मूल्य</th>
                            <th>श्रेणी</th>
                            <th class="text-center">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr>
                                <td class="text-center">
                                    @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                                        <img src="{{ asset('storage/' . $menu->image) }}"
                                             alt="{{ $menu->name }}"
                                             width="80"
                                             height="80"
                                             class="rounded shadow-sm img-thumbnail object-fit-cover"
                                             style="object-fit: cover;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $menu->name }}</div>
                                    <small class="text-muted d-block">{{ Str::limit($menu->description, 30) }}</small>
                                    @if($menu->is_featured)
                                        <span class="badge bg-danger mt-1">Featured</span>
                                    @endif
                                </td>
                                <td class="text-end">रु {{ number_format($menu->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $menu->category?->name ?? 'श्रेणी छैन' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.menu.edit', $menu) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="सम्पादन गर्नुहोस्">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.menu.destroy', $menu) }}"
                                              method="POST"
                                              class="delete-form d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-name="{{ $menu->name }}"
                                                    title="मेटाउनुहोस्">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-utensils fa-2x mb-2"></i><br>
                                    कुनै मेनु भेटिएन।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($menus->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center">
                {{ $menus->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const name = this.dataset.name;

                if (confirm(`'${name}' मेनुलाई मेटाउन चाहनुहुन्छ?`)) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
