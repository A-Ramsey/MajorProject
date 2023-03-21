@extends('layouts.master')

@section('title', 'Event')

@section('content')
    <div class="">
        <h1>View Event - {{ $event->name }}</h1>
        <div class="bordered-box">
            <h4 class="bordered-box-title">Info</h4>
            <div class="bordered-box-content">
                @if (auth()->user()->hasPermission(['Administrator', 'Roster Clerk']))
                    @if (!$event->rosterConfirmed())
                        <a href="{{ env('APP_URL') }}/event/{{ $event->id }}/edit" type="button" class="button">Edit Event</a>
                    @endif
                    <form method="post" action="{{ $event->id }}/delete" style="display: inline;">
                        @csrf
                        <button type="submit" class="button alert" data-alert-text="delete event">Delete Event</button>
                    </form>
                @endif
                <p><span class="bold-nice-text">Event Date: </span>{{ date('d M Y', strtotime($event->day)) }}</p>
                <p><strong>Notes:</strong><br>{{ $event->notes }}</p>
                <p><strong>Working Timetable:</strong><br>{{ $event->workingTT->name }}</p>
                <a class="button" href="{{ env('APP_URL') }}/workingTT/{{ $event->workingTT->id }}">View Working Timetable</a>
            </div>
        </div>
        <div class="bordered-box">
            <h4 class="bordered-box-title">Staff</h4>
            <div class="bordered-box-content">
                @if (auth()->user()->hasPermission(['Administrator', 'Roster Clerk']))
                    <a href="{{ env('APP_URL') }}/event/{{ $event->id }}/add-staff" type="button" class="button">Add Staff</a>
                    <a href="{{ env('APP_URL') }}/event/{{ $event->id }}/confirm-roster" type="button" class="button">Confirm Roster</a>
                @endif
                <a type="button" class="button" href="{{ env('APP_URL') }}/event/{{ $event->id }}/volunteer">Volunteer</a>

                @if($fullRoster != [])
                    <div class="role-group">
                        <p class="role-area">Confirmed Roster</p>
                        <table>
                            <tr>
                                <th>Qualification</th>
                                <th>Main Roster</th>
                                <th>Extra Staff</th>
                            </tr>
                        @foreach($event->workingTT->qualifications()->get() as $qualification)
                            @php
                                $staff = ['main' => null, 'extra' => []];
                                if (array_key_exists($qualification->id, $fullRoster)) {
                                    $staff = $fullRoster[$qualification->id];
                                }
                            @endphp
                            <tr>
                                <td>{{ $qualification->name }}</td>
                                <td>
                                    @if ($staff['main'] != null)
                                        {{ $staff['main']->user->name }}
                                    @else
                                        No user rostered for this role
                                        <br>
                                        @if (auth()->user()->hasPermission(['Administrator', 'Roster Clerk']))
                                            <a class="button" href="{{ env('APP_URL') }}/email/event/{{ $event->id }}/need-volunteers/{{ $qualification->id }}">Email for Volunteers</a>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @forelse($staff['extra'] as $volunteer)
                                            @if (!$volunteer->user->isSafe($qualification)) {{ $volunteer->user->isSafe($qualification, true) }} - @endif{{ $volunteer->user->name }}
                                        <br>
                                    @empty
                                        No Volunteers for this role
                                    @endforelse
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                @else

                    <div class="role-group">
                        <p class="role-area">Qualifications with volunteers</p>
                        @forelse($filledQuals as $qual)
                            @if ($loop->first)
                                <label>You can still volunteer for these</label>
                            @endif
                            <p><strong><a href="{{ env('APP_URL') }}/qualification/{{ $qual['qualification']->id }}">{{ $qual['qualification']->name }}</a></strong>
                            @foreach ($qual['users'] as $user)
                                <br>
                                {{ $user->name }}
                            @endforeach
                            </p>
                        @empty
                            <p>No volunteered staff</p>
                        @endforelse
                    </div>

                    <div class="role-group">
                        <p class="role-area">Available qualified users</p>
                        @forelse($availableStaff as $availability)
                            <p>{{ $availability->user->name }}</p>
                        @empty
                            <p>No available qualified staff</p>
                        @endforelse
                    </div>

                    @if (count($unfilledQuals) != 0)
                    <div class="role-group unfilled-roles">
                        <p class="unfilled-roles-notice role-area">Qualifications where there are no volunteers</p>
                        @foreach ($unfilledQuals as $qual)
                            <p><strong><a href="{{ env('APP_URL') }}/qualification/{{ $qual->id }}">{{ $qual->name }}</strong> - </p></a>
                            @if (auth()->user()->hasPermission(['Administrator', 'Roster Clerk']))
                                <a class="button" href="{{ env('APP_URL') }}/email/event/{{ $event->id }}/need-volunteers/{{ $qual->id }}">Email for Volunteers</a>
                            @endif
                        @endforeach
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
