@extends('layouts.master')

@section('title', 'Register')
@section('content')
    <div class="">
        <h1>Register</h1>
        <form method="POST" action"register" class="cell form">
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    Name
                </label>
                <input class="field @error('name') field-err @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error ('name')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="email">
                    Email
                </label>
                <input class="field @error('email') field-err @enderror" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @error ('email')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
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
            </div>
        </form>
    </div>
@endsection
