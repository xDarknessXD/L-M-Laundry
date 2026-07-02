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
                <button x-on:click="window.print()" class="px-4 py-2 bg-surface-container-highest text-primary font-bold rounded-full text-sm hover:opacity-90 transition-all flex items-center gap-2 no-print">
                    <span class="material-symbols-outlined text-lg">print</span> Print Report
                </button>
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

    <!-- SCREEN VIEW: Full Report -->
    <div id="printable-report" class="space-y-6 screen-only">
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

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl p-6 shadow-sm" wire:ignore
                x-data="{
                    chart: null,
                    labels: {{ Js::from($dailyChart['labels']) }},
                    values: {{ Js::from($dailyChart['revenues']) }},
                    init() {
                        this.chart = new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: this.labels,
                                datasets: [{ data: this.values, backgroundColor: '#000a1e', borderRadius: 8, barThickness: 16 }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, grid: { color: '#e3e2e6' }, ticks: { callback: v => '₱' + v } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                        window.__charts = window.__charts || {};
                        window.__charts.dailyRevenue = this.chart;
                    }
                }">
                <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">bar_chart</span>
                    Hourly Revenue — {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                </h3>
                <canvas x-ref="canvas" height="200" class="w-full"></canvas>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm" wire:ignore
                x-data="{
                    chart: null,
                    labels: {{ Js::from($monthlyChart['labels']) }},
                    values: {{ Js::from($monthlyChart['counts']) }},
                    init() {
                        this.chart = new Chart(this.$refs.canvas, {
                            type: 'bar',
                            data: {
                                labels: this.labels,
                                datasets: [{ data: this.values, backgroundColor: '#3b6751', borderRadius: 6, barThickness: 10 }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#e3e2e6' } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                        window.__charts = window.__charts || {};
                        window.__charts.monthlyTrend = this.chart;
                    }
                }">
                <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-tertiary">trending_up</span>
                    Monthly Trend — {{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}
                </h3>
                <canvas x-ref="canvas" height="200" class="w-full"></canvas>
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

<!-- PRINT LAYOUT -->
<div id="printable-report-print" class="space-y-5 hidden print:block">
    <div class="mb-4">
        <h2 class="text-xl font-black">{{ $this->getPeriodLabel() }} — {{ $this->getDateRangeLabel() }}</h2>
        <p class="text-sm text-gray-500">Printed on {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="grid grid-cols-4 gap-3">
        <div class="border rounded p-3 text-center">
            <p class="text-xs text-gray-500 uppercase">Revenue</p>
            <p class="text-base font-bold">₱{{ number_format($this->getTotalRevenue(), 2) }}</p>
        </div>
        <div class="border rounded p-3 text-center">
            <p class="text-xs text-gray-500 uppercase">Transactions</p>
            <p class="text-base font-bold">{{ $this->getTotalTransactions() }}</p>
        </div>
        <div class="border rounded p-3 text-center">
            <p class="text-xs text-gray-500 uppercase">Customers</p>
            <p class="text-base font-bold">{{ $this->getUniqueCustomers() }}</p>
        </div>
        <div class="border rounded p-3 text-center">
            <p class="text-xs text-gray-500 uppercase">Cash Received</p>
            <p class="text-base font-bold">₱{{ number_format($this->getCashPayments(), 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="border rounded p-4" wire:ignore
            x-data="{
                chart: null,
                labels: {{ Js::from($dailyChart['labels']) }},
                values: {{ Js::from($dailyChart['revenues']) }},
                init() {
                    this.chart = new Chart(this.$refs.canvas, {
                        type: 'bar',
                        data: { labels: this.labels, datasets: [{ data: this.values, backgroundColor: '#000a1e', borderRadius: 6, barThickness: 14 }] },
                        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#eee' } }, x: { grid: { display: false } } } }
                    });
                    window.__charts = window.__charts || {};
                    window.__charts.printDaily = this.chart;
                }
            }">
            <h3 class="font-bold text-sm mb-3">Hourly Revenue</h3>
            <canvas x-ref="canvas" height="180" class="w-full"></canvas>
        </div>

        <div class="border rounded p-4" wire:ignore
            x-data="{
                chart: null,
                labels: {{ Js::from($monthlyChart['labels']) }},
                values: {{ Js::from($monthlyChart['counts']) }},
                init() {
                    this.chart = new Chart(this.$refs.canvas, {
                        type: 'bar',
                        data: { labels: this.labels, datasets: [{ data: this.values, backgroundColor: '#3b6751', borderRadius: 4, barThickness: 8 }] },
                        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#eee' } }, x: { grid: { display: false } } } }
                    });
                    window.__charts = window.__charts || {};
                    window.__charts.printTrend = this.chart;
                }
            }">
            <h3 class="font-bold text-sm mb-3">Transaction Trend</h3>
            <canvas x-ref="canvas" height="180" class="w-full"></canvas>
        </div>
    </div>

    @php $printMachineStats = $this->getMachineStats(); @endphp
    @if($printMachineStats['total_loads'] > 0)
    <div class="border rounded p-4">
        <h3 class="font-bold text-sm mb-3">Machine Activity</h3>
        <table class="w-full text-sm">
            <thead><tr class="border-b"><th class="text-left py-1 text-xs">Cycle</th><th class="text-center py-1 text-xs">Loads</th><th class="text-center py-1 text-xs">Kilos</th><th class="text-right py-1 text-xs">Minutes</th></tr></thead>
            <tbody>
                <tr class="border-b border-gray-100"><td class="py-1">Wash</td><td class="text-center py-1">{{ $printMachineStats['wash_loads'] }}</td><td class="text-center py-1">{{ number_format($printMachineStats['wash_kilos'], 1) }} kg</td><td class="text-right py-1">{{ number_format($printMachineStats['wash_minutes']) }} min</td></tr>
                <tr class="border-b border-gray-100"><td class="py-1">Dry</td><td class="text-center py-1">{{ $printMachineStats['dry_loads'] }}</td><td class="text-center py-1">{{ number_format($printMachineStats['dry_kilos'], 1) }} kg</td><td class="text-right py-1">{{ number_format($printMachineStats['dry_minutes']) }} min</td></tr>
                <tr><td class="py-1 font-bold">Total</td><td class="text-center py-1 font-bold">{{ $printMachineStats['total_loads'] }}</td><td class="text-center py-1 font-bold">{{ number_format($printMachineStats['total_kilos'], 1) }} kg</td><td class="text-right py-1 font-bold">{{ number_format($printMachineStats['total_minutes']) }} min</td></tr>
            </tbody>
        </table>
    </div>
    @endif

    @if($this->hasTransactions())
    <div class="border rounded p-4">
        <h3 class="font-bold text-sm mb-3">Latest Transactions</h3>
        <table class="w-full text-sm">
            <thead><tr class="border-b"><th class="text-left py-1 text-xs">Order #</th><th class="text-left py-1 text-xs">Customer</th><th class="text-left py-1 text-xs">Service</th><th class="text-center py-1 text-xs">Kilos</th><th class="text-right py-1 text-xs">Amount</th><th class="text-center py-1 text-xs">Status</th></tr></thead>
            <tbody>
                @foreach($this->getLatestTransactions() as $transaction)
                <tr class="border-b border-gray-100">
                    <td class="py-1 text-[11px] font-mono">{{ $transaction->order_number }}</td>
                    <td class="py-1 text-[11px]">{{ $transaction->customer_name }}</td>
                    <td class="py-1 text-[11px]">{{ $transaction->service->name ?? 'N/A' }}</td>
                    <td class="py-1 text-[11px] text-center">{{ number_format($transaction->kilos, 1) }}</td>
                    <td class="py-1 text-[11px] text-right font-bold">₱{{ number_format($transaction->total_amount, 2) }}</td>
                    <td class="py-1 text-[11px] text-center font-bold capitalize">{{ $transaction->payment_status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('refresh-charts', (event) => {
        const updateChart = (chart, labels, values) => {
            if (!chart) return;
            chart.data.labels = labels;
            chart.data.datasets[0].data = values;
            chart.update();
        };
        if (window.__charts) {
            updateChart(window.__charts.dailyRevenue, event.daily.labels, event.daily.revenues);
            updateChart(window.__charts.monthlyTrend, event.monthly.labels, event.monthly.counts);
            updateChart(window.__charts.printDaily, event.daily.labels, event.daily.revenues);
            updateChart(window.__charts.printTrend, event.monthly.labels, event.monthly.counts);
        }
    });
});

window.addEventListener('beforeprint', () => {
    if (window.__charts) {
        window.__charts.printDaily?.resize();
        window.__charts.printTrend?.resize();
        window.__charts.dailyRevenue?.resize();
        window.__charts.monthlyTrend?.resize();
    }
});
</script>
</div>
