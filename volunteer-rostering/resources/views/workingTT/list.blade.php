@extends('layouts.master')

@section('title', 'Working Timetables')

@section('content')
    <div class="">
        <h1>Working Timetables</h1>
        
        @if (auth()->user()->hasPermission(['Administrator']))
        <a class="button" href="{{ env('APP_URL') }}/workingTT/create">Add Working Timetable</a>
        @endif
        @if (count($workingTTs) < 1)
            <x-list-empty-notice name="Working Timetables"/>
        @else
        <div class="card-list">
            @foreach ($workingTTs as $workingTT)
                <div class="card clickable" data-workingtt-card={{ $workingTT->id }}>
                <div>
                    <h3>{{ $workingTT->name }}</h3>
                    <form method="post" action="{{ $workingTT->id }}/delete" style="display: inline;">
                        @csrf
                        <button type="submit" class="button alert" data-alert-text="delete working timetable"><i class="fa-solid fa-trash"></i></button>
                    </form>
                <!--a class="alert button"><i class="fa-solid fa-trash"></i></a-->
                </div>
                <p>{{ $workingTT->description }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <script>
//working timetables js
document.querySelectorAll("[data-workingtt-card]").forEach((elem) => {
    elem.addEventListener('click', (evt) => {
        if (!evt.target.classList.contains('alert')) {
            window.location.href = "{{ env("APP_URL") }}/workingTT/" + elem.getAttribute('data-workingtt-card');
        }
    });
})
    </script>
@endsection
