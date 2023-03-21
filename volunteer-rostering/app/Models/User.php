<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Level;
use App\Models\WebRole;
use App\Enums\WebRoleEnum;
use App\Models\EventVolunteer;
use App\Models\Availability;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $attributes = [
        'approved' => false,
    ];

    public function hasPermission($roles) {
        if (auth()->user()->isSuperAdmin()) {
            return true;
        }
        foreach($roles as $role) {
            $roleTemp = WebRoleEnum::coerce(trim(ucwords($role)));
            if (auth()->user()->hasWebRole($roleTemp->value)) {
                return true;
            }
        }
        return false;
    }

    private function isSuperAdmin() {
        return $this->hasWebRole("Super Admin");
    }

    public function getRoleNames() {
        $first = true;
        $text = "";
        foreach ($this->webRoles()->get() as $webRole) {
            if (!$first) {
                $text .= ", ";
            }
            $first =  false;
            $text .= $webRole->role;
        }
        return $text;
    }

    public function hasWebRole($checkRole) {
        $checkRole = ucwords($checkRole);
        $roles = $this->webRoles()->get();
        foreach ($roles as $role) {
            if ($role->role == $checkRole) {
                return true;
            }
        }
        return false;
    }

    public function isSafe($qual, $returnLevel = false) {
        foreach ($this->levels()->get() as $userLevel) {
            if ($userLevel->qualification->id == $qual->id) {
                if ($returnLevel) {
                    return $userLevel->name;
                } else {
                    return $userLevel->safe;
                }
            } else {
                continue;
            }
        }
        return false;
    }

    public function getSafeQualifications($idOnly = false, $event = false) {
        if ($event) {
            $eQuals = $event->workingTT->qualifications()->get()->all();
            $eQuals = array_map(fn ($qual) => $qual->id, $eQuals);
        }
        $userQuals = [];
        foreach ($this->levels()->get() as $userLevel) {
            if (!$event or in_array($userLevel->qualification->id, $eQuals)) {
                if ($userLevel->safe) {
                    if ($idOnly) {
                        array_push($userQuals, $userLevel->qualification->id);
                    } else {
                        array_push($userQuals, $userLevel->qualification);
                    }
                }
            }
        }
        return $userQuals;
    }

    public function getAllQualifications($idOnly = false, $event = false) {
        if ($event) {
            $eQuals = $event->workingTT->qualifications()->get()->all();
            $eQuals = array_map(fn ($qual) => $qual->id, $eQuals);
        }
        $userQuals = [];
        foreach ($this->levels()->get() as $userLevel) {
            if (!$event or in_array($userLevel->qualification->id, $eQuals)) {
                if ($idOnly) {
                    array_push($userQuals, $userLevel->qualification->id);
                } else {
                    array_push($userQuals, $userLevel->qualification);
                }
            }
        }
        return $userQuals;
    }

    public function availability() {
        return $this->hasMany(Availability::class);
    }

    public function levels() {
        return $this->belongsToMany(Level::class, 'level_user');
    }

    public function eventVolunteer() {
        return $this->hasMany(EventVolunteer::class);
    }

    public function webRoles() {
        return $this->hasMany(WebRole::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password) {
        $this->attributes['password'] = bcrypt($password);
    }
}
