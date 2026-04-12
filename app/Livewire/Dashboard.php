<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\User;
use App\Models\InventoryItem;
use App\Models\MachineLog;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        $activeOrders = Transaction::whereIn('order_status', ['pending', 'in_progress'])->count();
        $pendingPayments = Transaction::where('payment_status', '!=', 'paid')->sum('balance');
        $totalKilosToday = Transaction::whereDate('created_at', today())->sum('kilos');
        $machinesInUse = MachineLog::where('status', 'in_progress')->count();
        $totalMachines = \App\Models\Machine::count();

        $recentTransactions = Transaction::with(['service', 'creator'])
            ->latest()
            ->take(10)
            ->get();

        $lowStockItems = InventoryItem::where('status', 'low_stock')
            ->orWhere('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->get();

        $pendingUsers = $isAdmin ? User::where('status', 'pending')->get() : collect();

        // Weekly transaction data for chart
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyData[] = [
                'label' => $date->format('D'),
                'count' => Transaction::whereDate('created_at', $date)->count(),
            ];
        }

        return view('livewire.dashboard', compact(
            'isAdmin', 'activeOrders', 'pendingPayments', 'totalKilosToday',
            'machinesInUse', 'totalMachines', 'recentTransactions', 'lowStockItems',
            'pendingUsers', 'weeklyData'
        ));
    }
}
