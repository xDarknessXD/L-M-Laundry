<div class="p-8 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-primary">Transactions</h2>
            <p class="text-on-surface-variant font-medium mt-1">View and manage all laundry orders</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="flex items-center gap-2 px-8 py-3 editorial-gradient text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all active:scale-95">
            <span class="material-symbols-outlined">add</span> New Transaction
        </a>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 bg-white p-4 rounded-xl shadow-sm">
        <div class="flex-1 min-w-[200px] relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant text-xl">search</span>
            <input wire:model.live.debounce.300ms="search" type="text"
                   class="w-full pl-12 pr-4 py-3 bg-surface-container-highest border-none rounded-lg text-sm focus:ring-2 focus:ring-primary-fixed"
                   placeholder="Search customer or order #"/>
        </div>
        <select wire:model.live="filterPayment"
                class="bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed min-w-[160px]">
            <option value="">All Payments</option>
            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
            <option value="unpaid">Unpaid</option>
        </select>
        <select wire:model.live="filterOrder"
                class="bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed min-w-[160px]">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-container-high">
                        <th class="text-left px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Order #</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Customer</th>
                        <th class="text-left px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Service</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Weight</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Payment</th>
                        <th class="text-center px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Status</th>
                        <th class="text-right px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Total</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Date</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                    <tr class="border-b border-surface-container-highest/50 hover:bg-surface-container-highest/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono font-bold text-primary-container">{{ $txn->order_number }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-on-surface">{{ $txn->customer_name }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ $txn->customer_phone }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-on-surface">{{ $txn->service?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-center font-medium">{{ number_format($txn->kilos, 1) }} kg</td>
                        <td class="px-6 py-4 text-center">
                            @if($txn->payment_status === 'paid')
                                <span class="px-3 py-1 bg-secondary-container text-on-secondary-fixed-variant text-[10px] font-bold rounded-full">PAID</span>
                            @elseif($txn->payment_status === 'partial')
                                <span class="px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold rounded-full">PARTIAL</span>
                            @else
                                <span class="px-3 py-1 bg-error-container text-on-error-container text-[10px] font-bold rounded-full">UNPAID</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($txn->order_status === 'completed')
                                <span class="px-3 py-1 bg-secondary-container text-on-secondary-fixed-variant text-[10px] font-bold rounded-full">DONE</span>
                            @elseif($txn->order_status === 'in_progress')
                                <span class="px-3 py-1 bg-primary-fixed text-on-primary-fixed text-[10px] font-bold rounded-full">IN PROGRESS</span>
                            @elseif($txn->order_status === 'cancelled')
                                <span class="px-3 py-1 bg-surface-container-high text-on-surface-variant text-[10px] font-bold rounded-full">CANCELLED</span>
                            @else
                                <span class="px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold rounded-full animate-pulse">PENDING</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-xs font-bold text-tertiary">₱</span>
                            <span class="text-sm font-black">{{ number_format($txn->total_amount, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-on-surface-variant">{{ $txn->created_at->format('M d, h:i A') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('transactions.edit', $txn) }}" class="text-primary-container hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-16 text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl mb-3 opacity-20">receipt_long</span>
                            <p class="text-sm font-medium">No transactions found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-surface-container-high">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
