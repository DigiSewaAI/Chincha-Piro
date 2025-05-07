<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class AdminOrders extends Component
{
    use WithPagination;

    // Public Properties for Filtering & Search
    public $statusFilter = '';
    public $search = '';
    public $perPage = 10;

    // Listeners for Real-Time Updates
    protected $listeners = ['newOrder' => '$refresh'];

    // Reset Pagination When Filters Change
    protected $queryString = ['statusFilter', 'search'];

    public function updating($property)
    {
        if ($property === 'statusFilter' || $property === 'search') {
            $this->resetPage();
        }
    }

    public function render()
    {
        // Base Query
        $query = Order::with('dish', 'user') // Load related models
            ->when($this->statusFilter, function ($q) {
                return $q->where('status', $this->statusFilter);
            })
            ->when($this->search, function ($q) {
                return $q->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('id', 'like', '%' . $this->search . '%');
            });

        // Paginate Results
        $orders = $query->latest()->paginate($this->perPage);

        return view('livewire.admin-orders', [
            'orders' => $orders,
            'statuses' => Order::distinct('status')->pluck('status') // All unique statuses
        ]);
    }

    // Optional: Method to Clear Filters
    public function clearFilters()
    {
        $this->reset(['statusFilter', 'search', 'perPage']);
        $this->resetPage();
    }
}
