@extends('layouts.master')

@section('title', 'Edit User')

@section('content')
    <div class="">
        <h1>Reset Password - {{ $user->name }}</h1>
        <form method="POST" action="reset-password" class="cell form" enctype="multipart/form-data">
            @csrf
            <div class="cell">
                <label class="field-title" for="password">
                    Password
                </label>
                <input class="field @error('password') field-err @enderror" type="password" name="password" id="password" required>
                @error ('password')
                    <p class="form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="confirm_password">
                    Confirm Password
                </label>
                <input class="field @error('confirm_password') field-err @enderror" type="password" name="confirm_password" id="confirm_password" required>
                @error ('confirm_password')
                    <p class="form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
                @error ('submit')
                    <p class="form-err">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </div>
@endsection
