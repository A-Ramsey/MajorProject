@extends('layouts.master')

@section('title', 'Edit User')

@section('content')
    <div class="">
        <h1>Change Details</h1>
        <form method="POST" action="change-details" class="cell form" enctype="multipart/form-data">
            <a class="button" href="{{ env('APP_URL') }}/reset-password">Reset Password</a>
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    Name
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
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>
@endsection
