<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\WorkingTT;
use App\Models\Availability;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show($eventId) {
        $event = Event::find($eventId);
        $filledQuals = $event->getFilledPossibleQuals(false, true);
        $unfilledQuals = $event->getUnfilledPossibleQuals();
        $eventVolunteers = $event->eventVolunteers()->get();
        $allQuals = $event->workingTT->qualifications()->get()->all();
        $unrostered = [];
        $rosteredIds = [];
        $confirmedRoster = [];
        $fullRoster = [];

        foreach ($eventVolunteers as $eventVolunteer) {
            if ($eventVolunteer->qualification != null) {
                if (array_key_exists($eventVolunteer->qualification->id, $fullRoster)) {
                    if ($eventVolunteer->extra) {
                        array_push($fullRoster[$eventVolunteer->qualification->id]['extra'], $eventVolunteer);
                    } else {
                        $fullRoster[$eventVolunteer->qualification->id]['main'] = $eventVolunteer;
                    }
                } else {
                    $tmp = [];
                    if ($eventVolunteer->extra) {
                        $tmp['extra'] = [$eventVolunteer];
                        $tmp['main'] = null;
                    } else {
                        $tmp['main'] = $eventVolunteer;
                        $tmp['extra'] = [];
                    }
                    $fullRoster[$eventVolunteer->qualification->id] = $tmp;
                }
            }
        }
        $availableStaff = [];
        foreach (Availability::where('day', $event->day)->get() as $availability) {
            $userQuals = $availability->user->getAllQualifications();
            $qualIds = array_map(fn ($qual) => $qual->id, $userQuals);
            foreach ($allQuals as $qual) {
                if (in_array($qual->id, $qualIds)) {
                    array_push($availableStaff, $availability);
                    break;
                }
            }
        }

        return view('events.view', [
            'event' => $event,
            'filledQuals' => $filledQuals,
            'unfilledQuals' => $unfilledQuals,
            'eventVolunteers' => $eventVolunteers,
            'fullRoster' => $fullRoster,
            'availableStaff' => $availableStaff,
        ]);
    }

    public function create() {
        $workingTTs = WorkingTT::all();
        return view('events.create', [
            'workingTTs' => $workingTTs,
        ]);
    }

    public function store() {
        $eventAttr = request()->validate([
            'name' => 'required|max:255',
            'day' => 'required|date',
            'notes' => 'max:255',
        ]);
        $workingTTid = request()->validate([
            'working_timetable' => 'required|integer|exists:working_t_t_s,id',
        ])['working_timetable'];

        if (strtotime($eventAttr['day']) < strtotime('today')) {
            throw ValidationException::withMessages(['day' => 'Event must not be in the past']);
        }

        $event = Event::create($eventAttr);
        $workingTT = WorkingTT::find($workingTTid);
        $event->workingTT()->associate($workingTT);
$event->save();
        return redirect('/')->with('messages', 'Event created successfully');
    }

    public function edit($eventId) {
        $event = Event::find($eventId);
        if ($event->rosterConfirmed()) {
            return redirect('event/' . $event->id)->with('messages', 'Event can\'t be edited as the roster is confirmed');
        }
        $workingTTs = WorkingTT::all();

        return view('events.edit', [
            'event' => $event,
            'workingTTs' => $workingTTs,
        ]);
    }

    public function update($eventId) {
        $formData = request()->validate([
            'name' => 'required|max:255',
            'day' => 'required|date',
            'notes' => 'max:255',
        ]);
        $workingTTid = request()->validate([
            'working_timetable' => 'required|integer|exists:working_t_t_s,id',
        ]);
        $event = Event::find($eventId);
        if ($event->rosterConfirmed()) {
            return redirect('event/' . $event->id)->with('messages', 'Event can\'t be edited as the roster is confirmed');
        }
        $event->update($formData);
        if ($event->workingTT->id != $workingTTid['working_timetable']) {
            $event->workingTT()->disassociate($event->workingTT);
            $workingTT = WorkingTT::find($workingTTid['working_timetable']);
            $event->workingTT()->associate($workingTT);
            $event->save();
        }
        return redirect('event/' . $event->id)->with('messages', 'Event updated');
    }

    public function delete($eventId) {
        $event = Event::find($eventId);
        $event->delete();
        return redirect('/')->with('messages', 'Event deleted');
    }
}
