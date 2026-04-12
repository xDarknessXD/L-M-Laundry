<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class Transactions extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterPayment = '';
    public string $filterOrder = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterPayment()
    {
        $this->resetPage();
    }

    public function updatedFilterOrder()
    {
        $this->resetPage();
    }

    public function render()
    {
        $transactions = Transaction::with(['service', 'creator'])
            ->when($this->search, fn($q) => $q->where('customer_name', 'like', "%{$this->search}%")
                ->orWhere('order_number', 'like', "%{$this->search}%"))
            ->when($this->filterPayment, fn($q) => $q->where('payment_status', $this->filterPayment))
            ->when($this->filterOrder, fn($q) => $q->where('order_status', $this->filterOrder))
            ->latest()
            ->paginate(15);

        return view('livewire.transactions', compact('transactions'));
    }
}
