<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserManagement extends Component
{
    public string $search = '';
    public string $filterStatus = '';
    public string $filterRole = '';

    public function approveUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->update(['status' => 'active']);
            $this->dispatch('toast', message: "{$user->name} has been approved!", type: 'success');
        }
    }

    public function suspendUser($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->update(['status' => 'suspended']);
            $this->dispatch('toast', message: "{$user->name} has been suspended.", type: 'warning');
        }
    }

    public function activateUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->update(['status' => 'active']);
            $this->dispatch('toast', message: "{$user->name} has been activated.", type: 'success');
        }
    }

    public function changeRole($userId, $role)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->update(['role' => $role]);
            $this->dispatch('toast', message: "{$user->name} is now {$role}.", type: 'info');
        }
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
            $this->dispatch('toast', message: 'User deleted.', type: 'success');
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->latest()
            ->get();

        $pendingCount = User::where('status', 'pending')->count();

        return view('livewire.user-management', compact('users', 'pendingCount'));
    }
}
