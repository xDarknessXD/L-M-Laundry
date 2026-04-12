<div class="p-8 max-w-7xl mx-auto space-y-8">
    <!-- Low Stock Alert -->
    @if($isAdmin && $lowStockItems->count() > 0)
    <div class="bg-error-container text-on-error-container p-4 rounded-lg flex items-center justify-between animate-pulse">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined">warning</span>
            <p class="text-sm font-medium">Low Stock Alert: {{ $lowStockItems->count() }} item(s) below threshold. Restock recommended.</p>
        </div>
        <a href="{{ route('inventory') }}" class="text-xs font-bold uppercase tracking-wider bg-on-error-container/10 px-4 py-2 rounded-full hover:bg-on-error-container/20 transition-colors">Manage Inventory</a>
    </div>
    @endif

    <!-- Pending Users Alert -->
    @if($isAdmin && $pendingUsers->count() > 0)
    <div class="bg-tertiary-fixed text-on-tertiary-fixed p-4 rounded-lg flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined animate-pulse">person_add</span>
            <p class="text-sm font-medium">{{ $pendingUsers->count() }} pending account(s) awaiting approval.</p>
        </div>
        <a href="{{ route('users') }}" class="text-xs font-bold uppercase tracking-wider bg-tertiary-container/30 px-4 py-2 rounded-full hover:bg-tertiary-container/50 transition-colors">Review</a>
    </div>
    @endif

    <!-- Page Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-primary">Operations Overview</h2>
            <p class="text-on-surface-variant font-medium mt-1">Real-time status of J&M Laundry Lounge</p>
        </div>
        @if($isAdmin)
        <div class="flex gap-3">
            <a href="{{ route('machine-logs') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-primary font-bold rounded-full shadow-sm hover:bg-surface-container-high transition-all active:scale-95">
                <span class="material-symbols-outlined">history</span> View Logs
            </a>
            <a href="{{ route('transactions.create') }}" class="flex items-center gap-2 px-8 py-3 editorial-gradient text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all active:scale-95">
                <span class="material-symbols-outlined">add</span> New Transaction
            </a>
        </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
         x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
        <!-- Active Orders -->
        <div class="bg-white p-6 rounded-xl shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] flex flex-col justify-between h-40"
             x-show="shown" x-transition.duration.500ms>
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Active Orders</span>
                <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
            </div>
            <div>
                <span class="text-4xl font-extrabold tracking-tighter text-on-surface"
                      x-data="{ count: 0, target: {{ $activeOrders }} }"
                      x-init="let i = setInterval(() => { if(count >= target) { clearInterval(i); return; } count++; }, 50)"
                      x-text="count">0</span>
                <p class="text-xs text-secondary mt-1 font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    Active now
                </p>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white p-6 rounded-xl shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] flex flex-col justify-between h-40"
             x-show="shown" x-transition.duration.500ms.delay.100ms>
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Pending Payments</span>
                <div class="w-10 h-10 rounded-full bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-bold text-tertiary">₱</span>
                    <span class="text-4xl font-extrabold tracking-tighter text-on-surface">{{ number_format($pendingPayments, 2) }}</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1 font-medium">Outstanding balance</p>
            </div>
        </div>

        <!-- Total Kilos Today -->
        <div class="bg-white p-6 rounded-xl shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] flex flex-col justify-between h-40"
             x-show="shown" x-transition.duration.500ms.delay.200ms>
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Total Kilos Today</span>
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed">
                    <span class="material-symbols-outlined">scale</span>
                </div>
            </div>
            <div>
                <span class="text-4xl font-extrabold tracking-tighter text-on-surface">{{ number_format($totalKilosToday, 1) }}</span>
                <p class="text-xs text-on-surface-variant mt-1 font-medium">Across all loads</p>
            </div>
        </div>

        <!-- Machines In Use -->
        <div class="bg-white p-6 rounded-xl shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] flex flex-col justify-between h-40"
             x-show="shown" x-transition.duration.500ms.delay.300ms>
            <div class="flex justify-between items-start">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Machines In Use</span>
                <div class="w-10 h-10 rounded-full bg-on-secondary-fixed-variant flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">local_laundry_service</span>
                </div>
            </div>
            <div>
                <span class="text-4xl font-extrabold tracking-tighter text-on-surface">{{ $machinesInUse }}/{{ $totalMachines }}</span>
                <div class="w-full bg-surface-container-highest h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-secondary h-full rounded-full transition-all duration-1000" style="width: {{ $totalMachines > 0 ? ($machinesInUse / $totalMachines * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-on-surface">Recent Transactions</h3>
                <a href="{{ route('transactions') }}" class="text-sm font-semibold text-primary-container hover:underline">View All</a>
            </div>
            <div class="space-y-1">
                <div class="grid grid-cols-5 px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">
                    <div class="col-span-2">Customer / Service</div>
                    <div class="text-center">Weight</div>
                    <div class="text-center">Status</div>
                    <div class="text-right">Total</div>
                </div>
                @forelse($recentTransactions as $txn)
                <div class="grid grid-cols-5 items-center px-6 py-4 bg-white rounded-sm hover:bg-surface-container-highest transition-colors cursor-pointer">
                    <div class="col-span-2 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant">
                            <span class="font-bold text-xs">{{ strtoupper(substr($txn->customer_name, 0, 2)) }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-on-surface text-sm">{{ $txn->customer_name }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ $txn->service?->name ?? 'N/A' }} • {{ $txn->order_number }}</p>
                        </div>
                    </div>
                    <div class="text-center text-sm font-medium text-on-surface">{{ number_format($txn->kilos, 1) }} kg</div>
                    <div class="flex justify-center">
                        @if($txn->payment_status === 'paid')
                            <span class="px-3 py-1 bg-secondary-container text-on-secondary-fixed-variant text-[10px] font-bold rounded-full">PAID</span>
                        @elseif($txn->payment_status === 'partial')
                            <span class="px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold rounded-full">PARTIAL</span>
                        @else
                            <span class="px-3 py-1 bg-error-container text-on-error-container text-[10px] font-bold rounded-full">UNPAID</span>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-tertiary">₱</span>
                        <span class="text-sm font-black text-on-surface">{{ number_format($txn->total_amount, 2) }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl mb-2 opacity-30">receipt_long</span>
                    <p class="text-sm font-medium">No transactions yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar: Chart + Quick Stats -->
        <div class="space-y-8">
            <!-- Weekly Chart -->
            <div class="bg-surface-container-low p-6 rounded-xl">
                <h3 class="font-bold text-on-surface mb-4">This Week</h3>
                <canvas id="weeklyChart" height="200"
                    x-data
                    x-init="
                        import('https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js').then(mod => {
                            const Chart = mod.default || mod.Chart;
                            new Chart($el, {
                                type: 'bar',
                                data: {
                                    labels: {{ Js::from(collect($weeklyData)->pluck('label')) }},
                                    datasets: [{
                                        data: {{ Js::from(collect($weeklyData)->pluck('count')) }},
                                        backgroundColor: '#3b6751',
                                        borderRadius: 8,
                                        barThickness: 20
                                    }]
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
                        });
                    "></canvas>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="font-bold text-on-surface mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('transactions') }}" class="w-full py-3 border border-outline-variant/30 rounded-lg text-sm font-bold text-primary hover:bg-surface-container-low transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">receipt_long</span> All Transactions
                    </a>
                    <a href="{{ route('inventory') }}" class="w-full py-3 border border-outline-variant/30 rounded-lg text-sm font-bold text-primary hover:bg-surface-container-low transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">inventory_2</span> Inventory
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
