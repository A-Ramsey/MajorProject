<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkingTT;
use App\Models\Level;
use App\Models\EventVolunteer;

class Qualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'included_qualification',
    ];

    //doesnt use a relationship to make things easier for a cyclical relationship
    public function includedQualification() {
        if ($this->included_qualification == null) {
            return null;
        }
        return Qualification::find($this->included_qualification);
    }

    public function workingTTs() {
        return $this->belongsToMany(WorkingTT::class);
    }

    public function levels() {
        return $this->hasMany(Level::class);
    }

    public function eventVolunteer() {
        return $this->hasMany(EventVolunteer::class);
    }

    public function possibleEventVolunteers() {
        return $this->belongsToMany(EventVolunteer::class, 'possible_qualifications');
    }
}
