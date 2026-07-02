<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public bool $editing = false;

    public ?int $editId = null;

    public string $name = '';

    public string $phone = '';

    public ?int $selectedCustomerId = null;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openAdd()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $customer = Customer::find($id);
        if (! $customer) {
            return;
        }

        $this->editing = true;
        $this->editId = $customer->id;
        $this->name = $customer->name;
        $this->phone = $customer->phone ?? '';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:2',
            'phone' => 'nullable',
        ]);

        if ($this->editing && $this->editId) {
            Customer::where('id', $this->editId)->update([
                'name' => $this->name,
                'phone' => $this->phone ?: null,
            ]);
            $msg = 'Customer updated.';
        } else {
            Customer::create([
                'name' => $this->name,
                'phone' => $this->phone ?: null,
            ]);
            $msg = 'Customer added.';
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        if (! $customer) {
            return;
        }

        $customer->delete();
        $this->selectedCustomerId = null;
        $this->dispatch('toast', message: 'Customer deleted.', type: 'success');
    }

    public function viewHistory($id)
    {
        $this->selectedCustomerId = $this->selectedCustomerId === $id ? null : $id;
    }

    public function getCustomerTransactions()
    {
        if (! $this->selectedCustomerId) {
            return collect();
        }

        return Transaction::with('service')
            ->where('customer_id', $this->selectedCustomerId)
            ->latest()
            ->get();
    }

    private function resetForm()
    {
        $this->editing = false;
        $this->editId = null;
        $this->name = '';
        $this->phone = '';
    }

    public function render()
    {
        $customers = Customer::withCount('transactions')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.customers', [
            'customers' => $customers,
            'customerTransactions' => $this->getCustomerTransactions(),
        ]);
    }
}
