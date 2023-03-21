@extends('emails.wrapper.plain')

@section('name', $eventVolunteer->user->name ?? 'Sir/Madam')

@section('content')
You have been rostered for {{ $eventVolunteer->event->name }} on the {{ date('d/m/Y', strtotime($eventVolunteer->event->day)) }} for {{ $eventVolunteer->qualification->name }} as @if ($eventVolunteer->extra){{ "an extra member of staff" }}@else{{ "the primary member of staff" }}@endif.
See it here: {{ env('APP_URL') }}/event/{{ $eventVolunteer->event->id }}
@endsection
