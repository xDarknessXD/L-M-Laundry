<div class="w-full max-w-[480px] flex flex-col items-center">
    <!-- Logo -->
    <div class="mb-10 text-center flex flex-col items-center">
        <img src="{{ asset('images/logo.png') }}" alt="J&M Laundry Logo" class="w-24 h-auto mb-4 object-contain">
        <h1 class="text-2xl font-black text-primary tracking-[-0.02em]">J&M Laundry</h1>
        <p class="text-on-surface-variant font-medium tracking-tight mt-1">Management Suite</p>
    </div>

    <!-- Auth Card -->
    <div class="w-full bg-white rounded-xl p-8 md:p-10 shadow-[0_40px_40px_rgba(0,10,30,0.04)]">
        <header class="mb-8">
            <h2 class="text-xl font-bold text-on-surface tracking-tight">Staff Sign-In</h2>
            <p class="text-on-surface-variant text-sm mt-1">Enter your credentials to access the portal.</p>
        </header>

        <form wire:submit="login" class="space-y-6">
            <!-- Email -->
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-widest text-secondary" for="email">Email Address</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant group-focus-within:text-primary transition-colors">mail</span>
                    <input wire:model="email" type="email" id="email"
                           class="w-full pl-12 pr-4 py-4 bg-surface-container-highest rounded-lg text-on-surface placeholder:text-outline border-none focus:ring-2 focus:ring-primary-fixed transition-all"
                           placeholder="staff@jmlaundry.com"/>
                </div>
                @error('email') <p class="text-error text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Password -->
            <div class="space-y-2" x-data="{ show: false }">
                <div class="flex justify-between items-center">
                    <label class="block text-xs font-bold uppercase tracking-widest text-secondary" for="password">Password</label>
                </div>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant group-focus-within:text-primary transition-colors">lock</span>
                    <input wire:model="password" :type="show ? 'text' : 'password'" id="password"
                           class="w-full pl-12 pr-12 py-4 bg-surface-container-highest rounded-lg text-on-surface placeholder:text-outline border-none focus:ring-2 focus:ring-primary-fixed transition-all"
                           placeholder="••••••••"/>
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
                @error('password') <p class="text-error text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full editorial-gradient text-white font-bold py-4 rounded-full flex items-center justify-center gap-2 hover:opacity-90 active:scale-[0.98] transition-all shadow-md mt-4 relative">
                <span wire:loading.remove wire:target="login">Log In</span>
                <span wire:loading.remove wire:target="login" class="material-symbols-outlined text-lg">arrow_forward</span>
                <span wire:loading wire:target="login" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Signing in...
                </span>
            </button>

            <div class="mt-6 text-center text-sm font-medium text-on-surface-variant">
                <span>Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-primary font-bold hover:underline ml-1 transition-all">Register</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="mt-12 text-center">
        <div class="flex items-center justify-center gap-2 text-on-surface-variant text-sm font-medium">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
            <span class="tracking-tight">J&M Laundry Lounge — Staff Portal</span>
        </div>
        <p class="text-[10px] text-outline mt-2 font-semibold uppercase tracking-widest">Authorized Access Only</p>
    </footer>
</div>
