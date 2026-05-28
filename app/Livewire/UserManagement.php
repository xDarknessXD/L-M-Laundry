<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    public string $search = '';
    public string $filterStatus = '';
    public string $filterRole = '';

    public string $newName = '';
    public string $newEmail = '';
    public string $newPhone = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';
    public string $newRole = 'staff';
    public bool $showCreateForm = false;

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

    public function createUser()
    {
        $this->validate([
            'newName' => 'required|min:2|max:255',
            'newEmail' => 'required|email|unique:users,email',
            'newPhone' => 'nullable|string|max:20',
            'newPassword' => 'required|min:8|same:newPasswordConfirmation',
            'newPasswordConfirmation' => 'required',
            'newRole' => 'required|in:admin,staff',
        ]);

        User::create([
            'name' => $this->newName,
            'email' => $this->newEmail,
            'phone' => $this->newPhone,
            'password' => Hash::make($this->newPassword),
            'role' => $this->newRole,
            'status' => 'pending',
        ]);

        $this->reset('newName', 'newEmail', 'newPhone', 'newPassword', 'newPasswordConfirmation', 'newRole');
        $this->showCreateForm = false;

        $this->dispatch('toast', message: 'User account created!', type: 'success');
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
