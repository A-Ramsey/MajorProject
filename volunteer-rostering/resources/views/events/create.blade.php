@extends('layouts.master')

@section('title', 'Create Event')

@section('content')
    <div class="">
        <h1>Create Event</h1>
        <form method="POST" action="create" class="cell form" enctype="multipart/form-data">
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    Event Name
                </label>
                <input class="field @error('name') field-err @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error ('name')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="day">
                    Day
                </label>
                <input type="date" class="field @error('day') field-err @enderror" name="day" id="day" value="{{ old('day') }}" required>
                @error ('day')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="notes">
                    Notes
                </label>
                <textarea rows=3 class="field @error('notes') field-err @enderror" name="notes" id="notes">{{ old('notes') }}</textarea>
                @error ('notes')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="working_timetable">
                    Working Timetable
                </label>
                <select class="field @error('working_timetable') field-err @enderror" name="working_timetable" id="working_timetable" value="{{ old('working_timetable') }}" required>
                    @foreach ($workingTTs as $workingTT)
                        <option value="{{ $workingTT->id }}">{{ $workingTT->name }}</option>
                    @endforeach
                </select>
                @error ('working_timetable')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>
@endsection
