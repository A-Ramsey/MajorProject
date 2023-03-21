@extends('layouts.master')

@section('title', 'Login')
@section('content')
    <div class="">
        <h1>Login</h1>
        <form method="POST" action"login" class="cell form">
            @csrf
            <div class="cell">
                <label class="field-title" for="email">
                    Email
                </label>
                <input class="field" type="email" name="email" id="email" value="{{ old('email') }}" required>
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
                <button type="submit" class="cell button">Submit</button>
            </div>
            <p>Not a volunteer yet, register <a href="{{ env('APP_URL') }}/register">here</a></p>
        </form>
    </div>
@endsection
