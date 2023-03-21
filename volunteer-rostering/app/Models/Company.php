<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'primary_colour',
        'secondary_colour',
    ];

    protected $attributes = [
        'name' => 'Default company name',
        'primary_colour' => '',
        'secondary_colour' => '',
    ];
}
