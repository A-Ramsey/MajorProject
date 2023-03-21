<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventVolunteer;
use App\Models\Event;
use App\Models\Qualification;
use Mailgun\Mailgun;

class EmailController extends Controller
{
    private function initialisation() {
        return $mgClient = Mailgun::create(env('MAILGUN_SECRET'));
    }

    public function rosterConfirm($eventId) {
        $event = Event::find($eventId);
        $eventVolunteers = EventVolunteer::where('event_id', $eventId)->get();
        $mg = $this->initialisation();
        $domain = env('MAILGUN_DOMAIN');
        # Make the call to the client.
        foreach ($eventVolunteers as $eventVolunteer) {
            $result = $mg->messages()->send($domain, array(
                'from'  => env('MAIL_FROM_ADDRESS'),
                'to'    => $eventVolunteer->user->email,
                'subject' => 'You have been rostered for an event',
                'text'  => view('emails.content.rosterConfirm', ['eventVolunteer' => $eventVolunteer])->render()
            ));
        }
        return redirect('event/' . $eventId)->with('messages', 'Roster Confirmed');
    }

    public function needVolunteers($eventId, $qualId) {
        $qual = Qualification::find($qualId);
        $event = Event::find($eventId);
        $mg = $this->initialisation();
        $domain = env('MAILGUN_DOMAIN');
        $users = [];
        foreach ($qual->levels as $level) {
            if ($level->safe) {
                foreach ($level->users()->get() as $user) {
                    array_push($users, $user);
                    $result = $mg->messages()->send($domain, array(
                        'from'  => env('MAIL_FROM_ADDRESS'),
                        'to'    => $user->email,
                        'subject' => 'Volunteers Needed',
                        'text'  => view('emails.content.needVolunteers', ['qual' => $qual, 'event' => $event])->render()
                    ));
                }
            }
        }
        return redirect('event/' . $eventId)->with('messages', 'Email Sent');
    }
}
