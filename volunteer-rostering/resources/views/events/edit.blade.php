@extends('layouts.master')

@section('title', 'Edit Event')

@section('content')
    <div class="">
        <h1>Edit Event</h1>
        <form method="POST" action="edit" class="cell form" enctype="multipart/form-data">
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    Event Name
                </label>
                <input class="field @error('name') field-err @enderror" type="text" name="name" id="name" value="{{ old('name', $event->name) }}" required>
                @error ('name')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="day">
                    Day
                </label>
                <input type="date" class="field @error('day') field-err @enderror" name="day" id="day" value="{{ old('day', $event->day) }}" required>
                @error ('day')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="notes">
                    Notes
                </label>
                <textarea rows=3 class="field @error('notes') field-err @enderror" name="notes" id="notes">{{ old('notes', $event->notes) }}</textarea>
                @error ('notes')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="working_timetable">
                    Working Timetable
                </label>
                <select class="field @error('working_timetable') field-err @enderror" name="working_timetable" id="working_timetable" value="{{ old('working_timetable', $event->workingTT->id) }}" required>
                    @foreach ($workingTTs as $workingTT)
                        <option value="{{ $workingTT->id }}" @if($event->workingTT->id == $workingTT->id) selected @endif>{{ $workingTT->name }}</option>
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
