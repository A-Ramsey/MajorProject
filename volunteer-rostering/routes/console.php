<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\WebRole;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
 */

Artisan::command('new-super-admin', function () {
    $this->comment('Name');
    $userAttributes['name'] = $this->ask('What is your name?');
    $this->comment('Email');
    $userAttributes['email'] = $this->ask('What is your email?');
    $this->comment('Password');
    $userAttributes['password'] = $this->ask('What is your password?');

    $user = User::create($userAttributes);
    $webRole = WebRole::create(['role' => 'Volunteer']);
    $webRole2 = WebRole::create(['role' => 'Super Admin']);
    $user->webRoles()->save($webRole);
    $user->webRoles()->save($webRole2);
    $user->approved = true;
    $user->save();
    $this->comment('New Super Admin Created');
});
