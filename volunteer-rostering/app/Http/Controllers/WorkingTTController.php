<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Models\WorkingTT;
use App\Models\Qualification;

class WorkingTTController extends Controller
{
    public function list() {
        return view('workingTT.list', ['workingTTs' => WorkingTT::all()]);
    }

    public function show($workingTTid) {
        $workingTT = WorkingTT::find($workingTTid);
        $qualifications = Qualification::all();
        return view('workingTT.view', [
            'workingTT' => $workingTT,
            'qualifications' => $qualifications,
        ]);
    }

    public function pdf($workingTTid) {
        $workingTT = WorkingTT::find($workingTTid);

        $fileLoc = storage_path() . '/app/' . $workingTT->pdf;
        return response()->file($fileLoc);
    }

    public function create() {
        return view('workingTT.create');
    }

    public function store() {
        $workingTTattr = request()->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'max:255',
            'pdf' => 'required|file|mimes:pdf'
        ]);

        $file = $workingTTattr['pdf']->store('workingTTs');
        $workingTTattr['pdf'] = $file;

        $workingTT = WorkingTT::create($workingTTattr);
        return redirect('/workingTT/' . $workingTT->id)->with('messages', 'Working Timetable created');
    }

    public function edit($workingTTid) {
        $workingTT = WorkingTT::find($workingTTid);

        return view('workingTT.edit', [
            'workingTT' => $workingTT,
        ]);
    }

    public function update($workingTTid) {
        $workingTT = WorkingTT::find($workingTTid);

        $formData = request()->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'max:255',
            'pdf' => 'file'
        ]);
        if (isset($formData['pdf'])) {
            $file = $formData['pdf']->store('workingTTs');
            $formData['pdf'] = $file;
        }

        $workingTT->update($formData);

        return redirect('workingTT/' . $workingTT->id)->with('messages', 'Working Timetable updated');
    }

    public function delete($workingTTid) {
        $workingTT = WorkingTT::find($workingTTid);
        $workingTT->delete();
        return redirect('workingTT/list')->with('messages', 'Working timetable deleted');
    }

    public function addQualification($workingTTid) {
        $workingTT = WorkingTT::find($workingTTid);
        $formData = request()->validate([
            'qualification' => 'required|exists:qualifications,id',
            'add_included_qualifications' => '',
        ]);
        $qualification = Qualification::find($formData['qualification']);
        $qualifications = [];
        if (
            array_key_exists('add_included_qualifications', $formData)
            and $formData['add_included_qualifications']
        ) {
            $curQual = $qualification;
            array_push($qualifications, $curQual);
            while ($curQual->includedQualification() != null) {
                $curQual = $curQual->includedQualification();
                array_push($qualifications, $curQual);
            }
        } else {
            $qualifications = [$qualification];
        }
        foreach($qualifications as $qual) {
            if ($workingTT->qualifications()->get()->doesntContain($qual)) {
                $workingTT->qualifications()->attach($qual);
            }
        }
        return redirect('workingTT/' . $workingTT->id)->with('messages', 'Qualification added to working timetable successfully');
    }

    public function removeQualification($workingTTid, $qualificationId) {
        $workingTT = WorkingTT::find($workingTTid);
        $qualification = Qualification::find($qualificationId);
        $workingTT->qualifications()->detach($qualification);
        return redirect('workingTT/' . $workingTT->id)->with('messages', 'Qualification removed');
    }
}
