
@extends('layouts.master')

@section('title', 'working timetables')

@section('content')
    <div class="">
        <h1>Qualifications</h1>
        <a class="button" href="{{ env('APP_URL') }}/qualification/create">Add Qualification</a>
        @if (count($qualifications) < 1)
            <x-list-empty-notice name="Qualifications"/>
        @else
        <div class="card-list">
            @foreach ($qualifications as $qualification)
                <div class="card clickable" data-qualification-card={{ $qualification->id }}>
                <div>
                    <h3>{{ $qualification->name }}</h3>
                    <form method="post" action="{{ $qualification->id }}/delete" style="display: inline;">
                        @csrf
                        <button type="submit" class="button alert" data-alert-text="delete qualification"><i class="fa-solid fa-trash"></i></button>
                    </form>
                <!--a class="alert button"><i class="fa-solid fa-trash"></i></a-->
                </div>
                <p>{{ $qualification->description }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <script>
//qualification js
document.querySelectorAll("[data-qualification-card]").forEach((elem) => {
    elem.addEventListener('click', (evt) => {
        if (!evt.target.classList.contains('alert')) {
            window.location.href = "{{ env("APP_URL") }}/qualification/" + elem.getAttribute('data-qualification-card');
        }
    });
})
    </script>
@endsection
