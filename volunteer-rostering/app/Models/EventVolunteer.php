<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Qualification;
use App\Models\Event;
use App\Models\User;

class EventVolunteer extends Model
{
    use HasFactory;

    public $fillable = [
        'event_id',
        'user_id',
        'extra',
    ];

    protected $attributes = [
        'extra' => false,
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function qualification() {
        return $this->belongsTo(Qualification::class);
    }

    Public function possibleQualifications() {
        return $this->belongsToMany(Qualification::class, 'possible_qualifications');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
