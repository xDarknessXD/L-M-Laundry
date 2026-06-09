<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $title ?? 'J&M Laundry Lounge' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-on-surface font-sans antialiased min-h-screen flex items-center justify-center p-6 bg-mesh">
    <!-- Decorative Background -->
    <div class="fixed inset-0 overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-secondary/5 blur-[120px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-tertiary/5 blur-[120px]"></div>
    </div>

    <div x-data="{ now: new Date(), init() { setInterval(() => { this.now = new Date(); }, 1000) } }"
         class="fixed top-4 right-4 z-50 flex items-center gap-2 px-3 py-1.5 bg-white/70 backdrop-blur-md rounded-full shadow-sm text-xs text-on-surface-variant">
        <span class="material-symbols-outlined text-[14px]">schedule</span>
        <span x-text="now.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })" class="font-medium"></span>
        <span class="w-px h-3 bg-outline-variant/30"></span>
        <span x-text="now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' })" class="tabular-nums font-semibold text-primary"></span>
    </div>

    <div x-data x-init="$el.classList.add('opacity-0'); setTimeout(() => { $el.classList.remove('opacity-0'); $el.classList.add('transition-opacity', 'duration-500', 'opacity-100'); }, 50)">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
