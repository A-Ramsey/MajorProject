@extends('layouts.master')

@section('title', 'Volunteer for Event')

@section('content')
    <div class="">
        <h1>Confirm Roster for {{ $event->name }}</h1>
        <form method="POST" action="confirm-roster" class="cell form" enctype="multipart/form-data">
            @csrf
            <label>You cannot select the same memeber of staff for different roles</label>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
            <div class="cell">
                <table>
                    <tr>
                        <th>Qualification</th>
                        <th>Main Roster</th>
                        <th>Extra volunteers (i.e. Trainees)</th>
                    </tr>
                    @foreach($event->workingTT->qualifications()->get() as $qualification)
                        <tr>
                            <td>{{ $qualification->name }}</td>
                            <td>
                                @forelse($safeVolunteers[$qualification->id] as $volunteer)
                                    <input id="{{ $volunteer->user->id }}" name="{{ $qualification->id }}" value="{{ $volunteer->user->id }}" type="radio" data-volunteer>
                                    <label for="{{ $volunteer->user->id }}">
                                        {{ $volunteer->user->name }}
                                    </label>
                                    <br>
                                @empty
                                    No Safe Staff for this role
                                @endforelse
                                <hr>
                                @if (array_key_exists($qualification->id, $availableSafe))
                                    @forelse($availableSafe[$qualification->id] as $availability)
                                        <input id="available_{{ $availability->user->id }}_{{ $qualification->id }}" name="available_{{ $availability->user->id }}_{{ $qualification->id }}" value="{{ $availability->user->id }}" type="radio" datavolunteer>
                                        <label for="available_{{ $availability->user->id }}_{{ $qualification->id }}">
                                            {{ $availability->user->name }}
                                        </label>
                                        <br>
                                    @empty
                                        No available users for this role
                                    @endforelse
                                @else
                                    No available users for this role
                                @endif
                            </td>
                            <td>
                                @forelse($volunteers[$qualification->id] as $volunteer)
                                    <input id="extra_{{ $volunteer->user->id }}_{{ $qualification->id }}" name="extra_{{ $volunteer->user->id }}_{{ $qualification->id }}" value="{{ $volunteer->user->id }}" type="checkbox" data-volunteer>
                                    <label for="extra_{{ $volunteer->user->id }}_{{ $qualification->id }}">
                                        @if (!$volunteer->user->isSafe($qualification)) {{ $volunteer->user->isSafe($qualification, true) }} - @endif{{ $volunteer->user->name }}
                                    </label>
                                    <br>
                                @empty
                                    No Volunteers for this role
                                @endforelse
                                <hr>
                                @if (array_key_exists($qualification->id, $availableSafe))
                                    @forelse($available[$qualification->id] as $availability)
                                        <input id="available_extra_{{ $availability->user->id }}_{{ $qualification->id }}" name="available_extra_{{ $availability->user->id }}_{{ $qualification->id }}" value="{{ $availability->user->id }}" type="radio" datavolunteer>
                                        <label for="available_extra_{{ $availability->user->id }}_{{ $qualification->id }}">
                                            {{ $availability->user->name }}
                                        </label>
                                        <br>
                                    @empty
                                        No available users for this role
                                    @endforelse
                                @else
                                    No available users for this role
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div Class="cell">
                <button type="submit" class="cell button">Submit</button>
                @error ('submit')
                <p class="cell form-err">{{ $message }}</p>
            @enderror
            </div>
        </form>
    </div>
    <script>
//keeps you from selecting the same member of staff for different roles
Array.from(document.querySelectorAll('[data-volunteer]')).forEach((elem) => {
    elem.addEventListener('click', () => {
        let isChecked = elem.checked;
        Array.from(document.querySelectorAll('[data-volunteer][value="' + elem.value + '"]')).forEach((elem) => {
            console.log(elem);
            elem.checked = false;
        });
        //Allows checkboxes to be unticked but not radio buttons
        if ((elem.type == "checkbox" && isChecked) || (elem.type == "radio")) {
            elem.checked = true;
        }
    });
});
    </script>
@endsection
