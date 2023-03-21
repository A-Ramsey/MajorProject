@extends('layouts.master')

@section('title', 'Create Working Timetable')

@section('content')
    <div class="">
        <h1>Create Working Timetables</h1>
        <form method="POST" action="create" class="cell form" enctype="multipart/form-data">
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    Working Timetable Name
                </label>
                <input class="field @error('name') field-err @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error ('name')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="description">
                    Working Timetable Description
                </label>
                <textarea class="field @error('description') field-err @enderror" name="description" id="description" required rows=3>{{ old('description') }}</textarea>
                @error ('description')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="pdf">
                    Working Timetable PDF
                </label>
                <input class="field @error('pdf') field-err @enderror" type="file" name="pdf" id="pdf" value="{{ old('pdf') }}" required accept=".pdf">
                @error ('pdf')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>
@endsection
