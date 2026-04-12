<div class="p-8 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-primary">Machine Logs</h2>
            <p class="text-on-surface-variant font-medium mt-1">Track washer and dryer cycles</p>
        </div>
        <button wire:click="$set('showLogModal', true)"
                class="flex items-center gap-2 px-8 py-3 editorial-gradient text-white font-bold rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-all active:scale-95">
            <span class="material-symbols-outlined">play_circle</span> Start Cycle
        </button>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 bg-white p-4 rounded-xl shadow-sm">
        <div class="flex-1 min-w-[200px] relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">search</span>
            <input wire:model.live.debounce.300ms="search" type="text"
                   class="w-full pl-12 pr-4 py-3 bg-surface-container-highest border-none rounded-lg text-sm focus:ring-2 focus:ring-primary-fixed"
                   placeholder="Search machine..."/>
        </div>
        <select wire:model.live="filterMachine" class="bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed min-w-[160px]">
            <option value="">All Machines</option>
            @foreach($machines as $machine)
                <option value="{{ $machine->id }}">{{ $machine->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterCycleType" class="bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed min-w-[130px]">
            <option value="">All Types</option>
            <option value="wash">Wash</option>
            <option value="dry">Dry</option>
        </select>
        <select wire:model.live="filterStatus" class="bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed min-w-[150px]">
            <option value="">All Status</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-surface-container-high">
                    <th class="text-left px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Machine</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Type</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Load</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Duration</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Staff</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Status</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Started</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-b border-surface-container-highest/50 hover:bg-surface-container-highest/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full {{ $log->cycle_type === 'wash' ? 'bg-primary-fixed' : 'bg-tertiary-fixed' }} flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg {{ $log->cycle_type === 'wash' ? 'text-on-primary-fixed' : 'text-on-tertiary-fixed' }}">
                                    {{ $log->cycle_type === 'wash' ? 'local_laundry_service' : 'dry_cleaning' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">{{ $log->machine?->name ?? 'N/A' }}</p>
                                <p class="text-[10px] text-on-surface-variant font-mono">{{ $log->machine?->machine_code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase
                            {{ $log->cycle_type === 'wash' ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-tertiary-fixed text-on-tertiary-fixed' }}">
                            {{ $log->cycle_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium">{{ number_format($log->load_kilos, 1) }} kg</td>
                    <td class="px-6 py-4 text-center text-sm font-medium">{{ $log->duration_minutes }} min</td>
                    <td class="px-6 py-4 text-sm text-on-surface">{{ $log->staff?->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($log->status === 'in_progress')
                            <span class="px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold rounded-full animate-pulse">RUNNING</span>
                        @else
                            <span class="px-3 py-1 bg-secondary-container text-on-secondary-fixed-variant text-[10px] font-bold rounded-full">DONE</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-on-surface-variant">{{ $log->start_time?->format('M d, h:i A') ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($log->status === 'in_progress')
                        <button wire:click="completeLog({{ $log->id }})" wire:confirm="Mark this cycle as completed?"
                                class="text-secondary hover:text-secondary/80 transition-colors p-1">
                            <span class="material-symbols-outlined text-xl">check_circle</span>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-16 text-on-surface-variant">
                        <span class="material-symbols-outlined text-5xl mb-3 opacity-20">local_laundry_service</span>
                        <p class="text-sm font-medium">No machine logs found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-surface-container-high">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Start Cycle Modal -->
    @if($showLogModal)
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
         x-data x-on:click.self="$wire.set('showLogModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform scale-95 opacity-0"
             x-transition:enter-end="transform scale-100 opacity-100">
            <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">play_circle</span> Start Machine Cycle
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Machine</label>
                    <select wire:model="machineId" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed">
                        <option value="">Select machine...</option>
                        @foreach($machines as $machine)
                            <option value="{{ $machine->id }}">{{ $machine->name }} ({{ $machine->machine_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-on-surface-variant mb-1 block">Cycle Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="cycleType" value="wash" class="hidden peer"/>
                            <div class="peer-checked:ring-2 peer-checked:ring-primary-container peer-checked:bg-primary-fixed/10 bg-surface-container-highest rounded-lg p-3 text-center hover:bg-surface-container-high transition-all">
                                <span class="material-symbols-outlined text-2xl mb-1">local_laundry_service</span>
                                <p class="text-xs font-bold">Wash</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="cycleType" value="dry" class="hidden peer"/>
                            <div class="peer-checked:ring-2 peer-checked:ring-tertiary-container peer-checked:bg-tertiary-fixed/10 bg-surface-container-highest rounded-lg p-3 text-center hover:bg-surface-container-high transition-all">
                                <span class="material-symbols-outlined text-2xl mb-1">dry_cleaning</span>
                                <p class="text-xs font-bold">Dry</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Load (kg)</label>
                        <input wire:model="loadKilos" type="number" step="0.1" min="0.1" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-1 block">Duration (min)</label>
                        <input wire:model="durationMinutes" type="number" min="1" class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed"/>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="$set('showLogModal', false)" class="flex-1 py-3 border border-outline-variant/30 rounded-full text-sm font-bold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button wire:click="startLog" class="flex-1 py-3 editorial-gradient text-white rounded-full text-sm font-bold shadow-lg hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="startLog">Start Cycle</span>
                    <span wire:loading wire:target="startLog">Starting...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
