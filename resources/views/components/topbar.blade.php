@php $user = auth()->user(); @endphp
<header class="w-full h-16 sticky top-0 z-40 bg-white/80 backdrop-blur-xl flex justify-between items-center px-8 shadow-[0_40px_40px_rgba(0,10,30,0.04)]">
    <div class="flex items-center gap-8">
        <span class="text-lg font-bold text-primary-container">J&M Laundry Lounge</span>
    </div>
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2">
            <button class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-all">
                <span class="material-symbols-outlined">help</span>
            </button>
            <button class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-all relative">
                <span class="material-symbols-outlined">notifications</span>
                @if(\App\Models\User::where('status', 'pending')->count() > 0 && $user?->isAdmin())
                    <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full ring-2 ring-white"></span>
                @endif
            </button>
        </div>
        <div class="h-8 w-[1px] bg-outline-variant/30 mx-2"></div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-xs font-bold text-primary">{{ $user?->name ?? 'User' }}</p>
                <p class="text-[10px] text-on-surface-variant capitalize">{{ $user?->role ?? '' }}</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-white text-xs font-bold">
                {{ $user?->initials() ?? '?' }}
            </div>
        </div>
    </div>
</header>
