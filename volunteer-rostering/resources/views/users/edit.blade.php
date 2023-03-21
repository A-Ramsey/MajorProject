@extends('layouts.master')

@section('title', 'Edit User')

@section('content')
    <div class="">
        <h1>Edit User - {{ $user->name }}</h1>
        <form method="POST" action="edit" class="cell form" enctype="multipart/form-data">
            <a class="button" href="{{ env('APP_URL') }}/user/{{ $user->id }}/reset-password">Reset Password</a>
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    User Name
                </label>
                <input class="field @error('name') field-err @enderror" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @error ('name')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="email">
                    Email
                </label>
                <input class="field @error('email') field-err @enderror" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @error ('email')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="web_role">
                    Web Roles
                </label>
                <fieldset class="cell field @error('web_role') field-err @enderror" name="web_role" id="web_role">
                    @foreach ($webRoles as $role)
                        <input id="{{ $role['role']->key }}" name="web_role_{{ $role['role']->key }}" value="{{ $role['role']->key }}" type="checkbox" @if($role['checked']) checked="true" @endif>
                        <label for="{{ $role['role']->key }}">
                            {{ $role['role']->value }}
                        </label>
                        <br>
                    @endforeach
                </fieldset>
                @error ('web_role')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>
@endsection
