@php
    $currentRoute = request()->route()?->getName() ?? '';
    $user = auth()->user();
    $isAdmin = $user?->isAdmin() ?? false;

    $navItems = [
        ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'dashboard'],
        ['name' => 'New Transaction', 'route' => 'transactions.create', 'icon' => 'add_circle'],
        ['name' => 'Transactions', 'route' => 'transactions', 'icon' => 'receipt_long'],
        ['name' => 'Inventory', 'route' => 'inventory', 'icon' => 'inventory_2'],
        ['name' => 'Machine Logs', 'route' => 'machine-logs', 'icon' => 'local_laundry_service'],
        ['name' => 'Daily Report', 'route' => 'daily-report', 'icon' => 'analytics', 'admin' => true],
        ['name' => 'User Management', 'route' => 'users', 'icon' => 'group', 'admin' => true],
        ['name' => 'Settings', 'route' => 'settings', 'icon' => 'settings', 'admin' => true],
    ];
@endphp

<aside x-persist="sidebar" class="fixed left-0 top-0 h-full py-8 px-4 flex flex-col gap-2 w-72 border-none bg-surface-container-low z-50 font-sans tracking-tight antialiased">
    <!-- Brand -->
    <div class="mb-10 px-4">
        <img src="{{ asset('images/logo.png') }}" alt="J&M Laundry Logo" class="h-10 w-auto mb-3 object-contain">
        <h1 class="text-xl font-black text-primary-container tracking-[-0.02em]">J&M Laundry</h1>
        <p class="text-xs uppercase tracking-widest text-on-surface-variant/60 font-bold">Management Suite</p>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 flex flex-col gap-1">
        @foreach($navItems as $item)
            @if(isset($item['admin']) && $item['admin'] && !$isAdmin)
                {{-- Hide admin-only items for staff --}}
                @continue
            @endif
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-4 py-3 transition-all duration-200 rounded-full group
                      {{ $currentRoute === $item['route']
                          ? 'text-primary-container font-bold bg-white shadow-sm'
                          : 'text-on-surface-variant hover:text-primary-container hover:bg-surface-container-highest' }}">
                <span class="material-symbols-outlined transition-transform group-hover:scale-110"
                      @if($currentRoute === $item['route']) style="font-variation-settings: 'FILL' 1;" @endif>{{ $item['icon'] }}</span>
                <span class="font-semibold text-sm">{{ $item['name'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- Bottom Actions -->
    <div class="mt-auto flex flex-col gap-1 border-t border-outline-variant/10 pt-4" x-data="{ confirmLogout: false }">
        <button type="button" @click="confirmLogout = true" class="w-full flex items-center gap-3 px-4 py-3 text-error hover:bg-error-container/30 transition-all duration-200 rounded-full">
            <span class="material-symbols-outlined">logout</span>
            <span class="font-semibold text-sm">Logout</span>
        </button>

        <!-- Logout Confirmation Modal -->
        <div x-show="confirmLogout" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-sm"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-sm"
             x-transition:leave-end="opacity-0 backdrop-blur-none">

            <div @click.away="confirmLogout = false" class="bg-white rounded-2xl p-6 shadow-2xl max-w-sm w-full"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-error-container flex items-center justify-center text-error flex-shrink-0">
                        <span class="material-symbols-outlined text-2xl">logout</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-on-surface">Confirm Logout</h3>
                        <p class="text-sm text-on-surface-variant leading-tight">Are you sure you want to end your session?</p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" @click="confirmLogout = false" class="flex-1 py-3 text-sm font-bold text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors border border-outline-variant/30">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-3 text-sm font-bold text-white bg-error hover:opacity-90 rounded-full transition-opacity shadow-lg shadow-error/20">
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
