@extends('layouts.master')

@section('title', 'Qualification')

@section('content')
    <div class="">
        <h1>View Qualification - {{ $qualification->name }}</h1>
        <div class="bordered-box">
            <div class="bordered-box-title">
                <h4>Info</h4>
            </div>
            <div class="bordered-box-content">
                @if (auth()->user()->hasPermission(['Training Officer']))
                    <a class="button" href="{{ env('APP_URL') }}/qualification/{{ $qualification->id }}/assign">Assign Qualification to User</a>
                @endif
                <a class="button" href="{{ env('APP_URL') }}/qualification/{{ $qualification->id }}/edit">Edit Qualification</a>
                <form method="post" action="{{ $qualification->id }}/delete" style="display: inline;">
                    @csrf
                    <button type="submit" class="button alert" data-alert-text="delete qualification">Delete Qualification</button>
                </form>
                <p>{{ $qualification->description }}</p>
                <p><strong>Included Qualification:</strong> {{ $qualification->includedQualification()->name ?? "No Included Qualification" }}</p>
            </div>
        </div>
        <div class="bordered-box">
            <div class="bordered-box-title">
                <h4>Levels</h4>
            </div>
            <div class="bordered-box-content grid-x">
                <ul>
                @foreach ($qualification->levels as $level)
                    <li>{{ $level->name }}</li>
                @endforeach
                </ul>
            </div>
        </div>
        @foreach ($qualification->levels()->orderBy('superiority', 'DESC')->get() as $level)
            <div class="bordered-box">
                <div class="bordered-box-title">
                    <h4>{{ $level->name }}</h4>
                </div>
                <div class="bordered-box-content">
                    <h5>Users with this level:</h5>
                    <ul>
                        @forelse ($level->users()->get() as $user)
                            <li>{{ $user->name }}</li>
                        @empty
                            <p>No users qualified at this level</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endsection
