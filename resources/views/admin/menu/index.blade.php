@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h2 class="mb-0 text-primary fw-semibold">मेनु व्यवस्थापन</h2>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-2"></i> नयाँ मेनु थप्नुहोस्
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @include('partials.alerts')

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="min-width: 130px;">तस्वीर</th>
                            <th style="min-width: 200px;">नाम</th>
                            <th class="text-end" style="min-width: 100px;">मूल्य</th>
                            <th style="min-width: 130px;">श्रेणी</th>
                            <th class="text-center" style="min-width: 100px;">स्थिति</th>
                            <th class="text-center" style="min-width: 100px;">विशेष</th>
                            <th class="text-center" style="min-width: 200px;">कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr>
                                <td class="text-center">
                                    @if($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}"
                                             alt="{{ $menu->name }}"
                                             class="img-thumbnail rounded"
                                             style="width: 90px; height: 90px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                                             style="width: 90px; height: 90px;">
                                            <i class="fas fa-image text-muted fa-lg"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold fs-6">{{ $menu->name }}</div>
                                    @if($menu->description)
                                        <div class="text-muted small mt-1">{{ Str::limit($menu->description, 70) }}</div>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">रु {{ number_format($menu->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-primary-subtle text-dark">
                                        {{ $menu->category?->name ?? 'श्रेणी छैन' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($menu->status)
                                        <span class="badge bg-success-subtle text-success">सक्रिय</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">निष्क्रिय</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($menu->is_featured)
                                        <i class="fas fa-star text-warning" title="विशेष मेनु"></i>
                                    @else
                                        <i class="far fa-star text-muted" title="सामान्य मेनु"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.menu.edit', $menu->id) }}"
                                           class="btn btn-sm btn-outline-primary px-3 shadow-sm"
                                           title="सम्पादन गर्नुहोस्">
                                            <i class="fas fa-pen me-1"></i> सम्पादन
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.menu.destroy', $menu->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('के तपाईँ \"{{ $menu->name }}\" मेनु मेटाउन निश्चित हुनुहुन्छ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger px-3 shadow-sm"
                                                    title="मेटाउनुहोस्">
                                                <i class="fas fa-trash-alt me-1"></i> मेटाउनुहोस्
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-utensils fa-2x mb-2"></i><br>
                                    कुनै मेनु भेटिएन।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($menus->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center">
                {{ $menus->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
