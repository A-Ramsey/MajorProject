@extends('emails.wrapper.plain')

@section('name', $eventVolunteer->user->name ?? 'Sir/Madam')

@section('content')
Volunteers are needed for {{ $qual->name }} at the {{ $event->name }} on the {{ date('d/m/Y', strtotime($event->day)) }}.
Volunteer here: {{ env('APP_URL') }}/event/{{ $event->id }}
@endsection
