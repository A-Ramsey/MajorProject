<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function edit() {
        $company = Company::firstOrCreate([
            'id' => 1,
        ]);
        return view('company.edit', [
            'company' => $company,
        ]);
    }

    public function update() {
        $company = Company::firstOrCreate([
            'id' => 1,
        ]);

        $formData = request()->validate([
            'name' => 'required|max:255',
            'primary_colour' => 'required',
            'secondary_colour' => 'required',
        ]);

        $company->update($formData);

        return redirect('company/edit')->with('messages', 'Company details updated');
    }
}
