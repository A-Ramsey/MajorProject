@extends('layouts.master')

@section('title', 'Give User Qualification')

@section('content')
    <div class="">
        <h1>Give User Qualification</h1>
        <form method="POST" action="assign" class="cell form" enctype="multipart/form-data">
            @csrf
            <h5><strong>Qualification:</strong> {{ $qualification->name }}</h5>
            <p>If user already has a different level of this qualification this will be updated to the new level set</p>
            <div class="cell">
                <label class="field-title" for="level">
                    Level
                </label>
                <select class="field @error('level') field-err @enderror" name="level" id="level" value="{{ old('level') }}" required>
                    @foreach ($qualification->levels()->orderBy('superiority')->get() as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
                @error ('level')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="user">
                    User
                </label>
                <select class="field @error('user') field-err @enderror" name="user" id="user" value="{{ old('user') }}" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error ('user')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>
@endsection
