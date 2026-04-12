<div class="w-full max-w-[480px] bg-white rounded-xl p-10 flex flex-col items-center gap-8 shadow-[0_40px_40px_rgba(0,10,30,0.04)] relative">
    <!-- Logo -->
    <div class="flex flex-col items-center gap-3">
        <div class="w-16 h-16 rounded-full bg-primary-container flex items-center justify-center text-white">
            <span class="material-symbols-outlined text-3xl">local_laundry_service</span>
        </div>
        <div class="text-center">
            <h1 class="text-2xl font-black text-primary tracking-tight">J&M Laundry</h1>
            <p class="text-sm font-medium text-secondary uppercase tracking-widest mt-1">Management Suite</p>
        </div>
    </div>

    <div class="w-full">
        <div class="mb-8 text-center">
            <h2 class="text-xl font-bold text-on-surface">Create your account</h2>
            <p class="text-on-surface-variant text-sm mt-1">Join the lounge and manage your laundry flow</p>
        </div>

        <form wire:submit="register" class="flex flex-col gap-5">
            <!-- Name -->
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1">Full Name</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-xl">person</span>
                    <input wire:model="name" type="text"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-4 pl-12 pr-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                           placeholder="Juan Dela Cruz"/>
                </div>
                @error('name') <p class="text-error text-xs font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1">Email Address</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-xl">mail</span>
                    <input wire:model="email" type="email"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-4 pl-12 pr-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                           placeholder="juan@laundrylounge.com"/>
                </div>
                @error('email') <p class="text-error text-xs font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Phone -->
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1">Phone Number</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-xl">call</span>
                    <input wire:model="phone" type="tel"
                           class="w-full bg-surface-container-highest border-none rounded-lg py-4 pl-12 pr-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                           placeholder="+63 900 000 0000"/>
                </div>
            </div>

            <!-- Passwords -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-xl">lock</span>
                        <input wire:model="password" type="password"
                               class="w-full bg-surface-container-highest border-none rounded-lg py-4 pl-12 pr-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                               placeholder="••••••••"/>
                    </div>
                    @error('password') <p class="text-error text-xs font-medium">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1">Confirm</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-xl">verified_user</span>
                        <input wire:model="password_confirmation" type="password"
                               class="w-full bg-surface-container-highest border-none rounded-lg py-4 pl-12 pr-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                               placeholder="••••••••"/>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full mt-4 editorial-gradient text-white font-bold py-4 px-6 rounded-full shadow-lg shadow-primary/10 active:scale-[0.98] transition-transform flex items-center justify-center gap-2 relative">
                <span wire:loading.remove wire:target="register">Register</span>
                <span wire:loading.remove wire:target="register" class="material-symbols-outlined text-xl">arrow_forward</span>
                <span wire:loading wire:target="register" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Creating account...
                </span>
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-surface-container-high text-center">
            <p class="text-on-surface-variant text-sm">
                Already have an account?
                <a href="{{ route('login') }}" class="text-tertiary font-bold hover:underline underline-offset-4 ml-1 transition-all">Back to Login</a>
            </p>
        </div>
    </div>
</div>
