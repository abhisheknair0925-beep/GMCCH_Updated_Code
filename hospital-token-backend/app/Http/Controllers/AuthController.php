<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\DoctorLoginRequest;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * User Login (Using CRNO)
     */
    public function userLogin(UserLoginRequest $request)
    {
        $user = User::where('crno', $request->crno)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid CRNO. Patient not found.'
            ], 401);
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 200);
    }

    /**
     * Doctor Login (Using username + password)
     */
    public function doctorLogin(DoctorLoginRequest $request)
    {
        $doctor = Doctor::where('username', $request->username)->first();

        if (!$doctor || !Hash::check($request->password, $doctor->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password.'
            ], 401);
        }

        $token = $doctor->createToken('doctor-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Doctor logged in successfully',
            'data' => [
                'doctor' => $doctor,
                'token' => $token
            ]
        ], 200);
    }
}
