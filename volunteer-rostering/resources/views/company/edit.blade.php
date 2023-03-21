@extends('layouts.master')

@section('title', 'Edit Company')

@section('content')
    <div class="">
        <h1>Edit Company - {{ $company->name }}</h1>
        <form method="POST" action="edit" class="cell form" enctype="multipart/form-data">
            @csrf
            <div class="cell">
                <label class="field-title" for="name">
                    Company Name
                </label>
                <input class="field @error('name') field-err @enderror" type="text" name="name" id="name" value="{{ old('name', $company->name) }}" required>
                @error ('name')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="primary_colour">
                    Primary Colour
                </label>
                <input class="field @error('primary_colour') field-err @enderror" type="color" name="primary_colour" id="primary_colour" value="{{ old('primary_colour', $company->primary_colour) }}" required>
                @error ('primary_colour')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <label class="field-title" for="secondary_colour">
                    Secondary Colour
                </label>
                <input class="field @error('secondary_colour') field-err @enderror" type="color" name="secondary_colour" id="secondary_colour" value="{{ old('secondary_colour', $company->secondary_colour) }}" required>
                @error ('secondary_colour')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>
@endsection
