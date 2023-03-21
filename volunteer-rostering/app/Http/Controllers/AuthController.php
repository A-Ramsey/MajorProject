<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\WebRole;

class AuthController extends Controller
{
    // guide used: https://laracasts.com/series/laravel-8-from-scratch/episodes/45
    public function doRegister() {
        $userAttributes = request()->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|min:7|max:255|unique:users,email',
            'password' => 'required|min:7|max:255',
            'confirm_password' => 'required|min:7|max:255',
        ]);
        if ($userAttributes['confirm_password'] != $userAttributes['password']) {
            throw ValidationException::withMessages(['confirm_password' => 'Passwords must match']);
        }
        unset($userAttributes['confirm_password']);
        $user = User::create($userAttributes);
        $webRole = WebRole::create(['role' => 'Volunteer']);
        $user->webRoles()->save($webRole);
        $user->save();

        return redirect('login')->with('messages', 'Account creeated successfully, please wait for your account to be approved');
;
    }

    public function register() {
        return view('auth.register');
    }

    public function logout() {
        auth()->logout();
        return redirect('login')->with('messages', 'Logged out successfully');
    }

    public function login() {
        return view('auth.login');
    }

    public function doLogin() {
        $userAttributes = request()->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|max:255'
        ]);

        if (auth()->attempt($userAttributes)) {
            if (!auth()->user()->approved) {
                auth()->logout();
                return redirect('login')->with('messages', 'You are not yet approved, wait until your account has been approved before you login');
            }
            return redirect('/')->with('messages', 'Login successful');
        }

        throw ValidationException::withMessages(['email' => 'Email or password incorrect']);
    }
}
