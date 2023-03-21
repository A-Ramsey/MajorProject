@extends('layouts.master')

@section('title', 'Volunteer for Event')

@section('content')
    <div class="">
        <h1>Add staff for {{ $event->name }}</h1>
        <form method="POST" action="add-staff" class="cell form" enctype="multipart/form-data">
            @csrf
            <div class="cell">
                <div class="cell">
                    <label class="field-title" for="staff">
                        Staff Member
                    </label>
                    <select class="field @error('staff') field-err @enderror" name="staff" id="staff" value="{{ old('staff') }}" required>
                        @foreach($users as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="field-title" for="available_qualification">
                    Available Qualifications
                </label>
                <fieldset class="cell field @error('available_qualification') field-err @enderror" name="available_qualification" id="available_qualification">
                      @foreach ($allQuals as $qualification)
                        <input id="{{ $qualification->id }}" name="{{ $qualification->id }}" value="{{ $qualification->id }}" type="checkbox" data-qual-check>
                        <label for="{{ $qualification->id }}">
                            {{ $qualification->name }}
                        </label>
                        <br>
                    @endforeach
                </fieldset>
                @error ('available_qualification')
                    <p class="cell form-err">{{ $message }}</p>
                @enderror
            </div>
            <div class="cell">
                <button type="submit" class="cell button">Submit</button>
            </div>
        </form>
    </div>
    <script>
let userQuals = {};
@foreach ($userQuals as $user => $quals)
    let tmp{{ $user }} = []
    @foreach ($quals as $qual)
        tmp{{ $user }}.push(String({{ $qual }}));
    @endforeach
    userQuals[{{ $user }}] = tmp{{ $user }};
@endforeach
console.log(userQuals);

function inputChanger() {
    let curId = document.getElementById('staff').value;
    let quals = userQuals[curId];
    console.log(quals);
    Array.from(document.querySelectorAll('[data-qual-check]')).forEach((elem) => {
        console.log(quals.includes(elem.value));
        if (quals.includes(elem.id)) {
            if (elem.hasAttribute('disabled')) {
                elem.removeAttribute('disabled');
            }
            elem.labels[0].style.color = "Black";
        } else {
            elem.checked = false;
            elem.setAttribute('disabled', true);
            elem.labels[0].style.color = "#737373";
        }
        console.log(elem);
    });
}
inputChanger();
document.getElementById('staff').addEventListener('change', () => {
    inputChanger()
});
    </script>
@endsection
