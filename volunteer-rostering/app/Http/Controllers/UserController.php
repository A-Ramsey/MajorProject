<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\WebRole;
use App\Enums\WebRoleEnum;

class UserController extends Controller
{
    public function view($userId) {
        $user = User::find($userId);
        return view('users.view', [
            'user' => $user,
        ]);
    }

    public function approve($userId) {
        $user = User::find($userId);
        $user->approved = true;
        $user->save();
        return redirect('users')->with('messages', $user->name . ' approved');
    }

    public function delete($userId) {
        $user = User::find($userId);
        $user->delete();
        return redirect('users')->with('messages', 'User deleted');
    }

    public function list() {
        $users = User::where('approved', true)->get();
        $unapprovedUsers = User::where('approved', false)->get();

        return view('users.list', [
            'users' => $users,
            'unapprovedUsers' => $unapprovedUsers,
        ]);
    }

    public function edit($userId) {
        $user = User::find($userId);
        $webRoles = WebRoleEnum::getInstances();

        $userRoles = array_map(fn ($role) => WebRoleEnum::coerce($role->role), $user->webRoles()->get()->all());
        $roles = [];
        foreach ($webRoles as $role) {
            $roles[$role->key] = ['checked' => false, 'role' => $role];
            if ($role->in($userRoles)) {
                $roles[$role->key]['checked'] = true;
            }
        }
        return view('users.edit', [
            'webRoles' => $roles,
            'user' => $user,
        ]);
    }

    public function update($userId) {
        $formData = request()->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user = User::find($userId);
        $user->update($formData);

        $roles = [];
        foreach(request()->request as $key => $field) {
            if (str_starts_with($key, 'web_role_')) {
                // dont need the commented out section due to the coerce method on a laravel enums
                $roles[$field] = WebRoleEnum::coerce($field);//substr($key, strpos($key, '_', strpos($key, '_') + 1) + 1));
            }
        }
        //deleting removed roles
        foreach ($user->webRoles()->get() as $webRole) {
            if (!in_array($webRole->role, $roles)) {
                $webRole->delete();
            }
        }
        //adding new roles
        foreach ($roles as $role) {
            if (!$user->hasWebRole($role->value)) {
                $user->webRoles()->create(['role' => $role->value]);
            }
        }
        
        return redirect('user/' . $user->id)->with('messages', 'User updated');
    }

    public function resetOwnPasswordForm() {
        return $this->resetPasswordForm(auth()->user()->id);
    }

    public function resetOwnPassword() {
        return $this->resetPassword(auth()->user()->id, true);
    }

    public function resetPasswordForm($userId) {
        $user = User::find($userId);

        return view('users.resetPassword', [
            'user' => $user,
        ]);
    }

    public function resetPassword($userId, $own = false) {
        $user = User::find($userId);
        $formData = request()->validate([
            'password' => 'required|min:7|max:255',
            'confirm_password' => 'required|min:7|max:255',
        ]);
        if ($formData['password'] != $formData['confirm_password']) {
            throw ValidationException::withMessages(['confirm_password' => 'Passwords must match']);
        } else {
            unset($formData['confirm_password']);
        }

        $user->update($formData);

        $redirect = 'user/' . $user->id;
        if ($own) {
            $redirect = '/';
        }
        return redirect($redirect)->with('messages', 'Password Changed');
    }

    public function changeDetails() {
        $user = auth()->user();
        
        return view('users.changeDetails', [
            'user' => $user,
        ]);
    }

    public function updateDetails() {
        $user = auth()->user();
        $formData = request()->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);
        
        $user->update($formData);
        return redirect('/')->with('messages', 'Details Updated');
    }
}
