@extends('layouts.app')  <!-- यो लाइन छोड्नुहोस् -->

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('ड्यासबोर्ड') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h1>स्वागत छ! {{ Auth::user()->name }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
