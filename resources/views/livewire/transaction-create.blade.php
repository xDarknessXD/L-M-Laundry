<div class="flex min-h-[calc(100vh-4rem)]">
    <!-- Left Panel: Form -->
    <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
        <div class="max-w-2xl mx-auto space-y-8">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <a href="{{ route('transactions') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-high hover:bg-surface-container-highest transition-all">
                    <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-primary">New Transaction</h2>
                    <p class="text-on-surface-variant text-sm font-medium">Create a new laundry order</p>
                </div>
            </div>

            <form wire:submit="submit" class="space-y-8">
                <!-- Customer Info -->
                <div class="bg-white rounded-xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">person</span> Customer Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1 block">Full Name *</label>
                            <input wire:model="customer_name" type="text"
                                   class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm"
                                   placeholder="Juan Dela Cruz"/>
                            @error('customer_name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1 block">Phone Number</label>
                            <input wire:model="customer_phone" type="tel"
                                   class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm"
                                   placeholder="+63 900 000 0000"/>
                        </div>
                    </div>
                </div>

                <!-- Service Selection -->
                <div class="bg-white rounded-xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">local_laundry_service</span> Service Details
                    </h3>

                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-2 block">Select Service *</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($services as $service)
                            <label class="cursor-pointer" wire:key="service-{{ $service->id }}">
                                <input type="radio" wire:model.live="service_id" value="{{ $service->id }}" class="hidden peer"/>
                                <div class="peer-checked:ring-2 peer-checked:ring-primary-container peer-checked:bg-primary-fixed/10 bg-surface-container-highest rounded-lg p-4 hover:bg-surface-container-high transition-all">
                                    <p class="font-bold text-sm text-on-surface">{{ $service->name }}</p>
                                    <p class="text-xs text-on-surface-variant capitalize">{{ str_replace('_', ' ', $service->type) }}</p>
                                    <p class="text-xs mt-1 font-bold text-tertiary">₱{{ number_format($service->price_per_kilo, 2) }}/kg</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('service_id') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1 block">Material Type</label>
                            <select wire:model.live="material_type"
                                    class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm">
                                <option value="light">Light / Everyday</option>
                                <option value="jeans">Jeans / Medium</option>
                                <option value="heavy">Heavy / Comforter</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-on-surface-variant mb-1 block">Weight (kg) *</label>
                            <input wire:model.live.debounce.300ms="kilos" type="number" step="0.1" min="1"
                                   class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm"
                                   placeholder="5.0"/>
                            @if($selectedService && $kilos < $selectedService->minimum_kilos)
                                <p class="text-xs text-tertiary mt-1">Min {{ $selectedService->minimum_kilos }} kg — will be charged at minimum</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Addons -->
                <div class="bg-white rounded-xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span> Add-ons
                        </h3>
                        <button type="button" wire:click="$set('showAddonModal', true)"
                                class="text-xs font-bold text-primary-container bg-primary-fixed/20 px-4 py-2 rounded-full hover:bg-primary-fixed/30 transition-colors">
                            + Add Item
                        </button>
                    </div>

                    @if(count($addons) > 0)
                    <div class="space-y-2">
                        @foreach($addons as $index => $addon)
                        <div class="flex items-center justify-between bg-surface-container-highest rounded-lg px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-on-surface">{{ $addon['name'] }}</p>
                                <p class="text-xs text-on-surface-variant">₱{{ number_format($addon['price'], 2) }} × {{ $addon['quantity'] }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-sm text-on-surface">₱{{ number_format($addon['price'] * $addon['quantity'], 2) }}</span>
                                <button type="button" wire:click="removeAddon({{ $index }})" class="text-error hover:bg-error-container/30 p-1 rounded-full transition-colors">
                                    <span class="material-symbols-outlined text-lg">close</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-on-surface-variant text-center py-4">No addons selected</p>
                    @endif
                </div>

                <!-- Payment Details -->
                <div class="bg-white rounded-xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">payments</span> Payment
                    </h3>

                    <div>
                        <div class="grid grid-cols-3 gap-3 mt-2">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="payment_type" value="unpaid" class="hidden peer"/>
                                <div class="peer-checked:ring-2 peer-checked:ring-error peer-checked:bg-error-container/30 bg-surface-container-highest rounded-lg p-3 text-center transition-all">
                                    <p class="font-bold text-sm text-on-surface">Unpaid</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="payment_type" value="partial" class="hidden peer"/>
                                <div class="peer-checked:ring-2 peer-checked:ring-tertiary peer-checked:bg-tertiary-container/30 bg-surface-container-highest rounded-lg p-3 text-center transition-all">
                                    <p class="font-bold text-sm text-on-surface">Partial (Min 50%)</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="payment_type" value="full" class="hidden peer"/>
                                <div class="peer-checked:ring-2 peer-checked:ring-primary peer-checked:bg-primary-container/30 bg-surface-container-highest rounded-lg p-3 text-center transition-all">
                                    <p class="font-bold text-sm text-on-surface">Fully Paid</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    @if($payment_type === 'partial')
                    <div class="mt-4 p-4 border border-outline-variant/30 rounded-lg bg-surface-container-lowest">
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Amount Paid (₱)</label>
                        <input wire:model.live.debounce.300ms="amount_paid" type="number" step="0.01" min="{{ ceil($totalAmount * 0.5) }}" max="{{ $totalAmount }}"
                               class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-tertiary text-sm"/>
                        <p class="text-xs text-tertiary mt-1">Minimum down payment: ₱{{ number_format(ceil($totalAmount * 0.5), 2) }}</p>
                        @error('amount_paid') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <!-- Important Notice -->
                    <div class="flex items-start gap-2 mt-4 p-3 rounded-lg bg-surface-container-highest border-l-4 border-error">
                        <span class="material-symbols-outlined text-error text-sm mt-0.5">warning</span>
                        <p class="text-xs font-bold text-error">Laundry cannot be released unless fully paid.</p>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full editorial-gradient text-white font-bold py-4 rounded-full flex items-center justify-center gap-2 shadow-lg shadow-primary/20 hover:opacity-90 active:scale-[0.98] transition-all">
                    <span wire:loading.remove wire:target="submit">Create Transaction</span>
                    <span wire:loading.remove wire:target="submit" class="material-symbols-outlined">check_circle</span>
                    <span wire:loading wire:target="submit" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Processing...
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Right Panel: Receipt Preview -->
    <div class="w-[380px] bg-surface-container-low p-8 border-l border-outline-variant/20 hidden lg:flex flex-col items-center">
        <div class="sticky top-28 w-full space-y-6">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">receipt</span> Receipt Preview
            </h3>

            <div class="bg-white rounded-xl p-6 shadow-sm receipt-jagged space-y-4 pb-10">
                <div class="text-center border-b border-dashed border-outline-variant/30 pb-4">
                    <p class="font-black text-primary text-lg">J&M Laundry</p>
                    <p class="text-[10px] text-on-surface-variant uppercase tracking-widest">Official Receipt</p>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Customer</span>
                        <span class="font-semibold text-on-surface">{{ $customer_name ?: '—' }}</span>
                    </div>
                    @if($selectedService)
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Service</span>
                        <span class="font-semibold text-on-surface">{{ $selectedService->name }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Weight</span>
                        <span class="font-semibold text-on-surface">{{ number_format((float)$kilos, 1) }} kg</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Material</span>
                        <span class="font-semibold text-on-surface capitalize">{{ $material_type }}</span>
                    </div>
                </div>

                <div class="border-t border-dashed border-outline-variant/30 pt-3 space-y-1">
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">Subtotal</span>
                        <span class="font-semibold">₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($addonsTotal > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">Addons</span>
                        <span class="font-semibold">₱{{ number_format($addonsTotal, 2) }}</span>
                    </div>
                    @endif
                </div>

                <div class="border-t-2 border-primary pt-3 mt-3">
                    <div class="flex justify-between items-baseline mb-2">
                        <span class="text-sm font-bold text-on-surface">TOTAL</span>
                        <div>
                            <span class="text-sm font-bold text-tertiary">₱</span>
                            <span class="text-2xl font-black text-primary">{{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>

                    @if($payment_type !== 'unpaid')
                    <div class="flex justify-between items-center text-sm text-on-surface-variant mb-1">
                        <span>Amount Paid</span>
                        <span class="font-bold text-on-surface">₱{{ number_format((float)$amount_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-error">
                        <span class="font-bold">Balance</span>
                        <span class="font-bold text-[16px]">₱{{ number_format(max(0, $totalAmount - (float)$amount_paid), 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Addon Modal -->
    <div x-data="{ open: @entangle('showAddonModal') }"
         x-show="open" style="display: none;"
         x-init="$watch('open', value => document.body.style.overflow = value ? 'hidden' : '')"
         class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        
        <!-- Backdrop click listener -->
        <div class="absolute inset-0" x-on:click="open = false"></div>

        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl relative z-10"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform scale-95 opacity-0"
             x-transition:enter-end="transform scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="transform scale-100 opacity-100"
             x-transition:leave-end="transform scale-95 opacity-0">
            <h3 class="text-lg font-bold text-on-surface mb-4">Add Supply Item</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Select Item</label>
                    <select wire:model="selectedAddonId"
                            class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm">
                        <option value="">Choose an item...</option>
                        @foreach($inventoryItems as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} — ₱{{ number_format($item->price, 2) }} ({{ $item->stock_quantity }} in stock)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Quantity</label>
                    <input wire:model="addonQty" type="number" min="1"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed text-sm"/>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" x-on:click="open = false"
                        class="flex-1 py-3 border border-outline-variant/30 rounded-full text-sm font-bold hover:bg-surface-container-low transition-colors">
                    Cancel
                </button>
                <button type="button" wire:click="addAddon"
                        class="flex-1 py-3 editorial-gradient text-white rounded-full text-sm font-bold shadow-lg hover:opacity-90 transition-all">
                    Add
                </button>
            </div>
        </div>
    </div>
</div>
