<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Models\Prescription;
use Vinkla\Hashids\Facades\Hashids;
class ReportController extends Controller
{
    //
public function report(Request $request, $hashed_id)
{
    $userEmail = Auth::user()->email;

    $decoded = Hashids::decode($hashed_id);

    // ✅ Check if decode failed
    if (empty($decoded)) {
        return response()->json(['error' => 'Invalid appointment ID'], 400);
    }

    $patientId = $decoded[0];

    $appoint_id = Appointment::find($patientId);
    $doctor = Doctor::where('email', $userEmail)->first();

    if (!$appoint_id) {
        Log::error('Appointment not found', ['id' => $patientId]);
        return response()->json(['error' => 'Appointment not found'], 404);
    }

    $report = Prescription::where('appointment_id', $patientId)->get();

    return view('report', ['report' => $report]);
}
}
