<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\EventVolunteer;
use App\Models\Qualification;
use App\Models\Event;
use App\Models\User;
use App\Models\Availability;

class EventVolunteerController extends Controller
{
    public function create($eventId) {
        $event = Event::find($eventId);
        $user = auth()->user();
        $availableQuals = $event->getUserFillableQuals($user, false, true);

        return view('eventVolunteer.create', [
            'user' => $user,
            'event' => $event,
            'qualifications' => $availableQuals,
        ]);
    }

    public function store($eventId) {
        $event = Event::find($eventId);
        $user = auth()->user();

        $formData = request()->validate([
            'available_qualification' => '',
        ]);
        $quals = request()->request;
        $quals->remove('_token');
        $qualifications = [];
        if ($quals->count() == 0) {
            throw ValidationException::withMessages(['available_qualification' => 'Must add at least one qualification']);
        }

        foreach ($quals as $qual) {
            $qualification = Qualification::find($qual);
            if (!$event->isQualFillable($qualification, $user)) {
                throw ValidationException::withMessages(['submit' => $qualification->name . ' not fillable']);
            }
            array_push($qualifications, $qualification);
        }

        $eventVolunteer = EventVolunteer::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id], []
        );
        $eventVolunteer->user()->associate($user);
        $eventVolunteer->event()->associate($event);
        foreach ($qualifications as $qualification) {
            if (!$eventVolunteer->possibleQualifications()->get()->contains($qualification)) {
                $eventVolunteer->possibleQualifications()->attach($qualification);
            }
        }
        $eventVolunteer->save();

        return redirect('event/' . $event->id)->with('messages', 'You have volunteered for this event!');
    }

    public function addStaffCreate($eventId) {
        $event = Event::find($eventId);
        $users = User::all();
        $userQuals = [];
        foreach ($users as $user) {
            $userQuals[$user->id] = $event->getUserFillableQuals($user, true, true);
        }
        $allQuals = $event->workingTT->qualifications()->get();
        if ($allQuals->isEmpty()) {
            session()->flash('messages', 'No qualifications to add staff for');
        }
        return view('eventVolunteer.addStaff', [
            'event' => $event,
            'users' => $users,
            'userQuals' => $userQuals,
            'allQuals' => $allQuals,
        ]);
    }

    public function addStaffStore($eventId) {
        $event = Event::find($eventId);
        $formData = request()->validate([
            'staff' => 'required|integer',
        ]);
        $user = User::find($formData['staff']);
        $quals = request()->request;
        $quals->remove('_token');
        $quals->remove('staff');
        if ($quals->count() == 0) {
            throw ValidationException::withMessages(['available_qualification' => 'Must add at least one qualification']);
        }
        $availableQuals = $user->getAllQualifications(true);
        $possibleQuals = array_intersect($quals->all(), $availableQuals);
        $qualifications = array_map(
            fn($qualId) => Qualification::find($qualId)
        , $possibleQuals);
        
        $eventVolunteer = EventVolunteer::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id], []
        );
        $eventVolunteer->user()->associate($user);
        $eventVolunteer->event()->associate($event);
        foreach ($qualifications as $qualification) {
            if (!$eventVolunteer->possibleQualifications()->get()->contains($qualification)) {
                $eventVolunteer->possibleQualifications()->attach($qualification);
            }
        }
        $eventVolunteer->save();
        
        return redirect('event/' . $event->id)->with('messages', 'Staff interest added');
    }

    public function confirmRosterCreate($eventId) {
        $event = Event::find($eventId);
        $volunteers = [];
        $safeVolunteers = [];
        $volunteeredUsersIds = [];
        foreach ($event->workingTT->qualifications()->get() as $qual) {
            $posVol = $qual->possibleEventVolunteers()->where('event_id', $event->id)->get()->all();
            $volunteers[$qual->id] = $posVol;

            $safeForQual = [];
            foreach ($posVol as $eventVolunteer) {
                if (!in_array($eventVolunteer->user->id, $volunteeredUsersIds)) {
                    array_push($volunteeredUsersIds, $eventVolunteer->user->id);
                }
                if ($eventVolunteer->user->isSafe($qual)) {
                    array_push($safeForQual, $eventVolunteer);
                }
            }
            $safeVolunteers[$qual->id] = $safeForQual;
        }

        $available = [];
        $availableSafe = [];
        foreach (Availability::where('day', $event->day)->get() as $availability) {
            if (in_array($availability->user->id, $volunteeredUsersIds)) {
                continue;
            }
            $sQuals = $availability->user->getSafeQualifications(false, $event);
            foreach ($sQuals as $qual) {
                if (array_key_exists($qual->id, $availableSafe)) {
                    array_push($availableSafe[$qual->id], $availability);
                } else {
                    $availableSafe[$qual->id] = [$availability];
                }
            }
            $aQuals = $availability->user->getAllQualifications(false, $event);
            foreach ($aQuals as $qual) {
                if (array_key_exists($qual->id, $available)) {
                    array_push($available[$qual->id], $availability);
                } else {
                    $available[$qual->id] = [$availability];
                }
            }
        }
        return view('eventVolunteer.confirmRoster', [
            'event' => $event,
            'volunteers' => $volunteers,
            'safeVolunteers' => $safeVolunteers,
            'available' => $available,
            'availableSafe' => $availableSafe,
        ]);
    }

    public function confirmRosterStore($eventId) {
        $event = Event::find($eventId);
        $qualsWithStaff = request()->request;
        $qualsWithStaff->remove('_token');
        $usersWithQual = [];
        $users = [];
        $extras = [];
        $available = [];
        $availableExtras = [];
        foreach($qualsWithStaff as $qual => $user) {
            //way to check if user has volunteered for other roles
            if (array_count_values($qualsWithStaff->all())[$user]>1){
                throw ValidationException::withMessages([$qual => "User can't volunteer for multiple roles"]);
            }

            if (str_starts_with($qual, 'extra_')) {
                $qual = substr($qual, strpos($qual, '_', strpos($qual, '_') + 1) + 1);
                $fullUser = User::find($user);
                if (in_array($qual, $fullUser->getAllQualifications(true))) {
                    $extras[$user] = Qualification::find($qual);
                }
                continue;
            } else if (str_starts_with($qual, 'available_extra_')) {
                $qual = substr($qual, strpos($qual, '_', strpos($qual, '_', strpos($qual, '_') + 1) + 1) + 1);
                $fullUser = User::find($user);
                if (in_array($qual, $fullUser->getAllQualifications(true))) {
                    $availableExtras[$user] = Qualification::find($qual);
                }
                continue;
            } else if (str_starts_with($qual, 'available_')) {
                //has to be after availble extra or it picks up both
                $tmpUser = User::find($user);
                $tmpQual = Qualification::find(substr($qual, strpos($qual, '_', strpos($qual, '_') + 1) + 1));
                if ($tmpUser->isSafe($tmpQual)) {
                    $available[$user] = $tmpQual;
                } else {
                    throw ValidationException::withMessages(['submit' => "Can't roster user who is " . $tmpUser->isSafe($tmpQual, true) . " as a main roster for " . $qual->name]);
                }
                continue;
            } else {
                $tmpUser = User::find($user);
                $tmpQual = Qualification::find($qual);
                if ($tmpUser->isSafe($tmpQual)) {
                    $usersWithQual[$user] = $qual;
                } else {
                    throw ValidationException::withMessages(['submit' => "Can't roster user who is " . $tmpUser->isSafe($tmpQual, true) . " as a main roster for " . $qual->name]);
                }
            }
        }
        $allEventVolunteers = [];
        //for users who volunteered or were added as staff to the event
        foreach ($event->eventVolunteers()->get() as $eventVolunteer) {
            if(array_key_exists($eventVolunteer->user->id, $usersWithQual)) {
                //main roster
                $qual = $usersWithQual[$eventVolunteer->user->id];
                $eventVolunteer->qualification()->associate($qual);
                $eventVolunteer->extra = false;
                array_push($allEventVolunteers, $eventVolunteer);
            } else if (array_key_exists($eventVolunteer->user->id, $extras)) {
                //extras
                $qual = $extras[$eventVolunteer->user->id];
                $eventVolunteer->qualification()->associate($qual);
                $eventVolunteer->extra = true;
                array_push($allEventVolunteers, $eventVolunteer);
            }
            $eventVolunteer->save();
        }
        //available and user for main roster
        foreach ($available as $userId => $qual) {
            $eventVolunteer = EventVolunteer::updateOrCreate(
                ['event_id' => $event->id, 'user_id' => $userId], []
            );
            $eventVolunteer->qualification()->associate($qual);
            $eventVolunteer->extra = false;
            $eventVolunteer->save();
            array_push($allEventVolunteers, $eventVolunteer);
        }
        //available and used for extra roster
        foreach ($availableExtras as $userId => $qual) {
            $eventVolunteer = EventVolunteer::updateOrCreate(
                ['event_id' => $event->id, 'user_id' => $userId], []
            );
            $eventVolunteer->qualification()->associate($qual);
            $eventVolunteer->extra = true;
            $eventVolunteer->save();
            array_push($allEventVolunteers, $eventVolunteer);
        }

        return redirect('email/event/' . $event->id . '/roster-confirm');
    }
}
