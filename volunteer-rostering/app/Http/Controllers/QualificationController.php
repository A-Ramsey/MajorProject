<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qualification;
use App\Models\Level;
use App\Models\User;

class QualificationController extends Controller
{
    public function edit($qualificationId) {
        $qualification = Qualification::find($qualificationId);
        $qualifications = Qualification::where('id', '!=', $qualificationId)->get();
        

        return view('qualification.edit', [
            'qualification' => $qualification,
            'qualifications' => $qualifications,
        ]);
    }

    public function update($qualificationId) {
        $formData = request()->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'included_qualification' => 'required',
        ]);
        if ($formData['included_qualification'] == -1) {
            $formData['included_qualification'] = null;
        }

        $qualification = Qualification::find($qualificationId);
        $qualification->update($formData);

        return redirect('qualification/' . $qualification->id)->with('messages', 'Qualification updated');
    }

    public function create() {
        $qualifications = Qualification::all();
        return view('qualification.create', [
            'qualifications' => $qualifications,
        ]);
    }

    public function store() {
        $qualAttr = request()->validate([
            'name' => 'required|min:1|max:255',
            'description' => 'required|min:1|max:255',
            'included_qualification' => 'required|integer',
        ]);
        $extraQualAttr = request()->validate([
            'instructor_level' => ''
        ]);
        if ($qualAttr['included_qualification'] == -1) {
            $qualAttr['included_qualification'] = null;
        }

        $qualification = Qualification::create($qualAttr);

        $trainee = Level::create(['name' => 'Trainee', 'superiority' => 1, 'safe' => false]);
        $qualified = Level::create(['name' => 'Qualified', 'superiority' => 2, 'safe' => true]);
        $levels = [$trainee, $qualified];

        if (array_key_exists('instructor_level', $extraQualAttr) and $extraQualAttr['instructor_level'] == "true") {
            $instructor = Level::create(['name' => 'Instructor', 'superiority' => 3, 'safe' => true]);
            array_push($levels, $instructor);
        }

        $qualification->levels()->saveMany($levels);


        return redirect('qualification/list')->with('messages', 'Qualification created successfully');
    }

    public function list() {
        $qualifications = Qualification::all();
        return view('qualification.list', [
            'qualifications' => $qualifications,
        ]);
    }

    public function view($qualificationId) {
        $qualification = Qualification::find($qualificationId);
        return view('qualification.view', [
            'qualification' => $qualification,
        ]);
    }

    public function delete($qualificationId) {
        $qualification = Qualification::find($qualificationId);
        $levels = $qualification->levels()->get();
        foreach ($levels as $level) {
            $level->delete();
        }
        $qualification->delete();
        return redirect('qualification/list')->with('messages', 'Qualification deleted');
    }

    public function pickUserLevel($qualificationId) {
        $qualification = Qualification::find($qualificationId);
        $levels = $qualification->levels()->orderBy('superiority')->get();
        $users = User::all();
        return view('qualification.addUserQualification', [
            'qualification' => $qualification,
            'users' => $users,
        ]);
    }

    public function assignLevel($qualificationId) {
        $formData = request()->validate([
            'level' => 'required|integer|exists:levels,id',
            'user' => 'required|integer|exists:users,id'
        ]);
        $qualification = Qualification::find($qualificationId);
        $level = Level::find($formData['level']);
        $user = User::find($formData['user']);
        $otherLevels = $qualification->levels()->where('id', '!=', $level->id)->get();
        foreach($otherLevels as $otherLevel) {
            if ($otherLevel->users()->get()->contains($user)) {
                $otherLevel->users()->detach($user);
            }
        }
        if ($level->users()->get()->doesntContain($user)) {
            $level->users()->attach($user);
            $message = $user->name . " given " . $level->name . " on " . $qualification->name;
        } else {
            $message = "User already has qualification";
        }
        return redirect('qualification/' . $qualification->id)->with('messages', $message);
    }

    public function removeUserLevel($levelId, $userId) {
        $level = Level::find($levelId);
        $user = User::find($userId);
        $level->users()->detach($user);
        return redirect('user/' . $user->id)->with('messages', 'Qualification removed');
    }
}
