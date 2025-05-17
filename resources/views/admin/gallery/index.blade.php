@extends('layouts.admin')

@section('content')
    <h1>Gallery Management</h1>

    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">Add New</a>

    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="row mt-4">
        @foreach($galleryItems as $item)
            <div class="col-md-3 mb-4">
                <div class="card">
                    @if($item->type === 'image')
                        <img src="{{ asset('storage/' . $item->file_path) }}" class="card-img-top" alt="{{ $item->title }}">
                    @else
                        <video controls class="w-100">
                            <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4">
                        </video>
                    @endif

                    <div class="card-body">
                        <p>{{ $item->title }}</p>
                        <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Optional: Add pagination if you use paginate() in the controller -->
    <div class="d-flex justify-content-center mt-4">
        {{ $galleryItems->links() }}
    </div>
@endsection
