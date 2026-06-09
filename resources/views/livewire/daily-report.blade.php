<div class="p-8 max-w-7xl mx-auto space-y-6">
    <!-- Period Selector & Header -->
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div class="flex rounded-lg bg-surface-container-high p-1">
                <button wire:click="setPeriod('daily')"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200
                               {{ $period === 'daily' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    Daily
                </button>
                <button wire:click="setPeriod('weekly')"
                        class="px-4 py-2 rounded-md text-sm font-bold transition-all duration-200
                               {{ $period === 'weekly' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    Weekly
                </button>
                <button wire:click="setPeriod('monthly')"
                        class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200
                               {{ $period === 'monthly' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                    Monthly
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black tracking-tight text-primary">{{ $this->getPeriodLabel() }}</h2>
                <p class="text-on-surface-variant font-medium mt-1">{{ $this->getPeriodDescription() }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="previousDay" class="p-3 hover:bg-surface-container-high rounded-full transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>

                <input type="date" wire:model="selectedDate" wire:change="onDateChange($event.target.value)"
                       class="px-4 py-2 bg-white border border-outline-variant rounded-lg text-sm font-medium focus:ring-2 focus:ring-primary">
                @if($period !== 'daily')
                    <span class="text-xs font-semibold text-on-surface-variant min-w-[180px] text-center">
                        {{ $this->getDateRangeLabel() }}
                    </span>
                @endif

                <button wire:click="nextDay" class="p-3 hover:bg-surface-container-high rounded-full transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
                <button wire:click="goToToday" class="px-4 py-2 bg-primary-fixed text-on-primary-fixed font-bold rounded-full text-sm hover:opacity-90 transition-all">
                    Today
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">payments</span>
                </div>
                <span class="text-sm font-medium text-on-surface-variant">Total Revenue</span>
            </div>
            <p class="text-2xl font-black text-primary">
                @if($this->hasTransactions())
                    ₱{{ number_format($this->getTotalRevenue(), 2) }}
                @else
                    —
                @endif
            </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-tertiary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-tertiary">receipt_long</span>
                </div>
                <span class="text-sm font-medium text-on-surface-variant">Total Transactions</span>
            </div>
            <p class="text-2xl font-black text-tertiary">
                @if($this->hasTransactions())
                    {{ $this->getTotalTransactions() }}
                @else
                    —
                @endif
            </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-secondary">people</span>
                </div>
                <span class="text-sm font-medium text-on-surface-variant">Total Customers</span>
            </div>
            <p class="text-2xl font-black text-secondary">
                @if($this->hasTransactions())
                    {{ $this->getUniqueCustomers() }}
                @else
                    —
                @endif
            </p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary-fixed">local_laundry_service</span>
                </div>
                <span class="text-sm font-medium text-on-surface-variant">Services Rendered</span>
            </div>
            <p class="text-2xl font-black text-on-primary-fixed">
                @if($this->hasTransactions())
                    {{ $this->getTotalTransactions() }}
                @else
                    —
                @endif
            </p>
        </div>
    </div>

    @if($this->hasTransactions())
        <!-- Service Performance -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">analytics</span>
                Service Performance
            </h3>
            <div class="grid grid-cols-4 gap-4">
                @foreach($this->getServiceBreakdown() as $type => $data)
                <div class="p-4 bg-surface-container-highest rounded-lg">
                    <p class="text-xs font-bold text-on-surface-variant uppercase mb-1">{{ $data['name'] }}</p>
                    <p class="text-2xl font-black text-on-surface">{{ $data['count'] }}</p>
                    <p class="text-xs text-on-surface-variant">{{ $data['count'] == 1 ? 'transaction' : 'transactions' }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Add-ons & Supplies by Category -->
        @if(count($this->getAddonsByCategory()) > 0)
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-tertiary">inventory_2</span>
                Add-ons & Supplies
            </h3>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-container-high">
                        <th class="text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Category</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Quantity Used</th>
                        <th class="text-right px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getAddonsByCategory() as $category)
                    <tr class="border-b border-surface-container-highest/50">
                        <td class="px-4 py-3 text-sm font-medium text-on-surface">{{ $category['name'] }}</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-on-surface">{{ $category['quantity'] }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-on-surface">₱{{ number_format($category['total'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Payment Summary -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">account_balance_wallet</span>
                Payment Summary
            </h3>
            <div class="flex items-center justify-between p-4 bg-secondary-container/30 rounded-lg">
                <span class="text-sm font-medium text-on-surface">Total Cash Received</span>
                <span class="text-xl font-black text-secondary">₱{{ number_format($this->getCashPayments(), 2) }}</span>
            </div>
        </div>

        <!-- Machine Activity Summary -->
        @php $machineStats = $this->getMachineStats(); @endphp
        @if($machineStats['total_loads'] > 0)
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">precision_manufacturing</span>
                Machine Activity Summary
            </h3>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-container-high">
                        <th class="text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Cycle</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Loads</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Kilos</th>
                        <th class="text-right px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Minutes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-surface-container-highest/50">
                        <td class="px-4 py-3 text-sm font-semibold text-primary capitalize">
                            <span class="material-symbols-outlined text-[14px] align-middle mr-1">local_laundry_service</span>
                            Wash
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-on-surface">{{ $machineStats['wash_loads'] }}</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-on-surface">{{ number_format($machineStats['wash_kilos'], 1) }} kg</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-on-surface">{{ number_format($machineStats['wash_minutes']) }} min</td>
                    </tr>
                    <tr class="border-b border-surface-container-highest/50">
                        <td class="px-4 py-3 text-sm font-semibold text-tertiary capitalize">
                            <span class="material-symbols-outlined text-[14px] align-middle mr-1">dry_cleaning</span>
                            Dry
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-on-surface">{{ $machineStats['dry_loads'] }}</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-on-surface">{{ number_format($machineStats['dry_kilos'], 1) }} kg</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-on-surface">{{ number_format($machineStats['dry_minutes']) }} min</td>
                    </tr>
                    <tr class="bg-primary-fixed/5">
                        <td class="px-4 py-3 text-sm font-black text-on-surface">Total</td>
                        <td class="px-4 py-3 text-center text-sm font-black text-on-surface">{{ $machineStats['total_loads'] }}</td>
                        <td class="px-4 py-3 text-center text-sm font-black text-on-surface">{{ number_format($machineStats['total_kilos'], 1) }} kg</td>
                        <td class="px-4 py-3 text-right text-sm font-black text-on-surface">{{ number_format($machineStats['total_minutes']) }} min</td>
                    </tr>
                </tbody>
            </table>

            @if(count($machineStats['by_machine']) > 0)
            <h4 class="text-sm font-bold text-on-surface mb-3 mt-6">Machine Breakdown</h4>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-container-high">
                        <th class="text-left px-4 py-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Machine</th>
                        <th class="text-left px-4 py-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Cycle</th>
                        <th class="text-center px-4 py-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Loads</th>
                        <th class="text-center px-4 py-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Kilos</th>
                        <th class="text-right px-4 py-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Minutes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($machineStats['by_machine'] as $row)
                    <tr class="border-b border-surface-container-highest/50">
                        <td class="px-4 py-2 text-sm font-medium text-on-surface">{{ $row['machine_name'] }}</td>
                        <td class="px-4 py-2 text-sm capitalize font-semibold {{ $row['cycle_type'] === 'wash' ? 'text-primary' : 'text-tertiary' }}">
                            {{ $row['cycle_type'] }}
                        </td>
                        <td class="px-4 py-2 text-center text-sm font-bold text-on-surface">{{ $row['loads'] }}</td>
                        <td class="px-4 py-2 text-center text-sm font-bold text-on-surface">{{ number_format($row['kilos'], 1) }} kg</td>
                        <td class="px-4 py-2 text-right text-sm font-bold text-on-surface">{{ number_format($row['minutes']) }} min</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        @endif

        <!-- Latest Transactions -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                Latest Transactions
            </h3>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-surface-container-high">
                        <th class="text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Order #</th>
                        <th class="text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Customer</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Service</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Kilos</th>
                        <th class="text-right px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Amount</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getLatestTransactions() as $transaction)
                    <tr class="border-b border-surface-container-highest/50 hover:bg-surface-container-highest/30">
                        <td class="px-4 py-3 text-sm font-mono text-on-surface">{{ $transaction->order_number }}</td>
                        <td class="px-4 py-3 text-sm text-on-surface">{{ $transaction->customer_name }}</td>
                        <td class="px-4 py-3 text-center text-sm text-on-surface">{{ $transaction->service->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-center text-sm text-on-surface">{{ number_format($transaction->kilos, 1) }} kg</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-on-surface">₱{{ number_format($transaction->total_amount, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 text-[10px] font-bold rounded-full uppercase
                                {{ $transaction->payment_status === 'paid' ? 'bg-secondary-container text-on-secondary-fixed-variant' : 'bg-tertiary-fixed text-on-tertiary-fixed' }}">
                                {{ $transaction->payment_status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl p-16 shadow-sm text-center">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4">event_busy</span>
            <h3 class="text-xl font-bold text-on-surface mb-2">No Transactions</h3>
            <p class="text-on-surface-variant">No transactions found for {{ $this->getDateRangeLabel() }}</p>
            <button wire:click="goToToday" class="mt-6 px-6 py-3 bg-primary-fixed text-on-primary-fixed font-bold rounded-full text-sm hover:opacity-90 transition-all inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">today</span>
                Go to current {{ $period }}
            </button>
        </div>
    @endif
</div>
