<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        return response()->json(
            Patient::all()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone'         => 'nullable|string|regex:/^0[7-9][0-9]{8}$/',
            'id_number'    => 'required|string|unique:patients,id_number',
            'gender'        => 'required|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'nhif_number'   => 'nullable|string',
            'ward'          => 'required|string',
            'is_admitted'   => 'sometimes|boolean',
        ]);

        $patient = Patient::create($data);

        return response()->json($patient, 201);
    }

    public function show(Patient $patient)
    {
        return response()->json($patient->load('invoices'));
    }
}