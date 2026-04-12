<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', 'Invalid credentials.');
            return;
        }

        $user = Auth::user();

        if ($user->isPending()) {
            Auth::logout();
            return redirect()->route('waiting');
        }

        if ($user->isSuspended()) {
            Auth::logout();
            $this->addError('email', 'Your account has been suspended. Please contact the administrator.');
            return;
        }

        session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth', ['title' => 'Sign In | J&M Laundry Lounge']);
    }
}
