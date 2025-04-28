@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-lg">

    <h1 class="text-4xl font-bold mb-6 text-purple-700">प्रशासनिक प्यानल</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <!-- Reservations Card -->
        <div class="bg-purple-100 p-6 rounded-lg shadow hover:bg-purple-200 transition">
            <h3 class="text-lg font-semibold text-purple-800 mb-2">आजका रिजर्भेसन</h3>
            <p class="text-4xl font-bold text-purple-900">{{ $todayReservations }}</p>
            <a href="{{ route('reservations.index') }}" class="text-purple-600 hover:underline text-sm mt-3 inline-block">
                थप हेर्नुहोस् →
            </a>
        </div>

        <!-- Orders Card -->
        <div class="bg-green-100 p-6 rounded-lg shadow hover:bg-green-200 transition">
            <h3 class="text-lg font-semibold text-green-800 mb-2">आजका अर्डरहरू</h3>
            <p class="text-4xl font-bold text-green-900">{{ $todayOrders }}</p>
            <a href="{{ route('orders.index') }}" class="text-green-600 hover:underline text-sm mt-3 inline-block">
                थप हेर्नुहोस् →
            </a>
        </div>

        <!-- Menu Items Card -->
        <div class="bg-yellow-100 p-6 rounded-lg shadow hover:bg-yellow-200 transition">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">मेनु आइटमहरू</h3>
            <p class="text-4xl font-bold text-yellow-900">{{ $menuCount }}</p>
            <a href="#" class="text-yellow-600 hover:underline text-sm mt-3 inline-block">
                थप हेर्नुहोस् →
            </a>
        </div>

    </div>

    <!-- Graph Section -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">महिनावारी राजस्व ग्राफ</h2>
        <canvas id="revenueChart"></canvas>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">हालैका अर्डरहरू</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-100 text-gray-600">
                    <tr>
                        <th class="py-3 px-4">ग्राहक</th>
                        <th class="py-3 px-4">अर्डर ID</th>
                        <th class="py-3 px-4">मिति</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">#{{ $order->id }}</td>
                        <td class="py-3 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                    @if($recentOrders->isEmpty())
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-400">कुनै अर्डर फेला परेन।</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'महिनावारी आम्दानी (रु)',
            data: @json($chartData),
            borderColor: 'rgba(99, 102, 241, 1)',
            backgroundColor: 'rgba(99, 102, 241, 0.2)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgba(99, 102, 241, 1)',
        }]
    },
    options: {
        responsive: true,
    }
});
</script>

<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }}',
    timer: 2500,
    showConfirmButton: false
});
@endif
</script>
@endpush
