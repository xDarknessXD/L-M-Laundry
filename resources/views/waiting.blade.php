<x-layouts.auth title="Account Pending | J&M Laundry Lounge">
    <div class="max-w-xl w-full flex flex-col items-center text-center">
        <!-- Brand -->
        <div class="mb-12 flex flex-col items-center">
            <div class="w-20 h-20 bg-primary-container rounded-xl flex items-center justify-center mb-4 shadow-[0_20px_40px_rgba(0,10,30,0.1)]">
                <span class="material-symbols-outlined text-white text-4xl">local_laundry_service</span>
            </div>
            <h1 class="text-2xl font-black text-primary-container tracking-tight">J&M Laundry</h1>
            <p class="text-xs font-bold uppercase tracking-widest text-secondary mt-1">Management Suite</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white p-10 md:p-16 rounded-xl shadow-[0_40px_80px_rgba(0,10,30,0.04)] w-full relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-secondary-container opacity-20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-primary-fixed opacity-30 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center">
                <!-- Animated Illustration -->
                <div class="relative w-32 h-32 mb-10">
                    <div class="absolute inset-0 border-4 border-dashed border-primary-fixed rounded-full animate-[spin_10s_linear_infinite]"></div>
                    <div class="absolute inset-2 bg-surface-container-low rounded-full flex items-center justify-center">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-inner">
                            <span class="material-symbols-outlined text-primary text-5xl animate-pulse">pending_actions</span>
                        </div>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-secondary-container rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-on-secondary-container">bubble_chart</span>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-on-surface mb-6 tracking-tight">Waiting for Approval</h2>

                <div class="bg-surface-container-high rounded-lg p-6 mb-10 max-w-sm">
                    <p class="text-on-surface-variant leading-relaxed">
                        Your account is pending approval. Please wait for the admin to activate your account.
                    </p>
                </div>

                <div class="flex flex-col gap-4 w-full">
                    <div class="flex items-center justify-center gap-3 py-3 px-6 bg-surface-container-low rounded-full text-sm text-on-surface-variant font-medium">
                        <span class="material-symbols-outlined text-primary">info</span>
                        This usually takes less than 24 hours
                    </div>

                    <div class="h-px w-12 bg-outline-variant/30 mx-auto my-4"></div>

                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 text-primary font-bold hover:gap-4 transition-all duration-300">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 flex flex-col items-center gap-2">
            <p class="text-on-surface-variant text-sm">Need urgent access?</p>
            <p class="text-xs font-bold text-secondary uppercase tracking-widest">Contact Support</p>
        </footer>
    </div>
</x-layouts.auth>
