<div class="p-8 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-primary">Customers</h2>
            <p class="text-on-surface-variant font-medium mt-1">Manage customer records and view history</p>
        </div>
        <button wire:click="openAdd" class="flex items-center gap-2 px-8 py-3 editorial-gradient text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all active:scale-95">
            <span class="material-symbols-outlined">person_add</span> Add Customer
        </button>
    </div>

    <!-- Search -->
    <div class="relative max-w-md">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">search</span>
        <input wire:model.live.debounce.300ms="search" type="text"
               class="w-full pl-12 pr-4 py-3 bg-white border-none rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-primary-fixed"
               placeholder="Search customers by name or phone..."/>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-surface-container-high">
                    <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Customer</th>
                    <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Phone</th>
                    <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Transactions</th>
                    <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Added</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="border-b border-surface-container-highest/50 hover:bg-surface-container-highest/20 transition-colors cursor-pointer"
                    wire:click="viewHistory({{ $customer->id }})">
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-on-surface">{{ $customer->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-on-surface-variant">{{ $customer->phone ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 bg-secondary-container text-on-secondary-fixed-variant text-xs font-bold rounded-full">
                            {{ $customer->transactions_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-on-surface-variant">
                        {{ $customer->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 justify-end">
                            <button wire:click.stop="openEdit({{ $customer->id }})" class="text-primary-container hover:text-primary transition-colors p-1">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </button>
                            <button x-on:click.prevent.stop="$dispatch('confirm-action', { message: 'Delete {{ addslashes($customer->name) }}?', method: 'delete', params: [{{ $customer->id }}] })" class="text-error hover:text-error/80 transition-colors p-1">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>

                @if($selectedCustomerId === $customer->id)
                <tr>
                    <td colspan="5" class="px-6 py-4 bg-surface-container-highest/30">
                        <h4 class="font-bold text-on-surface text-sm mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">history</span>
                            Transaction History — {{ $customer->name }}
                        </h4>
                        @if($customerTransactions->count() > 0)
                        <table class="w-full text-sm bg-white rounded-lg overflow-hidden">
                            <thead>
                                <tr class="border-b border-surface-container-high">
                                    <th class="text-left px-4 py-2 text-[10px] font-black uppercase text-on-surface-variant/60">Order #</th>
                                    <th class="text-left px-4 py-2 text-[10px] font-black uppercase text-on-surface-variant/60">Service</th>
                                    <th class="text-center px-4 py-2 text-[10px] font-black uppercase text-on-surface-variant/60">Kilos</th>
                                    <th class="text-right px-4 py-2 text-[10px] font-black uppercase text-on-surface-variant/60">Amount</th>
                                    <th class="text-center px-4 py-2 text-[10px] font-black uppercase text-on-surface-variant/60">Status</th>
                                    <th class="text-right px-4 py-2 text-[10px] font-black uppercase text-on-surface-variant/60">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customerTransactions as $txn)
                                <tr class="border-b border-surface-container-highest/50">
                                    <td class="px-4 py-2 text-xs font-mono text-on-surface">{{ $txn->order_number }}</td>
                                    <td class="px-4 py-2 text-xs text-on-surface">{{ $txn->service->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-xs text-center">{{ number_format($txn->kilos, 1) }} kg</td>
                                    <td class="px-4 py-2 text-xs text-right font-bold">₱{{ number_format($txn->total_amount, 2) }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="px-2 py-0.5 text-[9px] font-bold rounded-full uppercase
                                            {{ $txn->payment_status === 'paid' ? 'bg-secondary-container text-on-secondary-fixed-variant' : 'bg-tertiary-fixed text-on-tertiary-fixed' }}">
                                            {{ $txn->payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-xs text-right text-on-surface-variant">{{ $txn->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-sm text-on-surface-variant">No transactions found.</p>
                        @endif
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-5xl mb-3 opacity-20 block">person_off</span>
                        <p class="font-medium text-on-surface-variant">No customers found</p>
                        @if($search)
                        <p class="text-sm text-on-surface-variant mt-1">No results for "{{ $search }}"</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>

    <!-- Add/Edit Customer Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
         x-data x-on:click.self="$wire.set('showModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform scale-95 opacity-0"
             x-transition:enter-end="transform scale-100 opacity-100">
            <h3 class="text-lg font-bold text-on-surface mb-4">{{ $editing ? 'Edit Customer' : 'New Customer' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Full Name *</label>
                    <input wire:model="name" type="text"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"
                           placeholder="Juan Dela Cruz"/>
                    @error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Phone Number</label>
                    <input wire:model="phone" type="tel"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"
                           placeholder="+63 900 000 0000"/>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="$set('showModal', false)" class="flex-1 py-3 border border-outline-variant/30 rounded-full text-sm font-bold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button wire:click="save" class="flex-1 py-3 editorial-gradient text-white rounded-full text-sm font-bold shadow-lg hover:opacity-90 transition-all">
                    <span wire:loading.remove wire:target="save">{{ $editing ? 'Update' : 'Add Customer' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <x-confirm-modal />
</div>
