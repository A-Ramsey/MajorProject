@extends('layouts.master')

@section('title', 'Personal Calendar')

@section('content')
@php
use App\Models\Company;
$company = Company::firstOrCreate([
    'id' => 1
]);
@endphp

    <div class="calendar">
        <h1>My Scheduled Events</h1>
        <div id="personal-calendar"></div>
    </div>
    <div class="page-content cell large-10 medium-10 small-12 grid-x">
        <form method="POST" action="add-availability" class="large-5 small-12 cell form" enctype="multipart/form-data">
            <h5>Add Availablity</h5>
            @csrf
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
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
            <br>
        <form method="POST" action="delete-availability" class="large-5 small-12 cell form" enctype="multipart/form-data">
            <h5>Delete Availablity</h5>
            @csrf
            <div class="cell">
                <label class="field-title" for="delDay">
                    Select Availability
                </label>
                <select class="field @error('delDay') field-err @enderror" name="delDay" id="delDay" value="{{ old('delDay') }}" required>
                    @foreach (auth()->user()->availability()->get() as $availability)
                        <option value="{{ $availability->id }}">{{ date('d/m/Y', strtotime($availability->day)) }}</option>
                    @endforeach
                </select>
                @error ('delDay')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>

    <script>
function personalFullCalendarInitialisation() {
  //From full calendar example
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('personal-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      timeZone: 'UTC',
      initialView: 'dayGridMonth',
      selectable: true,
      unselectAuto: false,
      height: 'auto',
      events: [
          @foreach (auth()->user()->availability()->get() as $availability)
              {
                  title: "Availability",
                  start: '{{ $availability->day }}',
                  id: {{ $availability->id }},
                  color: "{{ $company->primary_colour }}",
                  display: "background",
              },
          @endforeach
          @foreach (auth()->user()->eventVolunteer()->get() as $eventVolunteer)
              {
                  @php
                      $event = $eventVolunteer->event;
                  @endphp
                  title: "{{ $event->name }}",
                  start: '{{ $event->day }}',
                  id: {{ $event->id }},
                  color: "{{ $company->secondary_colour }}"
              }@if (!$loop->last),@endif
          @endforeach
      ],
      eventClick: function(info) {
        if (info.event.id) {
          window.open("event/" + info.event.id);
        }
      },
    });
    calendar.render();
  });
}

personalFullCalendarInitialisation()
    </script>
@endsection
