@extends('layouts.master')

@section('title', 'Working Timtetable')

@section('content')
    <div class="">
        <h1>View Working Timetable - {{ $workingTT->name }}</h1>
        <div class="bordered-box">
            <div class="bordered-box-title">
                <h4>Info</h4>
            </div>
            <div class="bordered-box-content">
                <a class="button" href="{{ env('APP_URL') }}/workingTT/{{ $workingTT->id }}/pdf">View Working Timetable PDF</a>
                @if (auth()->user()->hasPermission(['Administrator']))
                    <a class="button" href="{{ env('APP_URL') }}/workingTT/{{ $workingTT->id }}/edit">Edit Working Timetable</a>
                    <form method="post" action="{{ $workingTT->id }}/delete" style="display: inline;">
                        @csrf
                        <button type="submit" class="button alert" data-alert-text="delete working timetable">Delete Working Timetable</button>
                    </form>
                @endif
                <p>{{ $workingTT->description }}</p>
            </div>
        </div>
        <div class="bordered-box">
            <div class="bordered-box-title">
                <h4>Qualifications</h4>
            </div>
            <div class="bordered-box-content grid-x">
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    @foreach($workingTT->qualifications()->get() as $qualification)
                        <tr>
                            <td>{{ $qualification->name }}</td>
                            <td>{{ $qualification->description }}</td>
                            <td>
        @if (auth()->user()->hasPermission(['Administrator']))
                                <form method="post" action="{{ $workingTT->id }}/remove-qualification/{{ $qualification->id }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="button alert" data-alert-text="remove qualification from working timetable">Remove from Working Timetable</button>
                                </form>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="cell grid-x">
        @if (auth()->user()->hasPermission(['Administrator']))
                <form method="POST" action="{{ $workingTT->id }}/add-qualification" class="cell small-12 medium-6 large-3 form" enctype="multipart/form-data">
                    <h5>Add Qualification</h5>
                    @csrf
                    <div class="cell">
                        <label class="field-title" for="qualification">
                            Qualification
                        </label>
                        <select class="field @error('qualification') field-err @enderror" name="qualification" id="qualification" value="{{ old('qualification') }}" required>
                            @foreach ($qualifications as $qualification)
                                <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
                            @endforeach
                        </select>
                        @error ('qualification')
                            <p class="cell form-err">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="cell">
                        <label class="field-title" for="add_included_qualifications">
                            Add Included Qualifications
                        </label>
                        <input type="checkbox" class="field @error('add_included_qualifications') field-err @enderror" name="add_included_qualifications" id="add_included_qualifications" value="true" @checked(old('add_included_qualifications') == "true")>
                        @error ('add_included_qualifications')
                            <p class="cell form-err">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="cell">
                        <button type="submit" class="cell button">Submit</button>
                    </div>
                </form>
            @endif
                <div class="cell auto"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
