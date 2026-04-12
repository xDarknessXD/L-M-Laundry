<div class="p-8 max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-3xl font-black tracking-tight text-primary">Settings</h2>
        <p class="text-on-surface-variant font-medium mt-1">Configure services, pricing, and system preferences</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 bg-surface-container-high rounded-full p-1 w-fit">
        <button wire:click="$set('activeTab', 'services')"
                class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ $activeTab === 'services' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
            Services & Pricing
        </button>
        <button wire:click="$set('activeTab', 'account')"
                class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ $activeTab === 'account' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
            Account
        </button>
        <button wire:click="$set('activeTab', 'system')"
                class="px-6 py-2.5 rounded-full text-sm font-bold transition-all {{ $activeTab === 'system' ? 'bg-white text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
            System
        </button>
    </div>

    <!-- Services Tab -->
    @if($activeTab === 'services')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-on-surface">Laundry Services</h3>
            <button wire:click="openAddService"
                    class="flex items-center gap-2 px-6 py-3 editorial-gradient text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Service
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($services as $service)
            <div class="bg-white rounded-xl p-6 shadow-sm {{ !$service->is_active ? 'opacity-50' : '' }} relative">
                @if(!$service->is_active)
                <div class="absolute top-4 right-4">
                    <span class="px-2 py-0.5 bg-surface-container-high text-on-surface-variant text-[10px] font-bold rounded-full">INACTIVE</span>
                </div>
                @endif
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h4 class="font-bold text-on-surface text-lg">{{ $service->name }}</h4>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase bg-primary-fixed text-on-primary-fixed">{{ str_replace('_', ' ', $service->type) }}</span>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black text-tertiary">₱{{ number_format($service->price_per_kilo, 2) }}</p>
                        <p class="text-[10px] text-on-surface-variant font-bold uppercase">Per Kilo</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="bg-surface-container-highest rounded-lg p-3 text-center">
                        <p class="text-xs text-on-surface-variant">Wash</p>
                        <p class="text-sm font-bold">₱{{ number_format($service->wash_price, 0) }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $service->wash_minutes }}min</p>
                    </div>
                    <div class="bg-surface-container-highest rounded-lg p-3 text-center">
                        <p class="text-xs text-on-surface-variant">Dry</p>
                        <p class="text-sm font-bold">₱{{ number_format($service->dry_price, 0) }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $service->dry_minutes }}min</p>
                    </div>
                    <div class="bg-surface-container-highest rounded-lg p-3 text-center">
                        <p class="text-xs text-on-surface-variant">Fold</p>
                        <p class="text-sm font-bold">₱{{ number_format($service->fold_price, 0) }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-on-surface-variant">
                    <span>Min: {{ $service->minimum_kilos }} kg</span>
                    <div class="flex items-center gap-2">
                        <button wire:click="openEditService({{ $service->id }})" class="text-primary-container hover:text-primary p-1 transition-colors">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </button>
                        <button wire:click="toggleService({{ $service->id }})" class="text-on-surface-variant hover:text-on-surface p-1 transition-colors">
                            <span class="material-symbols-outlined text-lg">{{ $service->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                        </button>
                        <button wire:click="deleteService({{ $service->id }})" wire:confirm="Delete {{ $service->name }}?" class="text-error hover:text-error/80 p-1 transition-colors">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Account Tab -->
    @if($activeTab === 'account')
    <div class="bg-white rounded-xl p-8 shadow-sm max-w-lg space-y-6">
        <h3 class="text-xl font-bold text-on-surface">Account Information</h3>
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-primary-container flex items-center justify-center text-white text-xl font-bold">
                {{ $user->initials() }}
            </div>
            <div>
                <p class="text-lg font-bold text-on-surface">{{ $user->name }}</p>
                <p class="text-sm text-on-surface-variant">{{ $user->email }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-surface-container-highest rounded-lg p-4">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Role</p>
                <p class="text-sm font-bold text-on-surface mt-1 capitalize">{{ $user->role }}</p>
            </div>
            <div class="bg-surface-container-highest rounded-lg p-4">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Status</p>
                <p class="text-sm font-bold text-secondary mt-1 capitalize">{{ $user->status }}</p>
            </div>
            <div class="bg-surface-container-highest rounded-lg p-4">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Phone</p>
                <p class="text-sm font-bold text-on-surface mt-1">{{ $user->phone ?? '—' }}</p>
            </div>
            <div class="bg-surface-container-highest rounded-lg p-4">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Joined</p>
                <p class="text-sm font-bold text-on-surface mt-1">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- System Tab -->
    @if($activeTab === 'system')
    <div class="bg-white rounded-xl p-8 shadow-sm max-w-lg space-y-6">
        <h3 class="text-xl font-bold text-on-surface">System Information</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center bg-surface-container-highest rounded-lg p-4">
                <span class="text-sm text-on-surface-variant">Application</span>
                <span class="text-sm font-bold text-on-surface">J&M Laundry Lounge</span>
            </div>
            <div class="flex justify-between items-center bg-surface-container-highest rounded-lg p-4">
                <span class="text-sm text-on-surface-variant">Version</span>
                <span class="text-sm font-bold text-on-surface">1.0.0</span>
            </div>
            <div class="flex justify-between items-center bg-surface-container-highest rounded-lg p-4">
                <span class="text-sm text-on-surface-variant">Framework</span>
                <span class="text-sm font-bold text-on-surface">Laravel + Livewire</span>
            </div>
            <div class="flex justify-between items-center bg-surface-container-highest rounded-lg p-4">
                <span class="text-sm text-on-surface-variant">Database</span>
                <span class="text-sm font-bold text-on-surface">SQLite</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Service Modal -->
    @if($showServiceModal)
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
         x-data x-on:click.self="$wire.set('showServiceModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-lg shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform scale-95 opacity-0"
             x-transition:enter-end="transform scale-100 opacity-100">
            <h3 class="text-lg font-bold text-on-surface mb-4">{{ $editingService ? 'Edit Service' : 'Add New Service' }}</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Name</label>
                        <input wire:model="serviceName" type="text" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed" placeholder="Regular Wash"/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Type</label>
                        <select wire:model="serviceType" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed">
                            <option value="regular">Regular</option>
                            <option value="self_service">Self-Service</option>
                            <option value="rush">Rush</option>
                            <option value="comforter">Comforter</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Price/Kilo (₱)</label>
                        <input wire:model="pricePerKilo" type="number" step="0.01" min="0" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Min Kilos</label>
                        <input wire:model="minimumKilos" type="number" step="0.5" min="1" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Wash ₱</label>
                        <input wire:model="washPrice" type="number" step="0.01" min="0" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Wash Min</label>
                        <input wire:model="washMinutes" type="number" min="0" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Fold ₱</label>
                        <input wire:model="foldPrice" type="number" step="0.01" min="0" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Dry ₱</label>
                        <input wire:model="dryPrice" type="number" step="0.01" min="0" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Dry Min</label>
                        <input wire:model="dryMinutes" type="number" min="0" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="$set('showServiceModal', false)" class="flex-1 py-3 border border-outline-variant/30 rounded-full text-sm font-bold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button wire:click="saveService" class="flex-1 py-3 editorial-gradient text-white rounded-full text-sm font-bold shadow-lg hover:opacity-90 transition-all">
                    <span wire:loading.remove wire:target="saveService">{{ $editingService ? 'Update' : 'Create' }}</span>
                    <span wire:loading wire:target="saveService">Saving...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
