<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HospitalDoctorController extends Controller
{
    private function checkAccess(Request $request)
    {
        if (!$request->user() instanceof Hospital) {
            abort(403, 'Unauthorized. Admin access only.');
        }
    }

    public function store(Request $request)
    {
        $this->checkAccess($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'regno' => 'required|string|unique:doctors,regno',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('doctors', 'public');
        }

        $doctor = Doctor::create([
            'name' => $request->name,
            'qualification' => $request->qualification,
            'username' => $request->regno, // Using regno as username by default
            'password' => Hash::make($request->password),
            'department' => $request->department,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'regno' => $request->regno,
            'photo' => $photoPath,
        ]);

        // Assign to unit
        $unit = Unit::find($request->unit_id);
        $unit->doctor_id = $doctor->id;
        $unit->save();

        return response()->json([
            'success' => true,
            'message' => 'Doctor added successfully',
            'data'    => array_merge($doctor->toArray(), [
                // Return full URL so frontend can use it directly as <img src="..."> 
                'photo_url' => $doctor->photo ? Storage::disk('public')->url($doctor->photo) : null,
            ])
        ], 201);
    }
}
