@extends('layouts.master')

@section('title', 'Home')

@section('content')
@php
use App\Models\Company;
$company = Company::firstOrCreate([
    'id' => 1
]);
@endphp

    <div class="calendar">
        <p style="color: white; background-color: {{ $company->secondary_colour }}; width: fit-content; padding: 0.3rem; border-radius: 0.5rem; display: inline-block;">Event you are rostered for</p>
        <p style="color: white; background-color: {{ $company->primary_colour }}; width: fit-content; padding: 0.3rem; border-radius: 0.5rem; display: inline-block;">Event you are not rostered for</p>
        <div id="calendar"></div>
    </div>

    <script>
function fullCalendarInitialisation() {
  //From full calendar example
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      timeZone: 'UTC',
      initialView: 'dayGridMonth',
      height: 'auto',
      events: [
          @foreach ($events as $event)
              {
                  title: "{{ $event->name }}",
                  start: '{{ $event->day }}',
                  id: {{ $event->id }},
                  color: @if ($event->isRostered(auth()->user())) "{{ $company->secondary_colour }}" @else "{{ $company->primary_colour }}" @endif
              }@if (!$loop->last),@endif
          @endforeach
      ],
      eventClick: function(info) {
        if (info.event.id) {
          window.open("event/" + info.event.id, '_self');
        }
      },
      @if (auth()->user()->hasPermission(['Administrator', 'Roster Clerk']))
      customButtons: {
        addButton: {
          text: "Add Event",
          click: function() {
            window.open("event/create", '_self');
          }
        }
      },
      @endif
      headerToolbar: {
        left: "@if (auth()->user()->hasPermission(['Administrator', 'Roster Clerk'])){{ 'addButton' }}@endif",
        center: 'title',
      }
    });
    calendar.render();
  });
}

fullCalendarInitialisation()
    </script>
@endsection
