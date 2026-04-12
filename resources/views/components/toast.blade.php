<div x-data="{ toasts: [], add(msg, type = 'success') { const id = Date.now(); this.toasts.push({id, msg, type}); setTimeout(() => this.remove(id), 3000); }, remove(id) { this.toasts = this.toasts.filter(t => t.id !== id); } }"
     x-on:toast.window="add($event.detail.message, $event.detail.type || 'success')"
     class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform translate-x-full opacity-0"
             x-transition:enter-end="transform translate-x-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="transform translate-x-0 opacity-100"
             x-transition:leave-end="transform translate-x-full opacity-0"
             class="flex items-center gap-3 px-5 py-4 rounded-xl shadow-xl min-w-[320px] backdrop-blur-xl"
             :class="{
                 'bg-secondary-container text-on-secondary-container': toast.type === 'success',
                 'bg-error-container text-on-error-container': toast.type === 'error',
                 'bg-tertiary-fixed text-on-tertiary-fixed': toast.type === 'warning',
                 'bg-primary-fixed text-on-primary-fixed': toast.type === 'info'
             }">
            <span class="material-symbols-outlined text-xl" x-text="toast.type === 'success' ? 'check_circle' : toast.type === 'error' ? 'error' : toast.type === 'warning' ? 'warning' : 'info'"></span>
            <span class="text-sm font-semibold" x-text="toast.msg"></span>
            <button @click="remove(toast.id)" class="ml-auto p-1 hover:opacity-70">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
    </template>
</div>
