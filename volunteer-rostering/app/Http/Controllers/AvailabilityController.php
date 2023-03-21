<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Availability;
use App\Models\User;

class AvailabilityController extends Controller
{
    public function addAvailability() {
        $user = auth()->user();
        $formData = request()->validate([
            'day' => 'required|date',
        ]);
        foreach ($user->availability()->get() as $availability) {
            if ($availability->day == $formData['day']) {
                throw ValidationException::withMessages(['day' => 'Already have availability for that day']);
            }
        }
        $availability = Availability::create($formData);
        $availability->user()->associate($user);
        $availability->save();
        return redirect('personal-calendar')->with('messages', 'Availability added successfully');
    }

    public function deleteAvailability() {
        $user = auth()->user();
        $formData = request()->validate([
            'delDay' => 'required|integer',
        ]);
        $availability = Availability::find($formData['delDay']);
        $availability->delete();
        return redirect('personal-calendar')->with('messages', 'Availability removed successfully');
    }
}
