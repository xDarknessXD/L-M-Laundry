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
<body class="bg-surface text-on-surface font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="ml-72 flex-1 min-h-screen flex flex-col">
            <!-- Top Navigation Bar -->
            @include('components.topbar')

            <!-- Page Content -->
            <div class="flex-1" x-data x-init="$el.classList.add('opacity-0'); setTimeout(() => { $el.classList.remove('opacity-0'); $el.classList.add('transition-opacity', 'duration-500', 'opacity-100'); }, 50)">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Toast Notifications -->
    @include('components.toast')

    @livewireScripts
</body>
</html>
