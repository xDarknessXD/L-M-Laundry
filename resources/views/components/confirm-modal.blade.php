<div x-data="{ show: false, message: '', method: '', params: [] }"
     x-on:confirm-action.window="show = true; message = $event.detail.message; method = $event.detail.method; params = $event.detail.params || []"
     x-show="show"
     class="fixed inset-0 z-[70] flex items-center justify-center p-4"
     style="display: none;">

    <div x-on:click="show = false" class="fixed inset-0 bg-black/30 backdrop-blur-sm"></div>

    <div class="relative bg-white rounded-xl p-6 w-full max-w-sm shadow-2xl"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform scale-95 opacity-0"
         x-transition:enter-end="transform scale-100 opacity-100">

        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-error-container flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-error">warning</span>
            </div>
            <div>
                <h3 class="text-lg font-bold text-on-surface">Confirm Action</h3>
                <p class="text-sm text-on-surface-variant mt-1" x-text="message"></p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button x-on:click="show = false"
                    class="px-5 py-2.5 border border-outline-variant/30 text-sm font-bold rounded-full hover:bg-surface-container-low transition-colors">
                Cancel
            </button>
            <button x-on:click="show = false; $wire.call(method, ...params)"
                    class="px-5 py-2.5 bg-error text-white text-sm font-bold rounded-full hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">check</span>
                Confirm
            </button>
        </div>
    </div>
</div>
