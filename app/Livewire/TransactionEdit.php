<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Transaction;

#[Layout('layouts.app')]
class TransactionEdit extends Component
{
    public Transaction $transaction;

    public string $customer_name = '';
    public string $customer_phone = '';
    public string $material_type = '';
    public $kilos = 0;
    public $amount_paid = 0;
    public string $payment_status = '';
    public string $order_status = '';

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->customer_name = $transaction->customer_name;
        $this->customer_phone = $transaction->customer_phone ?? '';
        $this->material_type = $transaction->material_type;
        $this->kilos = $transaction->kilos;
        $this->amount_paid = $transaction->amount_paid;
        $this->payment_status = $transaction->payment_status;
        $this->order_status = $transaction->order_status;
    }

    public function save()
    {
        $this->validate([
            'customer_name' => 'required|min:2',
            'amount_paid' => 'required|numeric|min:0',
            'order_status' => 'required',
        ]);

        // Recalculate payment status
        $balance = $this->transaction->total_amount - $this->amount_paid;
        if ($this->amount_paid >= $this->transaction->total_amount) {
            $this->payment_status = 'paid';
            $balance = 0;
        } elseif ($this->amount_paid > 0) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'unpaid';
        }

        $this->transaction->update([
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'amount_paid' => $this->amount_paid,
            'balance' => max(0, $balance),
            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status,
        ]);

        $this->dispatch('toast', message: 'Transaction updated successfully!', type: 'success');
        return redirect()->route('transactions');
    }

    public function render()
    {
        return view('livewire.transaction-edit')
            ->layout('layouts.app');
    }
}
