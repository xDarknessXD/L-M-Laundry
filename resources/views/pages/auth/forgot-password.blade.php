<x-layouts.auth :title="'Forgot Password'">
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
            <h2 class="text-xl font-bold text-on-surface tracking-tight">Forgot Password</h2>
            <p class="text-on-surface-variant text-sm mt-1">Enter your email and we'll send you a reset link.</p>
        </header>

        @if(session('status'))
            <div class="mb-6 p-4 bg-secondary-container/30 rounded-lg text-sm font-medium text-on-secondary-fixed-variant">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase tracking-widest text-secondary" for="email">Email Address</label>
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant group-focus-within:text-primary transition-colors">mail</span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-12 pr-4 py-4 bg-surface-container-highest rounded-lg text-on-surface placeholder:text-outline border-none focus:ring-2 focus:ring-primary-fixed transition-all"
                           placeholder="staff@jmlaundry.com"/>
                </div>
                @error('email') <p class="text-error text-xs font-medium mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full editorial-gradient text-white font-bold py-4 rounded-full flex items-center justify-center gap-2 hover:opacity-90 active:scale-[0.98] transition-all shadow-md">
                Send Reset Link
                <span class="material-symbols-outlined text-lg">send</span>
            </button>
        </form>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm font-medium text-primary hover:underline flex items-center justify-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back to Sign In
        </a>
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
</x-layouts.auth>
