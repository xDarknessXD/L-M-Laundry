<div class="p-8 max-w-3xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('transactions') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-high hover:bg-surface-container-highest transition-all">
            <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-black tracking-tight text-primary">Edit Transaction</h2>
            <p class="text-on-surface-variant text-sm font-medium font-mono">{{ $transaction->order_number }}</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-xl p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant">Customer Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Full Name</label>
                    <input wire:model="customer_name" type="text"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm"/>
                    @error('customer_name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Phone</label>
                    <input wire:model="customer_phone" type="tel"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm"/>
                </div>
            </div>
        </div>

        <!-- Order Details (Read-only) -->
        <div class="bg-white rounded-xl p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant">Order Details</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-surface-container-highest rounded-lg p-4">
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Service</p>
                    <p class="text-sm font-bold text-on-surface mt-1">{{ $transaction->service?->name }}</p>
                </div>
                <div class="bg-surface-container-highest rounded-lg p-4">
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Weight</p>
                    <p class="text-sm font-bold text-on-surface mt-1">{{ number_format($transaction->kilos, 1) }} kg</p>
                </div>
                <div class="bg-surface-container-highest rounded-lg p-4">
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Total</p>
                    <p class="text-sm font-bold text-tertiary mt-1">₱{{ number_format($transaction->total_amount, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Payment & Status -->
        <div class="bg-white rounded-xl p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant">Payment & Status</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Amount Paid (₱)</label>
                    <input wire:model.live="amount_paid" type="number" step="0.01" min="0"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm"/>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Balance: <span class="font-bold text-error">₱{{ number_format(max(0, $transaction->total_amount - (float)$amount_paid), 2) }}</span>
                    </p>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Order Status</label>
                    <select wire:model="order_status"
                            class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex gap-4">
            <a href="{{ route('transactions') }}" class="flex-1 py-4 border border-outline-variant/30 rounded-full text-center text-sm font-bold hover:bg-surface-container-low transition-colors">Cancel</a>
            <button type="submit" class="flex-1 editorial-gradient text-white font-bold py-4 rounded-full flex items-center justify-center gap-2 shadow-lg hover:opacity-90 active:scale-[0.98] transition-all">
                <span wire:loading.remove wire:target="save">Update Transaction</span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>
