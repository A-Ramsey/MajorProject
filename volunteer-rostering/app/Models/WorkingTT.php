<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\Qualification;

class WorkingTT extends Model
{
    use HasFactory;

    public function event() {
        return $this->hasMany(Event::class);
    }

    public function qualifications() {
        return $this->belongsToMany(Qualification::class);
    }

    protected $fillable = [
        'name',
        'description', 
        'pdf',
    ];
}
