<?php

namespace App\Livewire;

use App\Models\InventoryCategory;
use App\Models\MachineLog;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionAddon;
use Carbon\Carbon;
use Livewire\Component;

class DailyReport extends Component
{
    public string $period = 'daily';

    public string $selectedDate;

    public function mount()
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function setPeriod(string $period)
    {
        $this->period = $period;
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function previousDay()
    {
        $this->selectedDate = match ($this->period) {
            'weekly' => Carbon::parse($this->selectedDate)->subWeek()->format('Y-m-d'),
            'monthly' => Carbon::parse($this->selectedDate)->subMonth()->format('Y-m-d'),
            default => Carbon::parse($this->selectedDate)->subDay()->format('Y-m-d'),
        };
    }

    public function nextDay()
    {
        $this->selectedDate = match ($this->period) {
            'weekly' => Carbon::parse($this->selectedDate)->addWeek()->format('Y-m-d'),
            'monthly' => Carbon::parse($this->selectedDate)->addMonth()->format('Y-m-d'),
            default => Carbon::parse($this->selectedDate)->addDay()->format('Y-m-d'),
        };
    }

    public function goToToday()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function onDateChange($value)
    {
        if ($value) {
            $this->selectedDate = Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function getDateRange(): array
    {
        $date = Carbon::parse($this->selectedDate);

        return match ($this->period) {
            'weekly' => [
                'start' => $date->copy()->startOfWeek()->startOfDay(),
                'end' => $date->copy()->endOfWeek()->endOfDay(),
            ],
            'monthly' => [
                'start' => $date->copy()->startOfMonth()->startOfDay(),
                'end' => $date->copy()->endOfMonth()->endOfDay(),
            ],
            default => [
                'start' => $date->copy()->startOfDay(),
                'end' => $date->copy()->endOfDay(),
            ],
        };
    }

    public function getPeriodLabel(): string
    {
        return match ($this->period) {
            'weekly' => 'Weekly Report',
            'monthly' => 'Monthly Report',
            default => 'Daily Report',
        };
    }

    public function getPeriodDescription(): string
    {
        return match ($this->period) {
            'weekly' => 'Overview of weekly operations',
            'monthly' => 'Overview of monthly operations',
            default => 'Overview of daily operations',
        };
    }

    public function getDateRangeLabel(): string
    {
        $range = $this->getDateRange();

        return match ($this->period) {
            'weekly' => 'Week of ' . $range['start']->format('M d') . ' - ' . $range['end']->format('M d, Y'),
            'monthly' => $range['start']->format('F Y'),
            default => $range['start']->format('F d, Y'),
        };
    }

    private function baseQuery()
    {
        $range = $this->getDateRange();
        return Transaction::whereBetween('created_at', [$range['start'], $range['end']]);
    }

    public function getTotalRevenue()
    {
        return $this->baseQuery()->sum('total_amount');
    }

    public function getTotalTransactions()
    {
        return $this->baseQuery()->count();
    }

    public function getUniqueCustomers()
    {
        return $this->baseQuery()->distinct()->count('customer_phone');
    }

    public function getServiceBreakdown()
    {
        $transactions = $this->baseQuery()->with('service')->get();

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
        $transactionIds = $this->baseQuery()->pluck('id');

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
        return $this->baseQuery()
            ->where('payment_status', 'paid')
            ->sum('amount_paid');
    }

    public function getLatestTransactions()
    {
        $limit = $this->period === 'daily' ? 10 : 50;

        return $this->baseQuery()
            ->with('service')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getMachineStats(): array
    {
        $range = $this->getDateRange();

        $logs = MachineLog::with('machine')
            ->whereBetween('created_at', [$range['start'], $range['end']])
            ->get();

        $washLogs = $logs->where('cycle_type', 'wash');
        $dryLogs = $logs->where('cycle_type', 'dry');

        $perMachine = $logs->groupBy(function ($log) {
            return $log->machine_id.'|'.$log->cycle_type;
        })->map(function ($group) {
            $first = $group->first();
            return [
                'machine_name' => $first->machine?->name ?? 'Unknown',
                'cycle_type' => $first->cycle_type,
                'loads' => $group->count(),
                'kilos' => $group->sum('load_kilos'),
                'minutes' => $group->sum('duration_minutes'),
            ];
        })->sortBy('machine_name')->values()->toArray();

        return [
            'total_loads' => $logs->count(),
            'total_kilos' => $logs->sum('load_kilos'),
            'total_minutes' => $logs->sum('duration_minutes'),
            'wash_loads' => $washLogs->count(),
            'wash_kilos' => $washLogs->sum('load_kilos'),
            'wash_minutes' => $washLogs->sum('duration_minutes'),
            'dry_loads' => $dryLogs->count(),
            'dry_kilos' => $dryLogs->sum('load_kilos'),
            'dry_minutes' => $dryLogs->sum('duration_minutes'),
            'by_machine' => $perMachine,
        ];
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
