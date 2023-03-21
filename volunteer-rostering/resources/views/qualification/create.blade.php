@extends('layouts.master')

@section('title', 'Create Qualification')

@section('content')
    <div class="">
        <h1>Create Qualification</h1>
        <form method="POST" action="create" class="cell form" enctype="multipart/form-data">
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    Qualification Name
                </label>
                <input class="field @error('name') field-err @enderror" type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error ('name')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="description">
                    Description
                </label>
                <textarea class="field @error('description') field-err @enderror" name="description" id="description" rows="3" required>{{ old('description') }}</textarea>
                @error ('description')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="instructor_level">
                    Include Instructor Level
                </label>
                <input type="checkbox" class="field @error('instructor_level') field-err @enderror" name="instructor_level" id="instructor_level" value="true" @checked(old('instructor_level') == "true")>
                @error ('instructor_level')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="included_qualification">
                    Included Qualification
                </label>
                <select class="field @error('included_qualification') field-err @enderror" name="included_qualification" id="included_qualification" value="{{ old('included_qualification') }}" required>
                    <option value="-1">No included qualifications</option>
                    @foreach ($qualifications as $qualification)
                        <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
                    @endforeach
                </select>
                @error ('included_qualification')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>
@endsection
