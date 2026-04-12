<?php

namespace App\Livewire;

use App\Models\InventoryCategory;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionAddon;
use Carbon\Carbon;
use Livewire\Component;

class DailyReport extends Component
{
    public string $selectedDate;

    public function mount()
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function previousDay()
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->subDay()->format('Y-m-d');
    }

    public function nextDay()
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->addDay()->format('Y-m-d');
    }

    public function goToToday()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function updatedSelectedDate($value)
    {
        if ($value) {
            $this->selectedDate = Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function getSelectedDateObject(): Carbon
    {
        return Carbon::parse($this->selectedDate);
    }

    public function getTotalRevenue()
    {
        return Transaction::whereDate('created_at', $this->getSelectedDateObject())
            ->sum('total_amount');
    }

    public function getTotalTransactions()
    {
        return Transaction::whereDate('created_at', $this->getSelectedDateObject())
            ->count();
    }

    public function getUniqueCustomers()
    {
        return Transaction::whereDate('created_at', $this->getSelectedDateObject())
            ->distinct()
            ->count('customer_phone');
    }

    public function getServiceBreakdown()
    {
        $transactions = Transaction::whereDate('created_at', $this->getSelectedDateObject())
            ->with('service')
            ->get();

        $services = Service::all()->pluck('name', 'type');

        $breakdown = [];
        foreach ($services as $type => $name) {
            $breakdown[$type] = [
                'name' => $name,
                'count' => $transactions->where('service.type', $type)->count(),
            ];
        }

        return $breakdown;
    }

    public function getAddonsByCategory()
    {
        $transactionIds = Transaction::whereDate('created_at', $this->getSelectedDateObject())
            ->pluck('id');

        $addons = TransactionAddon::whereIn('transaction_id', $transactionIds)
            ->with('inventoryItem.category')
            ->get();

        $categories = InventoryCategory::with('items')->get();

        $result = [];
        foreach ($categories as $category) {
            $categoryAddons = $addons->filter(function ($addon) use ($category) {
                return $addon->inventoryItem && $addon->inventoryItem->inventory_category_id === $category->id;
            });

            $totalQty = $categoryAddons->sum('quantity');
            $totalPrice = $categoryAddons->sum(fn ($a) => $a->quantity * $a->price);

            if ($totalQty > 0) {
                $result[] = [
                    'name' => $category->name,
                    'quantity' => $totalQty,
                    'total' => $totalPrice,
                ];
            }
        }

        return $result;
    }

    public function getCashPayments()
    {
        return Transaction::whereDate('created_at', $this->getSelectedDateObject())
            ->where('payment_status', 'paid')
            ->sum('amount_paid');
    }

    public function getLatestTransactions()
    {
        return Transaction::whereDate('created_at', $this->getSelectedDateObject())
            ->with('service')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function hasTransactions()
    {
        return $this->getTotalTransactions() > 0;
    }

    public function render()
    {
        return view('livewire.daily-report');
    }
}
