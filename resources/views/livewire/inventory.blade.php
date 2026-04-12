<div class="p-8 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-primary">Inventory</h2>
            <p class="text-on-surface-variant font-medium mt-1">Manage supplies, materials, and stock levels</p>
        </div>
        @if($isAdmin)
        <div class="flex gap-3">
            <button wire:click="$set('showCategoryModal', true)" class="flex items-center gap-2 px-6 py-3 bg-white text-primary font-bold rounded-full shadow-sm hover:bg-surface-container-high transition-all active:scale-95">
                <span class="material-symbols-outlined">create_new_folder</span> New Category
            </button>
            <button wire:click="openAddItem()" class="flex items-center gap-2 px-8 py-3 editorial-gradient text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Item
            </button>
        </div>
        @endif
    </div>

    <!-- Search -->
    <div class="relative max-w-md">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">search</span>
        <input wire:model.live.debounce.300ms="search" type="text"
               class="w-full pl-12 pr-4 py-3 bg-white border-none rounded-xl shadow-sm text-sm focus:ring-2 focus:ring-primary-fixed"
               placeholder="Search items..."/>
    </div>

    <!-- Categories Accordion -->
    <div class="space-y-6">
        @forelse($categories as $category)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="{ open: true }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-5 hover:bg-surface-container-highest/30 transition-colors">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $category->icon }}</span>
                    <div class="text-left">
                        <h3 class="font-bold text-on-surface">{{ $category->name }}</h3>
                        <p class="text-xs text-on-surface-variant">{{ $category->items->count() }} item(s)</p>
                    </div>
                </div>
                <span class="material-symbols-outlined transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @if($category->items->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="border-t border-surface-container-high">
                            <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Item</th>
                            <th class="text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Brand</th>
                            <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Unit</th>
                            <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Price</th>
                            <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Stock</th>
                            <th class="text-center px-6 py-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Status</th>
                            @if($isAdmin)<th class="px-6 py-3"></th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->items as $item)
                        <tr class="border-t border-surface-container-highest/50 hover:bg-surface-container-highest/20 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-on-surface">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant">{{ $item->brand ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-center">{{ $item->unit }}</td>
                            <td class="px-6 py-4 text-sm text-center font-bold">₱{{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-center font-bold {{ $item->stock_quantity <= 10 ? 'text-error' : 'text-on-surface' }}">{{ $item->stock_quantity }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->stock_quantity <= 0)
                                    <span class="px-3 py-1 bg-error-container text-on-error-container text-[10px] font-bold rounded-full">OUT</span>
                                @elseif($item->stock_quantity <= 10)
                                    <span class="px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold rounded-full animate-pulse">LOW</span>
                                @else
                                    <span class="px-3 py-1 bg-secondary-container text-on-secondary-fixed-variant text-[10px] font-bold rounded-full">OK</span>
                                @endif
                            </td>
                            @if($isAdmin)
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button wire:click="openEditItem({{ $item->id }})" class="text-primary-container hover:text-primary transition-colors p-1">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </button>
                                    <button wire:click="deleteItem({{ $item->id }})" wire:confirm="Delete {{ $item->name }}?" class="text-error hover:text-error/80 transition-colors p-1">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-8 text-on-surface-variant border-t border-surface-container-high">
                    <p class="text-sm">No items in this category</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-on-surface-variant">
            <span class="material-symbols-outlined text-5xl mb-3 opacity-20">inventory_2</span>
            <p class="font-medium">No inventory categories yet</p>
        </div>
        @endforelse
    </div>

    <!-- Add/Edit Item Modal -->
    @if($showItemModal)
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
         x-data x-on:click.self="$wire.set('showItemModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform scale-95 opacity-0"
             x-transition:enter-end="transform scale-100 opacity-100">
            <h3 class="text-lg font-bold text-on-surface mb-4">{{ $editingItem ? 'Edit Item' : 'Add New Item' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Category</label>
                    <select wire:model="categoryId" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Item Name</label>
                    <input wire:model="itemName" type="text" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed" placeholder="Ariel Powder"/>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Brand</label>
                        <input wire:model="itemBrand" type="text" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed" placeholder="Ariel"/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Unit</label>
                        <select wire:model="itemUnit" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed">
                            <option value="pcs">pcs</option>
                            <option value="kg">kg</option>
                            <option value="liters">liters</option>
                            <option value="sachets">sachets</option>
                            <option value="bottles">bottles</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Price (₱)</label>
                        <input wire:model="itemPrice" type="number" step="0.01" min="0" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Stock Qty</label>
                        <input wire:model="itemStock" type="number" min="0" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="$set('showItemModal', false)" class="flex-1 py-3 border border-outline-variant/30 rounded-full text-sm font-bold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button wire:click="saveItem" class="flex-1 py-3 editorial-gradient text-white rounded-full text-sm font-bold shadow-lg hover:opacity-90 transition-all">
                    <span wire:loading.remove wire:target="saveItem">{{ $editingItem ? 'Update' : 'Add Item' }}</span>
                    <span wire:loading wire:target="saveItem">Saving...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Category Modal -->
    @if($showCategoryModal)
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
         x-data x-on:click.self="$wire.set('showCategoryModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-2xl">
            <h3 class="text-lg font-bold text-on-surface mb-4">New Category</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Name</label>
                    <input wire:model="newCategoryName" type="text" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed" placeholder="Detergents"/>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Icon (Emoji)</label>
                    <input wire:model="newCategoryIcon" type="text" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed" placeholder="📦"/>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="$set('showCategoryModal', false)" class="flex-1 py-3 border border-outline-variant/30 rounded-full text-sm font-bold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button wire:click="saveCategory" class="flex-1 py-3 editorial-gradient text-white rounded-full text-sm font-bold shadow-lg hover:opacity-90 transition-all">Create</button>
            </div>
        </div>
    </div>
    @endif
</div>
