@extends('layouts.master')

@section('title', 'Edit Qualification')

@section('content')
    <div class="">
        <h1>Edit Qualification</h1>
        <form method="POST" action="edit" class="cell form" enctype="multipart/form-data">
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    Qualification Name
                </label>
                <input class="field @error('name') field-err @enderror" type="text" name="name" id="name" value="{{ old('name', $qualification->name) }}" required>
                @error ('name')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="description">
                    Description
                </label>
                <textarea class="field @error('description') field-err @enderror" name="description" id="description" rows="3" required>{{ old('description', $qualification->description) }}</textarea>
                @error ('description')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="included_qualification">
                    Included Qualification
                </label>
                <select class="field @error('included_qualification') field-err @enderror" name="included_qualification" id="included_qualification" value="{{ old('included_qualification', $qualification->included_qualification) }}" required>
                    <option value="-1">No included qualifications</option>
                    @foreach ($qualifications as $qual)
                        <option @if($qualification->includedQualification() != null and $qualification->includedQualification()->id == $qual->id) selected="selected" @endif value="{{ $qual->id }}">{{ $qual->name }}</option>
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
