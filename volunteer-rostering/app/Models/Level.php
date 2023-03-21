<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Qualification;
use App\Models\User;

class Level extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsToMany(User::class, 'level_user');
    }

    protected $fillable = [
        'name',
        'superiority',
        'safe',
    ];

    public function qualification() {
        return $this->belongsTo(Qualification::class);
    }
}
