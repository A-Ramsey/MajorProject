@extends('layouts.master')

@section('title', 'Volunteer for Event')

@section('content')
    <div class="">
        <h1>Volunteer for {{ $event->name }}</h1>
        <form method="POST" action="volunteer" class="cell form" enctype="multipart/form-data">
            @csrf
            @if(count($qualifications) == 0)
                <p>No qualifications left to volunteer for that you are qualified for.</p>
            @else
            <div class="cell">
                <label class="field-title" for="available_qualification">
                    Available Qualifications
                </label>
                <fieldset class="cell field @error('available_qualification') field-err @enderror" name="available_qualification" id="available_qualification">
                    @foreach ($qualifications as $qualification)
                        <input id="{{ $qualification->id }}" name="{{ $qualification->id }}" value="{{ $qualification->id }}" type="checkbox">
                        <label for="{{ $qualification->id }}">
                            @if (!$user->isSafe($qualification))
                                {{ $user->isSafe($qualification, true) }} - 
                            @endif
                            {{ $qualification->name }}
                        </label>
                        <br>
                    @endforeach
                </fieldset>
                @error ('available_qualification')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            @endif
            <div class="cell">
                <button id="submit" @if(count($qualifications) == 0) disabled @endif type="submit" class="cell button">Submit</button>
                @error ('submit')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </div>
@endsection
