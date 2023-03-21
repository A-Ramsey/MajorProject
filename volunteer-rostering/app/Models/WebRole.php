<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\WebRoleEnum;
use App\Models\User;

class WebRole extends Model
{
    use HasFactory;

    protected $casts = [
        'role' => WebRoleEnum::class,
    ];

    protected $fillable = [
        'role',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
