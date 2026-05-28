<div class="p-8 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-primary">User Management</h2>
            <p class="text-on-surface-variant font-medium mt-1">Manage staff accounts and access control</p>
        </div>
        <div class="flex items-center gap-3">
            @if($pendingCount > 0)
            <div class="flex items-center gap-2 px-4 py-2 bg-tertiary-fixed text-on-tertiary-fixed rounded-full animate-pulse">
                <span class="material-symbols-outlined text-lg">person_add</span>
                <span class="text-sm font-bold">{{ $pendingCount }} pending</span>
            </div>
            @endif
            <button wire:click="$toggle('showCreateForm')"
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold text-sm rounded-full hover:opacity-90 active:scale-[0.97] transition-all shadow-md shadow-primary/20">
                <span class="material-symbols-outlined text-lg">add</span>
                Create Staff
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 bg-white p-4 rounded-xl shadow-sm">
        <div class="flex-1 min-w-[200px] relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline-variant">search</span>
            <input wire:model.live.debounce.300ms="search" type="text"
                   class="w-full pl-12 pr-4 py-3 bg-surface-container-highest border-none rounded-lg text-sm focus:ring-2 focus:ring-primary-fixed"
                   placeholder="Search name or email..."/>
        </div>
        <select wire:model.live="filterRole" class="bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed min-w-[130px]">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
        </select>
        <select wire:model.live="filterStatus" class="bg-surface-container-highest border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary-fixed min-w-[150px]">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="suspended">Suspended</option>
        </select>
    </div>

    <!-- Create User Form -->
    @if($showCreateForm)
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-on-surface">Create Staff Account</h3>
                <p class="text-sm text-on-surface-variant">New accounts start as pending until approved</p>
            </div>
            <button type="button" wire:click="$set('showCreateForm', false)" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form wire:submit="createUser" onsubmit="return false" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Full Name</label>
                <input wire:model="newName" type="text"
                       class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                       placeholder="Juan Dela Cruz"/>
                @error('newName') <p class="text-error text-xs font-medium">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Email Address</label>
                <input wire:model="newEmail" type="email"
                       class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                       placeholder="staff@jmlaundry.com"/>
                @error('newEmail') <p class="text-error text-xs font-medium">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Phone Number</label>
                <input wire:model="newPhone" type="tel"
                       class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                       placeholder="+63 900 000 0000"/>
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Role</label>
                <select wire:model="newRole"
                        class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface">
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
                @error('newRole') <p class="text-error text-xs font-medium">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Password</label>
                <input wire:model="newPassword" type="password"
                       class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                       placeholder="••••••••"/>
                @error('newPassword') <p class="text-error text-xs font-medium">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Confirm Password</label>
                <input wire:model="newPasswordConfirmation" type="password"
                       class="w-full bg-surface-container-highest border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-primary-fixed transition-all text-on-surface placeholder:text-outline/50"
                       placeholder="••••••••"/>
                @error('newPasswordConfirmation') <p class="text-error text-xs font-medium">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2 flex justify-end gap-3 pt-2">
                <button type="button" wire:click="$set('showCreateForm', false)"
                        class="px-6 py-2.5 border border-outline-variant/30 text-sm font-bold rounded-full hover:bg-surface-container-low transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:opacity-90 active:scale-[0.97] transition-all flex items-center gap-2 shadow-md shadow-primary/20">
                    <span wire:loading.remove wire:target="createUser" class="material-symbols-outlined text-lg">person_add</span>
                    <span wire:loading.remove wire:target="createUser">Create Account</span>
                    <span wire:loading wire:target="createUser" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Creating...
                    </span>
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($users as $user)
        <div wire:key="user-{{ $user->id }}" class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow relative
            {{ $user->status === 'pending' ? 'ring-2 ring-tertiary-container' : '' }}">
            <!-- Status indicator -->
            <div class="absolute top-4 right-4">
                @if($user->status === 'active')
                    <span class="w-2.5 h-2.5 rounded-full bg-secondary inline-block ring-2 ring-secondary/20"></span>
                @elseif($user->status === 'pending')
                    <span class="w-2.5 h-2.5 rounded-full bg-tertiary-container inline-block animate-pulse ring-2 ring-tertiary-container/20"></span>
                @else
                    <span class="w-2.5 h-2.5 rounded-full bg-error inline-block ring-2 ring-error/20"></span>
                @endif
            </div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ $user->initials() }}
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-on-surface truncate">{{ $user->name }}</h4>
                    <p class="text-xs text-on-surface-variant truncate">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase
                            {{ $user->role === 'admin' ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-surface-container-high text-on-surface-variant' }}">
                            {{ $user->role }}
                        </span>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase
                            {{ $user->status === 'active' ? 'bg-secondary-container text-on-secondary-fixed-variant' : ($user->status === 'pending' ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-error-container text-on-error-container') }}">
                            {{ $user->status }}
                        </span>
                    </div>
                </div>
            </div>

            @if($user->id !== auth()->id())
            <div class="mt-4 pt-4 border-t border-surface-container-high flex flex-wrap gap-2">
                @if($user->status === 'pending')
                    <button wire:click="approveUser({{ $user->id }})"
                            class="flex-1 py-2 bg-secondary text-white text-xs font-bold rounded-full hover:opacity-90 transition-all flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">check</span> Approve
                    </button>
                @elseif($user->status === 'active')
                    <button x-on:click.prevent="$dispatch('confirm-action', { message: 'Suspend {!! addslashes($user->name) !!}?', method: 'suspendUser', params: [{{ $user->id }}] })"
                            class="flex-1 py-2 bg-error-container text-on-error-container text-xs font-bold rounded-full hover:opacity-90 transition-all flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">block</span> Suspend
                    </button>
                @else
                    <button wire:click="activateUser({{ $user->id }})"
                            class="flex-1 py-2 bg-secondary-container text-on-secondary-fixed-variant text-xs font-bold rounded-full hover:opacity-90 transition-all flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-sm">check_circle</span> Activate
                    </button>
                @endif

                <button wire:click="changeRole({{ $user->id }}, '{{ $user->role === 'admin' ? 'staff' : 'admin' }}')"
                        class="py-2 px-3 border border-outline-variant/30 text-xs font-bold rounded-full hover:bg-surface-container-low transition-colors"
                        title="Toggle role">
                    <span class="material-symbols-outlined text-sm">swap_horiz</span>
                </button>

                <button x-on:click.prevent="$dispatch('confirm-action', { message: 'Delete {!! addslashes($user->name) !!}? This cannot be undone.', method: 'deleteUser', params: [{{ $user->id }}] })"
                        class="py-2 px-3 text-error border border-error/20 text-xs font-bold rounded-full hover:bg-error-container/30 transition-colors">
                    <span class="material-symbols-outlined text-sm">delete</span>
                </button>
            </div>
            @else
            <div class="mt-4 pt-4 border-t border-surface-container-high">
                <p class="text-xs text-on-surface-variant text-center italic">This is you</p>
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full text-center py-16 text-on-surface-variant">
            <span class="material-symbols-outlined text-5xl mb-3 opacity-20">group</span>
            <p class="font-medium">No users found</p>
        </div>
        @endforelse
    </div>

    <x-confirm-modal />
</div>
