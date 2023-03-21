<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkingTT;
use App\Models\EventVolunteer;

class Event extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'day',
        'notes',
    ];

    public function rosterConfirmed() {
        foreach($this->eventVolunteers()->get() as $eventVolunteer) {
            if ($eventVolunteer->qualification != null) {
                return true;
            }
        }
        return false;
    }

    public function isRostered($user) {
        foreach ($this->eventVolunteers()->get() as $eventVolunteer) {
            if ($eventVolunteer->qualification == null) {
                continue;
            }
            if ($eventVolunteer->user == $user) {
                return true;
            }
        }
        return false;
    }

    public function isQualFillable($qualification, $user=false) {
        $fillableQuals = $this->getUnfilledQuals($user, true, true);

        if (in_array($qualification->id, $fillableQuals)) {
            return true;
        }

        return false;
    }

    public function getFilledQuals($idOnly = false) {
        $filledQuals = [];
        foreach ($this->eventVolunteers()->get() as $eventVol) {
            if ($eventVol->qualification != null) {
                if ($idOnly) {
                    array_push($filledQuals, $eventVol->qualification->id);
                } else {
                    array_push($filledQuals, $eventVol->qualification);
                }
            }
        }
        return $filledQuals;
    }

    public function getFilledPossibleQuals($idOnly = false, $qualWithUsers = false) {
        $filledQualIds = [];
        $filledQuals = [];
        foreach ($this->eventVolunteers()->get() as $eventVol) {
            foreach($eventVol->possibleQualifications()->get() as $qual) {
                if(in_array($qual->id, $filledQualIds)) {
                    //add user to filled quals
                    if ($qualWithUsers) {
                        array_push($filledQuals[$qual->id]['users'], $eventVol->user);
                    }
                } else {
                    //add id to filled quals
                    array_push($filledQualIds, $qual->id);

                    if ($qualWithUsers) {
                        $qualWithUsersData = ['qualification' => $qual];
                        $qualWithUsersData['users'] = [$eventVol->user];
                        $filledQuals[$qual->id] = $qualWithUsersData;
                    } else {
                        array_push($filledQuals, $qual);
                    }
                }
            }
        }
        if ($idOnly) {
            return $filledQualIds;
        }
        return $filledQuals;
    }

    public function getUnfilledPossibleQuals($idOnly = false) {
        $filledQuals = $this->getFilledPossibleQuals(true);
        $unfilledQuals = [];
        foreach($this->workingTT->qualifications()->get() as $qual) {
            if (!in_array($qual->id, $filledQuals)) {
                array_push($unfilledQuals, $qual);
            }
        } 
        return $unfilledQuals;
    }

    public function getUserFillableQuals($user, $idOnly = false, $includeTrainee = false) {
        if ($includeTrainee) {
            $userQuals = $user->getAllQualifications(true);
        } else {
            $userQuals = $user->getSafeQualifications(true);
        }

        $allQuals = $this->getUnfilledQuals();
        $quals = [];
        foreach ($allQuals as $qual) {
            if (in_array($qual->id, $userQuals)) {
                if ($idOnly) {
                    array_push($quals, $qual->id);
                } else {
                    array_push($quals, $qual);
                }
            }
        }
        return $quals;
    }

    /**
     * Function to get all the unfilled qualifications on an event
     *
     * @param $user takes a user and if provided get the unfilled qualifications that the user can do
     */
    public function getUnfilledQuals($user=false, $idOnly=false, $includeTrainee = false) {
        $filledQualIds = $this->getFilledQuals(true);
        if ($user != false) {
            if ($includeTrainee) {
                $userQuals = $user->getAllQualifications(true);
            } else {
                $userQuals = $user->getSafeQualifications(true);
            }
        } else {
            $userQuals = [];
        }
        
        $allQuals = $this->workingTT->qualifications()->get();
        $unfilledQuals = [];
        foreach ($allQuals as $qual) {
            if (
                !in_array($qual->id, $filledQualIds) and 
                ($user == false or in_array($qual->id, $userQuals))
            ) {
                if ($idOnly) {
                    array_push($unfilledQuals, $qual->id);
                } else {
                    array_push($unfilledQuals, $qual);
                }
            }
        }
        return $unfilledQuals;
    }

    public function workingTT() {
        return $this->belongsTo(WorkingTT::class);
    }

    public function eventVolunteers() {
        return $this->hasMany(EventVolunteer::class);
    }
}
