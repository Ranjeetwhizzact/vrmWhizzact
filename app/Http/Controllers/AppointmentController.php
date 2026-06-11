<?php

namespace App\Http\Controllers;

use App\Helpers\ZoomHelper;
use App\Models\Appointment;
use App\Models\Division;
use App\Models\Doctor;
use App\Models\MeetingRating;
use App\Models\MeetingRecording;
use App\Models\Patient;
use App\Models\Report;
use App\Services\ZoomServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    protected $zoomService;

    public function index()
    {
        $divisions = Division::where('is_active', 1)->get();

        return view('schedule', compact('divisions'));
    }

    public function __construct(ZoomServices $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    // public function checkdoctor(Request $request)
    // {
    //     $selectDate = $request->date; // Format: YYYY-MM-DD
    //     $selectTime = $request->time; // Format: HH:MM

    //     // Convert date to weekday
    //     $weekday = strtolower(Carbon::parse($selectDate)->format('l'));

    //     // Fetch all doctors and filter based on availability
    //     $doctors = Doctor::all()->filter(function ($doctor) use ($weekday, $selectTime) {
    //         $availableDays = $doctor->available_days;

    //         if (!isset($availableDays[$weekday])) {

    //             return false;
    //         }

    //         $startTime = Carbon::parse($availableDays[$weekday]['start_time'])->format('H:i');
    //         $endTime = Carbon::parse($availableDays[$weekday]['end_time'])->format('H:i');

    //         // Check if requested time is within the available time range
    //         if ($selectTime < $startTime || $selectTime > $endTime) {

    //             return false;
    //         }

    //         return true;
    //     });

    //     // Filter out fully booked doctors
    //     $availableDoctors = $doctors->filter(function ($doctor) use ($selectDate, $selectTime) {
    //         $appointments = Appointment::where('doctor_id', $doctor->id)
    //             ->whereDate('start_time', $selectDate)
    //             ->get();

    //         foreach ($appointments as $appointment) {
    //             $startTime = Carbon::parse($appointment->start_time)->format('H:i');
    //             $endTime = Carbon::parse($appointment->end_time)->format('H:i');

    //             Log::info('Appointment details', [
    //                 'doctor_id' => $doctor->user_id,
    //                 'appointment_start' => $startTime,
    //                 'appointment_end' => $endTime,
    //                 'requested_time' => $selectTime
    //             ]);

    //             // Check if requested time falls within any existing appointment
    //             if ($selectTime >= $startTime && $selectTime < $endTime) {;
    //                 return false;
    //             }
    //         }

    //         return true;
    //     });

    //     $doctorList = $availableDoctors->map(function ($doctor) {
    //         return [
    //             'first_name' => $doctor->first_name,
    //             'last_name' => $doctor->last_name,
    //             'doctor_id' => $doctor->user_id,
    //         ];
    //     })->values(); // Ensures a clean JSON response

    //     // ❌ REMOVE THIS LINE (it stops execution)
    //     // dd($doctorList);

    //     return response()->json(['doctorList' => $doctorList], 200);
    // }

    public function checkdoctor(Request $request)
    {
        $selectDate = $request->date; // Format: YYYY-MM-DD
        $selectTime = $request->time; // Format: HH:MM

        // Convert date to weekday
        $weekday = strtolower(Carbon::parse($selectDate)->format('l'));

        // Fetch all doctors and filter based on availability
        $doctors = Doctor::all()->filter(function ($doctor) use ($weekday, $selectTime) {
            $availableDays = $doctor->available_days;

            if (

                ! isset($availableDays[$weekday]) ||

                empty($availableDays[$weekday]['start_time']) ||

                empty($availableDays[$weekday]['end_time'])

            ) {

                return false;
            }

            $startTime = Carbon::parse(

                $availableDays[$weekday]['start_time']

            )->format('H:i');

            $endTime = Carbon::parse(

                $availableDays[$weekday]['end_time']

            )->format('H:i');

            // Check if requested time is within the available time range
            if ($selectTime < $startTime || $selectTime > $endTime) {

                return false;
            }

            return true;
        });

        // Filter out fully booked doctors
        $availableDoctors = $doctors->filter(function ($doctor) use ($selectDate, $selectTime) {
            $appointments = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('start_time', $selectDate)
                ->get();

            foreach ($appointments as $appointment) {
                $startTime = Carbon::parse($appointment->start_time)->format('H:i');
                $endTime = Carbon::parse($appointment->end_time)->format('H:i');

                Log::info('Appointment details', [
                    'doctor_id' => $doctor->user_id,
                    'appointment_start' => $startTime,
                    'appointment_end' => $endTime,
                    'requested_time' => $selectTime,
                ]);

                // Check if requested time falls within any existing appointment
                if ($selectTime >= $startTime && $selectTime < $endTime) {
                    return false;
                }
            }

            return true;
        });

        $doctorList = $availableDoctors->map(function ($doctor) {
            return [
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
                'doctor_id' => $doctor->user_id,
            ];
        })->values(); // Ensures a clean JSON response

        // ❌ REMOVE THIS LINE (it stops execution)
        // dd($doctorList);

        return response()->json(['doctorList' => $doctorList], 200);
    }

    // public function scheduleCall(Request $request)
    // {
    //     Log::info('scheduleCall initiated', ['request' => $request->all()]);

    //     try {
    //         // Handle dynamic date format parsing
    //         if ($request->filled('date')) {
    //             $inputDate = $request->date;
    //             $formattedDate = null;

    //             // Accept YYYY-MM-DD or MM/DD/YYYY
    //             if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $inputDate)) {
    //                 $formattedDate = $inputDate;
    //             } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $inputDate)) {
    //                 try {
    //                     $formattedDate = Carbon::createFromFormat('m/d/Y', $inputDate)->format('Y-m-d');
    //                 } catch (\Exception $e) {
    //                     Log::error('Date format conversion failed', ['input_date' => $inputDate, 'exception' => $e->getMessage()]);
    //                     return back()->with('error', 'Invalid date format. Please use MM/DD/YYYY or YYYY-MM-DD.');
    //                 }
    //             } else {
    //                 Log::error('Unrecognized date format', ['input_date' => $inputDate]);
    //                 return back()->with('error', 'Invalid date format. Please use MM/DD/YYYY or YYYY-MM-DD.');
    //             }

    //             $request->merge(['date' => $formattedDate]);
    //         } else {
    //             return back()->with('error', 'Date is required.');
    //         }

    //         // Validate input
    //         try {
    //             $request->validate([
    //                 'client_id' => 'required|exists:patients,id',
    //                 'doctor_id' => 'required|exists:doctors,user_id', // FIXED LINE
    //                 'date' => 'required|date',
    //                 'time' => 'required',
    //             ]);
    //         } catch (\Illuminate\Validation\ValidationException $e) {
    //             Log::error('Validation failed', ['errors' => $e->errors()]);
    //             return back()->withErrors($e->errors());
    //         }

    //         $startTime = $request->date . ' ' . $request->time . ':00';
    //         $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' +30 minutes'));

    //         Log::info('Start and end time computed', ['start' => $startTime, 'end' => $endTime]);

    //         $doctor = Doctor::where('user_id', $request->doctor_id)->firstOrFail();
    //         $client = Patient::findOrFail($request->client_id);

    //         Log::info('Doctor and client fetched', ['doctor_id' => $doctor->user_id, 'client_id' => $client->id]);

    //         // Create Zoom meeting
    //         $zoomMeeting = $this->zoomService->createMeeting($startTime, $doctor->email, $client->email);
    //         Log::info('Zoom meeting created', ['zoomMeeting' => $zoomMeeting]);

    //         if (isset($zoomMeeting['error'])) {
    //             Log::error('Zoom meeting creation failed', ['error' => $zoomMeeting['error']]);
    //             return response()->json(['error' => $zoomMeeting['error']], 500);
    //         }

    //         // Create or update appointment
    //         $appointment = Appointment::where('client_id', $client->id)->first();
    //         $appointmentData = [
    //             'doctor_id' => $doctor->user_id,
    //             'start_time' => $startTime,
    //             'end_time' => $endTime,
    //             'zoom_meeting_id' => $zoomMeeting['id'],
    //             'zoom_join_url' => $zoomMeeting['join_url'],
    //             'zoom_start_url' => $zoomMeeting['start_url'],
    //             'zoom_passcode' => $zoomMeeting['password'],
    //             'appointment_type' => 'online',
    //             'status' => 'scheduled',
    //             'doctor_access_key' => Str::random(10),
    //             'created_by' => Auth::user()->email,
    //             'updated_by' => Auth::user()->email,
    //         ];

    //         if ($appointment) {
    //             Log::info('Updating existing appointment', ['appointment_id' => $appointment->id]);
    //             $appointment->update($appointmentData);
    //         } else {
    //             Log::info('Creating new appointment');
    //             $appointment = Appointment::create(array_merge($appointmentData, ['client_id' => $client->id]));
    //         }

    //         $client->status = 'scheduled';
    //         $client->save();
    //         Log::info('Client status updated');

    //         $honestdomain = env('APP_URL');
    //         $meetingLink = "{$honestdomain}/join-meeting/{$zoomMeeting['id']}";
    //         $mobile = ltrim($client->phone, '0');
    //         $assign_patient_name = $client->first_name . ' ' . $client->last_name;

    //         // Send Email
    //         $emailResponse = Http::withHeaders([
    //             'accept' => 'application/json',
    //             'authkey' => '400026Aum41tS2Xqb68664708P1',
    //             'content-type' => 'application/json',
    //         ])->post('https://control.msg91.com/api/v5/email/send', [
    //             'recipients' => [[
    //                 'to' => [[
    //                     'name' => $assign_patient_name,
    //                     'email' => $client->email,
    //                 ]],
    //                 'cc' => [[
    //                     'name' => 'Honest Health Care',
    //                     'email' => 'honesthealthcare3@gmail.com'
    //                 ]],
    //                 'variables' => [
    //                     'VAR1' => $assign_patient_name,
    //                     'VAR2' => $request->date,
    //                     'VAR3' => $request->time,
    //                     'VAR4' => $meetingLink,
    //                 ]
    //             ]],
    //             'from' => [
    //                 'name' => 'Honest Health Care',
    //                 'email' => 'honesthealthcare@email.whizzactsolutions.com'
    //             ],
    //             'domain' => 'email.whizzactsolutions.com',
    //             'reply_to' => [[
    //                 'email' => 'honesthealthcare3@gmail.com'
    //             ]],
    //             'attachments' => [],
    //             'template_id' => 'honest_availability_submitted_confirmation'
    //         ]);

    //         Log::info('Email response', ['response' => $emailResponse->body()]);

    //         // Send SMS
    //         $smsResponse = Http::withHeaders([
    //             'authkey' => '447742AMCfHYvVexw68079d89P1',
    //             'Content-Type' => 'application/json',
    //         ])->post('https://control.msg91.com/api/v5/flow', [
    //             'template_id' => '69e89908dfe7df40230da892',
    //             'short_url' => 1,
    //             'recipients' => [[
    //                 'mobiles' => "91{$mobile}",
    //                 'var1' => $assign_patient_name,
    //                 'var2' => $request->date,
    //                 'var3' => $request->time,
    //                 'var4' => $meetingLink,
    //             ]]
    //         ]);

    //         Log::info('SMS response', ['response' => $smsResponse->body()]);

    //         if ($emailResponse->successful() && $smsResponse->successful()) {
    //             Log::info('Both email and SMS sent successfully.');
    //             return back()->with('success', 'The video medical examination has been scheduled, and notifications were sent via SMS and email.');
    //         } else {
    //             Log::warning('Notification failed', [
    //                 'emailSuccess' => $emailResponse->successful(),
    //                 'smsSuccess' => $smsResponse->successful()
    //             ]);
    //             return back()->with('error', 'Appointment saved, but failed to send one or more notifications.');
    //         }
    //     } catch (\Throwable $e) {
    //         Log::error('Exception in scheduleCall', [
    //             'exception' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return back()->with('error', 'Something went wrong. Please try again later.');
    //     }
    // }

    public function scheduleCall(Request $request)
    {
        Log::info('scheduleCall initiated', [
            'request' => $request->all(),
        ]);

        try {

            /*
        |--------------------------------------------------------------------------
        | FORMAT DATE
        |--------------------------------------------------------------------------
        */

            if ($request->filled('date')) {

                $inputDate = $request->date;
                $formattedDate = null;

                // YYYY-MM-DD
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $inputDate)) {

                    $formattedDate = $inputDate;

                    // MM/DD/YYYY
                } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $inputDate)) {

                    try {

                        $formattedDate = Carbon::createFromFormat(
                            'm/d/Y',
                            $inputDate
                        )->format('Y-m-d');
                    } catch (\Exception $e) {

                        Log::error('Date format conversion failed', [
                            'input_date' => $inputDate,
                            'exception' => $e->getMessage(),
                        ]);

                        return back()->with(
                            'error',
                            'Invalid date format. Please use MM/DD/YYYY or YYYY-MM-DD.'
                        );
                    }
                } else {

                    return back()->with(
                        'error',
                        'Invalid date format. Please use MM/DD/YYYY or YYYY-MM-DD.'
                    );
                }

                $request->merge([
                    'date' => $formattedDate,
                ]);
            } else {

                return back()->with('error', 'Date is required.');
            }

            /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

            $request->validate([
                'client_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,user_id',
                'date' => 'required|date',
                'time' => 'required',
            ]);

            /*
        |--------------------------------------------------------------------------
        | TIMES
        |--------------------------------------------------------------------------
        */

            $startTime = Carbon::parse(
                $request->date.' '.$request->time
            );

            $endTime = (clone $startTime)->addMinutes(30);

            Log::info('Meeting times', [
                'start' => $startTime,
                'end' => $endTime,
            ]);

            /*
        |--------------------------------------------------------------------------
        | FETCH DOCTOR & CLIENT
        |--------------------------------------------------------------------------
        */

            $doctor = Doctor::where(
                'user_id',
                $request->doctor_id
            )->firstOrFail();

            $client = Patient::findOrFail(
                $request->client_id
            );

            /*
        |--------------------------------------------------------------------------
        | CREATE ZOOM MEETING
        |--------------------------------------------------------------------------
        */

            $zoomMeeting = $this->zoomService->createMeeting(
                $doctor,
                $startTime->toDateTimeString()
            );

            Log::info('Zoom meeting response', [
                'response' => $zoomMeeting,
            ]);

            if (
                ! $zoomMeeting ||
                isset($zoomMeeting['error'])
            ) {

                Log::error('Zoom creation failed', [
                    'response' => $zoomMeeting,
                ]);

                return back()->with(
                    'error',
                    'Unable to create Zoom meeting.'
                );
            }

            /*
        |--------------------------------------------------------------------------
        | ALWAYS CREATE NEW APPOINTMENT
        |--------------------------------------------------------------------------
        |
        | THIS IS THE FIX
        | DO NOT UPDATE EXISTING APPOINTMENT
        |
        */

            $appointment = Appointment::create([

                'client_id' => $client->id,

                'doctor_id' => $doctor->user_id,

                'start_time' => $startTime,

                'end_time' => $endTime,

                'zoom_meeting_id' => $zoomMeeting['id'] ?? null,

                'zoom_join_url' => $zoomMeeting['join_url'] ?? null,

                'zoom_start_url' => $zoomMeeting['start_url'] ?? null,

                'zoom_passcode' => $zoomMeeting['password'] ?? null,

                'appointment_type' => 'online',

                'status' => 'scheduled',

                'doctor_access_key' => Str::random(20),

                'created_by' => Auth::user()->email,

                'updated_by' => Auth::user()->email,
            ]);

            Log::info('Appointment created', [
                'appointment_id' => $appointment->id,
            ]);

            /*
        |--------------------------------------------------------------------------
        | UPDATE CLIENT STATUS
        |--------------------------------------------------------------------------
        */

            $client->status = 'scheduled';
            $client->save();

            /*
        |--------------------------------------------------------------------------
        | SECURE MEETING LINK
        |--------------------------------------------------------------------------
        |
        | USE APPOINTMENT ID
        | NOT ZOOM MEETING ID
        |
        */

            $appUrl = env('APP_URL');

            $meetingLink = "{$appUrl}/join-meeting/{$appointment->zoom_meeting_id}";

            /*
        |--------------------------------------------------------------------------
        | PATIENT INFO
        |--------------------------------------------------------------------------
        */

            $mobile = ltrim($client->phone, '0');

            $assignPatientName =
                $client->first_name.' '.$client->last_name;

            /*
        |--------------------------------------------------------------------------
        | SEND EMAIL
        |--------------------------------------------------------------------------
        */

            $emailResponse = Http::withHeaders([
                'accept' => 'application/json',
                'authkey' => '400026Aum41tS2Xqb68664708P1',
                'content-type' => 'application/json',
            ])->post(
                'https://control.msg91.com/api/v5/email/send',
                [
                    'recipients' => [[

                        'to' => [[
                            'name' => $assignPatientName,
                            'email' => $client->email,
                        ]],

                        'cc' => [[
                            'name' => 'Honest Health Care',
                            'email' => 'honesthealthcare3@gmail.com',
                        ]],

                        'variables' => [
                            'VAR1' => $assignPatientName,
                            'VAR2' => $request->date,
                            'VAR3' => $request->time,
                            'VAR4' => $meetingLink,
                        ],

                    ]],

                    'from' => [
                        'name' => 'Honest Health Care',
                        'email' => 'honesthealthcare@email.whizzactsolutions.com',
                    ],

                    'domain' => 'email.whizzactsolutions.com',

                    'reply_to' => [[
                        'email' => 'honesthealthcare3@gmail.com',
                    ]],

                    'attachments' => [],

                    'template_id' => 'honest_availability_submitted_confirmation',
                ]
            );

            Log::info('Email sent', [
                'response' => $emailResponse->body(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | SEND SMS
        |--------------------------------------------------------------------------
        */

            $smsResponse = Http::withHeaders([
                'authkey' => '447742AMCfHYvVexw68079d89P1',
                'Content-Type' => 'application/json',
            ])->post(
                'https://control.msg91.com/api/v5/flow',
                [
                    'template_id' => '69e89908dfe7df40230da892',

                    'short_url' => 1,

                    'recipients' => [[

                        'mobiles' => "91{$mobile}",

                        'var1' => $assignPatientName,

                        'var2' => $request->date,

                        'var3' => $request->time,

                        'var4' => $meetingLink,
                    ]],
                ]
            );

            Log::info('SMS sent', [
                'response' => $smsResponse->body(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

            if (
                $emailResponse->successful() &&
                $smsResponse->successful()
            ) {

                return back()->with(
                    'success',
                    'Video consultation scheduled successfully.'
                );
            }

            return back()->with(
                'warning',
                'Appointment scheduled but notification failed.'
            );
        } catch (\Throwable $e) {

            Log::error('scheduleCall exception', [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with(
                'error',
                'Something went wrong. Please try again later.'
            );
        }
    }

    // public function joinMeeting(Request $request, $appointmentId)
    // {
    //     $appointment = Appointment::where('zoom_meeting_id', $appointmentId)->firstOrFail();

    //     // Check if the user is a doctor or patient
    //     $isDoctor = ($request->has('doctor_access') && $request->doctor_access == $appointment->doctor_access_key);
    //     $userName = '';
    //     $userEmail = '';
    //     $branch = null;
    //     $patientId = $appointment->client_id;
    //     if ($isDoctor) {

    //         $doctor = Doctor::where('user_id', $appointment->doctor_id)->firstOrFail();

    //         $userName =  $doctor->first_name . " " . $doctor->last_name;
    //         $userEmail = $doctor->email;
    //     } else {
    //         $patient = Patient::findOrFail($appointment->client_id);
    //         $userName =  $patient->first_name . " " . $patient->last_name;
    //         $userEmail = $patient->email;
    //         $branch = $patient->branch ?? null;
    //     }

    //     // Initialize Zoom Helper
    //     $zoomHelper = new ZoomHelper();

    //     // Check if meeting exists before starting recording
    //     $meetingDetails = $this->zoomService->getMeetingDetails($appointment->zoom_meeting_id);
    //     if (!isset($meetingDetails->original['id'])) {
    //         return response()->json(['error' => 'Zoom meeting not found'], 404);
    //     }
    //     // dd($meetingDetails);

    //     // Start Zoom Auto-Recording
    //     // $this->zoomService->startZoomRecording($appointment->zoom_meeting_id);

    //     return view('zoomMeetingView', [
    //         'meetingId' => $appointment->zoom_meeting_id,
    //         'passCode' => $appointment->zoom_passcode,
    //         'signature' => $zoomHelper->generateZoomSignature($appointment->zoom_meeting_id, $isDoctor ? 1 : 0),
    //         'userName' => $userName,
    //         'userEmail' => $userEmail,
    //         'isDoctor' => $isDoctor,
    //         'patientId' => $patientId,
    //     ]);
    // }

    public function joinMeeting(Request $request, $appointmentId)
    {
        $appointment = Appointment::where('zoom_meeting_id', $appointmentId)->firstOrFail();

        $isDoctor = ($request->has('doctor_access') && $request->doctor_access == $appointment->doctor_access_key);
        $patient = Patient::find($appointment->client_id);
        Log::info('Patient Data', [
            'patient_found' => $patient ? true : false,
            'patient_id' => $appointment->client_id,
            'branch' => $patient->branch ?? null,
            'dob' => $patient->dob ?? null,
            'gender' => $patient->gender ?? null,
        ]);
        $branch = $patient->branch ?? null;
        $proposal_number = $patient->proposal_number ?? null;
        $phone = $patient->phone ?? null;
        $mer_type = $patient->mer_type ?? null;
        $full_name = $patient->full_name ?? null;
        $dob = $patient->dob ?? null;
        $gender = $patient->gender ?? null;
        $doctor = Doctor::where('user_id', $appointment->doctor_id)->first();
        $doctorsignature = null;
        $education = null;
        $reference = null;
        $sdkKey = $doctor->zoom_sdk_client_id ?? null;
        $sdkSecret = $doctor->zoom_sdk_client_secret ?? null;

        if ($isDoctor) {
            Log::info('Doctor Data', [
                'doctor_found' => $doctor ? true : false,
                'doctor_email' => $doctor->email ?? null,
                'examiner_signature' => $doctor->signature ?? null,
                'sdkKey' => $doctor->zoom_sdk_client_id ?? null,
                'sdkSecret' => $doctor->zoom_sdk_client_secret ?? null,
                'doctor_photo' => $doctor->image ?? null,
                'examiner_stamp' => $doctor->stamp ?? null,
            ]);
            $userName = $doctor->first_name.' '.$doctor->last_name;
            $userEmail = $doctor->email;
            $doctorsignature = $doctor->signature ?? null;
            $education = $doctor->education ?? null;
            $reference = $doctor->reference_number ?? null;
        } else {
            $userName = $patient->full_name ?? '';
            $userEmail = $patient->email ?? '';
        }
        $signature =
            ZoomHelper::generateZoomSignature(
                $appointment->zoom_meeting_id,
                $isDoctor ? 1 : 0,
                $sdkKey,
                $sdkSecret
            );

        return view('zoomMeetingView', [
            'appointment' => $appointment,
            'meetingId' => $appointment->zoom_meeting_id,
            'passCode' => $appointment->zoom_passcode,
            'signature' => $signature,
            'userName' => $userName,
            'userEmail' => $userEmail,
            'isDoctor' => $isDoctor,
            'patientId' => $appointment->client_id,
            'branch' => $branch,
            'proposal_number' => $proposal_number,
            'phone' => $phone,
            'mer_type' => $mer_type,
            'doctor_name' => $doctor
                ? ($doctor->first_name.' '.$doctor->last_name)
                : 'Doctor',
            'examiner_signature' => $doctorsignature,
            'doctor_qualification' => $education,
            'doctor_reg_no' => $reference,
            'doctor_photo' => $doctor->image ?? null,
            'examiner_stamp' => $doctor->stamp ?? null,
            'full_name' => $full_name,
            'dob' => $dob,
            'gender' => $gender,
            'sdkKey' => $sdkKey,
            'sdkSecret' => $sdkSecret,
        ]);
    }

    public function storeRating(Request $request)
    {
        Log::info('Meeting Rating Request Received', [
            'all_data' => $request->all(),
        ]);
        $request->validate([
            'appointment_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'rating.required' => 'Please leave a rating before ending the meeting.',
        ]);
        try {

            $rating = MeetingRating::create([
                'appointment_id' => $request->appointment_id,
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            Log::info('Meeting Rating Saved Successfully', [
                'saved_data' => $rating,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rating saved successfully',
            ]);
        } catch (\Exception $e) {

            Log::error('Meeting Rating Save Failed', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storereport(Request $request)
    {
        try {
            Log::info('Starting report submission', ['request_data' => $request->except(['_token', 'password'])]);

            // Validation
            $validated = $request->validate([
                'meeting_id' => 'nullable|string',
                'client_id' => 'nullable|string',
                'isDoctor' => 'nullable|boolean',
                'full_name' => 'nullable|string',
                'dob' => 'nullable|date',
                'age' => 'nullable|integer',
                'gender' => 'nullable|string',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'final_declaration' => 'sometimes|accepted',
            ]);

            // Single file uploads
            $fileFields = [
                'id_proof_screenshot',
                'consent_proof',
                'life_assured_signature',
                'q5c_documents',
                'q5b_documents',
                'declaration_proof',
                'examiner_signature',
                'examiner_stamp',
                'additional_documents',
            ];
            foreach ($fileFields as $field) {

                if ($request->hasFile($field)) {

                    $files = $request->file($field);

                    // If multiple files
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            $uploadedFiles[$field][] = $file->store('medical_reports/'.$field, 'public');
                        }
                    }
                    // If single file
                    else {
                        $uploadedFiles[$field] = $files->store('medical_reports/'.$field, 'public');
                    }
                }
            }

            // Multiple file uploads (JSON)
            $multipleFileFields = [
                'q5a_documents',
                'q6_documents',
                'q7_documents',
                'q8_documents',
                'q9_documents',
                'q10_documents',
                'q11_documents',
                'q12_documents',
                'q13_documents',
                'q14_documents',
                'q15_documents',
                'q16_documents',
                'q17_documents',
                'q18_documents',
                'q19_documents',
                'q20_documents',
                'gynae_documents',
            ];
            $multipleUploadedFiles = [];
            foreach ($multipleFileFields as $field) {
                if ($request->hasFile($field)) {
                    $files = [];
                    foreach ($request->file($field) as $file) {
                        $files[] = $file->store('medical_reports/'.$field, 'public');
                    }
                    $multipleUploadedFiles[$field] = json_encode($files);
                }
            }

            Log::info('LOCATION DATA', [
                'doctor_latitude' => $request->doctor_latitude,
                'doctor_longitude' => $request->doctor_longitude,
                'customer_latitude' => $request->customer_latitude,
                'customer_longitude' => $request->customer_longitude,
                'doctor_address' => $request->doctor_address,
                'customer_address' => $request->customer_address,
                'doctor_pincode' => $request->doctor_pincode,
                'customer_pincode' => $request->customer_pincode,
            ]);
            // Create report
            $report = new Report;
            $appointment = Appointment::where(
                'zoom_meeting_id',
                $request->meeting_id
            )->first();
            // Basic
            $report->meeting_id = $request->meeting_id;
            if ($appointment) {

                // Customer Location
                $report->customer_latitude = $appointment->customer_latitude;
                $report->customer_longitude = $appointment->customer_longitude;
                $report->customer_address = $appointment->customer_address;
                $report->customer_pincode = $appointment->customer_pincode;

                // Doctor Location
                $report->doctor_latitude = $appointment->doctor_latitude;
                $report->doctor_longitude = $appointment->doctor_longitude;
                $report->doctor_address = $appointment->doctor_address;
                $report->doctor_pincode = $appointment->doctor_pincode;
            }
            $report->client_id = $request->client_id;
            $report->isDoctor = ($request->has('doctor_access') || $request->input('isDoctor') == 1) ? 1 : 0;

            // Header
            $report->branch_code = $request->branch_code;
            $report->policy_no = $request->policy_no;
            $report->msp_code = $request->msp_code;
            $report->exam_datetime = $request->exam_datetime;
            $report->medical_diary_no = $request->medical_diary_no;
            $report->mobile_no = $request->mobile_no;
            $report->mer_done = $request->mer_done;
            $report->identity_proof_type = $request->identity_proof_type;
            $report->id_proof_no = $request->id_proof_no;
            $report->id_proof_screenshot = $uploadedFiles['id_proof_screenshot'] ?? null;

            // Consent
            $report->examiner_consent_name = $request->examiner_consent_name;
            $report->consent_method = $request->consent_method;
            $report->consent_proof = $uploadedFiles['consent_proof'] ?? null;

            // Personal
            $report->full_name = $request->full_name;
            $report->dob = $request->dob;
            $report->age = $request->age;
            $report->gender = $request->gender;
            $report->height = $request->height;
            $report->weight = $request->weight;
            $report->pulse = $request->pulse;
            $report->bp_systolic_1 = $request->bp_systolic_1;
            $report->bp_diastolic_1 = $request->bp_diastolic_1;
            $report->bp_systolic_2 = $request->bp_systolic_2;
            $report->bp_diastolic_2 = $request->bp_diastolic_2;

            // Question 5a
            $report->q5a_treatment = $request->q5a_treatment ?? 'NO';
            $report->q5a_date = $request->q5a_date;
            $report->q5a_nature = $request->q5a_nature;
            $report->q5a_medicine = $request->q5a_medicine;
            $report->q5a_impairment = $request->q5a_impairment;
            $report->q5a_unconscious = $request->q5a_unconscious;
            // $report->q5a_documents = $multipleUploadedFiles['q5a_documents'] ?? null;

            // Question 5b
            $report->q5b_surgery = $request->q5b_surgery ?? 'NO';
            $report->q5b_date = $request->q5b_date;
            $report->q5b_nature = $request->q5b_nature;
            $report->q5b_medicine = $request->q5b_medicine;
            $report->q5b_impairment = $request->q5b_impairment;
            $report->q5b_unconscious = $request->q5b_unconscious;
            // $report->q5b_documents = $multipleUploadedFiles['q5b_documents'] ?? null;

            // Question 5c
            $report->q5c_doctor_visit = $request->q5c_doctor_visit ?? 'NO';
            $report->q5c_date = $request->q5c_date;
            $report->q5c_nature = $request->q5c_nature;
            $report->q5c_medicine = $request->q5c_medicine;
            $report->q5c_impairment = $request->q5c_impairment;
            $report->q5c_unconscious = $request->q5c_unconscious;
            $report->q5c_documents = $uploadedFiles['q5c_documents'] ?? null;

            // Question 6
            $report->q6_diagnostic_tests = $request->q6_diagnostic_tests ?? 'NO';
            $report->q6_details = $request->q6_details;
            $report->q6_date = $request->q6_date;
            $report->q6_reason = $request->q6_reason;
            $report->q6_advised_by = $request->q6_advised_by;
            $report->q6_findings = $request->q6_findings;
            // $report->q6_documents = $multipleUploadedFiles['q6_documents'] ?? null;

            // Question 7
            $report->q7_covid = $request->q7_covid ?? 'NO';
            $report->q7_details = $request->q7_details;
            // $report->q7_documents = $multipleUploadedFiles['q7_documents'] ?? null;

            // Question 8
            $report->q8_hypertension_diabetes = $request->q8_hypertension_diabetes ?? 'NO';
            $report->q8_hypertension_diabetesdetails = $request->q8_hypertension_diabetesdetails;
            $report->q8b_details = $request->q8b_details;
            $report->q8c_medication = $request->q8c_medication;
            $report->q8e_complications = $request->q8e_complications;
            $report->q8f_endocrine = $request->q8f_endocrine;
            $report->q8g_weight_change = $request->q8g_weight_change;
            // $report->q8_documents = $multipleUploadedFiles['q8_documents'] ?? null;

            // Question 9
            $report->q9a_heart_history = $request->q9a_heart_history ?? 'NO';
            $report->q9a_heart = $request->q9a_heart;
            $report->q9b_cholesterol = $request->q9b_cholesterol;
            $report->q9c_medication = $request->q9c_medication;
            $report->q9d_surgery = $request->q9d_surgery;
            // $report->q9_documents = $multipleUploadedFiles['q9_documents'] ?? null;

            // Question 10
            $report->q10_kidney = $request->q10_kidney ?? 'NO';
            $report->q10_details = $request->q10_details;
            // $report->q10_documents = $multipleUploadedFiles['q10_documents'] ?? null;

            // Question 11
            $report->q11_liver_respiratory = $request->q11_liver_respiratory ?? 'NO';
            $report->q11_details = $request->q11_details;
            // $report->q11_documents = $multipleUploadedFiles['q11_documents'] ?? null;

            // Question 12
            $report->q12_blood_disorders = $request->q12_blood_disorders ?? 'NO';
            $report->q12_details = $request->q12_details;
            // $report->q12_documents = $multipleUploadedFiles['q12_documents'] ?? null;

            // Question 13
            $report->q13_cancer = $request->q13_cancer ?? 'NO';
            $report->q13_details = $request->q13_details;
            // $report->q13_documents = $multipleUploadedFiles['q13_documents'] ?? null;

            // Question 14
            $report->q14_neurological = $request->q14_neurological ?? 'NO';
            $report->q14_details = $request->q14_details;
            // $report->q14_documents = $multipleUploadedFiles['q14_documents'] ?? null;

            // Question 15
            $report->q15_physical_impairment = $request->q15_physical_impairment ?? 'NO';
            $report->q15_details = $request->q15_details;
            // $report->q15_documents = $multipleUploadedFiles['q15_documents'] ?? null;

            // Question 16
            $report->q16_digestive = $request->q16_digestive ?? 'NO';
            $report->q16_details = $request->q16_details;
            // $report->q16_documents = $multipleUploadedFiles['q16_documents'] ?? null;

            // Question 17
            $report->q17a_mental = $request->q17a_mental ?? 'NO';
            $report->q17a_details = $request->q17a_details;
            // $report->q17_documents = $multipleUploadedFiles['q17_documents'] ?? null;

            // Question 18
            $report->q18_ent = $request->q18_ent ?? 'NO';
            $report->q18_details = $request->q18_details;
            // $report->q18_documents = $multipleUploadedFiles['q18_documents'] ?? null;

            // Question 19
            $report->q19_hiv_sti = $request->q19_hiv_sti ?? 'NO';
            $report->q19_details = $request->q19_details;
            // $report->q19_documents = $multipleUploadedFiles['q19_documents'] ?? null;

            // Question 20
            $report->q20_other = $request->q20_other ?? 'NO';
            $report->q20a_details = $request->q20a_details;
            // $report->q20_documents = $multipleUploadedFiles['q20_documents'] ?? null;

            // Female
            $report->pregnancy_status = $request->pregnancy_status;
            $report->pregnancy_complications = $request->pregnancy_complications;
            $report->gynecological_issues = $request->gynecological_issues;
            $report->gynae_documents = $multipleUploadedFiles['gynae_documents'] ?? null;

            // Medical Examiner Assessment
            $report->appears_healthy = $request->appears_healthy;
            $report->healthy_notes = $request->healthy_notes;
            $report->place = $request->place;
            $report->exam_date = $request->exam_date;

            // Declaration
            $report->declaration_name = $request->declaration_name;
            $report->life_assured_signature = $uploadedFiles['life_assured_signature'] ?? null;
            $report->declaration_proof = $uploadedFiles['declaration_proof'] ?? null;
            $report->certificate_date = $request->certificate_date;
            $report->examiner_place = $request->examiner_place;
            $report->examiner_date = $request->examiner_date;
            // $report->examiner_signature = $uploadedFiles['examiner_signature'] ?? null;
            if (! empty($request->examiner_signature)) {

                // Original file path
                $oldPath = public_path($request->examiner_signature);

                // Check file exists
                if (file_exists($oldPath)) {

                    // Create folder if not exists
                    if (! file_exists(public_path('medical_reports/examiner_signature'))) {

                        mkdir(public_path('medical_reports/examiner_signature'), 0777, true);
                    }

                    // Generate filename
                    $filename = time().'_'.basename($oldPath);

                    // New path
                    $newPath = 'medical_reports/examiner_signature/'.$filename;

                    // Copy file
                    copy($oldPath, public_path($newPath));

                    // Save in database
                    $report->examiner_signature = $newPath;
                }
            }

            $report->doctor_name = $request->doctor_name;
            $report->doctor_qualification = $request->doctor_qualification;
            $report->doctor_registration_number = $request->doctor_registration_number;

            if (! empty($request->old_doctor_photo)) {

                // Original file path
                $oldPath = public_path($request->old_doctor_photo);

                // Check file exists
                if (file_exists($oldPath)) {

                    // Create folder if not exists
                    if (! file_exists(public_path('medical_reports/doctor_photo'))) {

                        mkdir(public_path('medical_reports/doctor_photo'), 0777, true);
                    }

                    // Generate filename
                    $filename = time().'_'.basename($oldPath);

                    // New path
                    $newPath = 'medical_reports/doctor_photo/'.$filename;

                    // Copy file
                    copy($oldPath, public_path($newPath));

                    // Save in database
                    $report->doctor_photo = $newPath;
                }
            }

            if (! empty($request->examiner_stamp)) {
                $oldPath = public_path($request->examiner_stamp);
                if (file_exists($oldPath)) {
                    if (! file_exists(public_path('medical_reports/examiner_stamp'))) {
                        mkdir(public_path('medical_reports/examiner_stamp'), 0777, true);
                    }
                    $filename = time().'_'.basename($oldPath);
                    $newPath = 'medical_reports/examiner_stamp/'.$filename;
                    copy($oldPath, public_path($newPath));
                    $report->examiner_stamp = $newPath;
                }
            }

            $report->additional_documents = $uploadedFiles['additional_documents'] ?? null;

            $report->final_declaration = $request->has('final_declaration') ? 1 : 0;
            $report->created_by = Auth::id() ?? 1;
            $report->updated_by = Auth::id() ?? 1;
            // Save
            $report->save();

            Log::info('Report saved successfully', ['report_id' => $report->id]);

            // Generate PDF
            try {

                $pdf = $this->generateMedicalReportPDF($report);

                $pdfFileName =
                    'medical_report_'.$report->id.'_'.date('Ymd_His').'.pdf';

                // Create reports folder if not exists
                if (! file_exists(public_path('reports'))) {

                    mkdir(public_path('reports'), 0777, true);
                }

                // Save PDF
                $pdf->save(public_path('reports/'.$pdfFileName));

                // Generate URL
                $pdfUrl = url('reports/'.$pdfFileName);

                Log::info('PDF generated successfully', [
                    'pdf_path' => public_path('reports/'.$pdfFileName),
                    'pdf_url' => $pdfUrl,
                ]);

                return response()->json([
                    'success' => true,
                    'pdf_url' => $pdfUrl,
                ]);
            } catch (\Exception $pdfError) {
                Log::error('PDF generation failed: '.$pdfError->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Report saved successfully, but PDF could not be generated: '.$pdfError->getMessage(),
                    'report_id' => $report->id,
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error saving medical report: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save report: '.$e->getMessage(),
            ], 500);
        }
    }

    public function saveMeetingLocation(Request $request)
    {
        Log::info('Location Request', $request->all());

        $appointment = Appointment::where(
            'zoom_meeting_id',
            $request->meeting_id
        )->first();

        if (! $appointment) {

            return response()->json([
                'success' => false,
                'message' => 'Appointment not found',
            ], 404);
        }

        if (filter_var($request->is_doctor, FILTER_VALIDATE_BOOLEAN)) {

            $appointment->doctor_latitude = $request->latitude;
            $appointment->doctor_longitude = $request->longitude;
            $appointment->doctor_address = $request->address;
            $appointment->doctor_pincode = $request->pincode;

        } else {

            $appointment->customer_latitude = $request->latitude;
            $appointment->customer_longitude = $request->longitude;
            $appointment->customer_address = $request->address;
            $appointment->customer_pincode = $request->pincode;
        }

        $appointment->save();

        return response()->json([
            'success' => true,
            'address' => $request->address,
            'pincode' => $request->pincode,
        ]);
    }

    private function getLocationDetails($latitude, $longitude)
    {
        $response = Http::withHeaders([
            'User-Agent' => 'YourAppName/1.0',
        ])->get('https://nominatim.openstreetmap.org/reverse', [
            'lat' => $latitude,
            'lon' => $longitude,
            'format' => 'jsonv2',
            'addressdetails' => 1,
        ]);

        Log::info('Nominatim Response', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return [
            'address' => $response->json('display_name'),
            'pincode' => $response->json('address.postcode'),
        ];
    }

    public function generateMedicalReportPDF($report)
    {
        $data = [
            'report' => $report,
            'generated_date' => now()->format('d/m/Y H:i:s'),
            'bmi' => $report->bmi,
            'bmi_category' => $report->bmi_category,
            'positive_answers' => $report->getPositiveAnswers(),
        ];

        $pdf = Pdf::loadView('pdf.medical_report', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    public function getBmiAttribute()
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;

            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }

        return null;
    }

    public function getBmiCategoryAttribute()
    {
        $bmi = $this->bmi;
        if (! $bmi) {
            return 'Unknown';
        }
        if ($bmi < 18.5) {
            return 'Underweight';
        }
        if ($bmi < 25) {
            return 'Normal weight';
        }
        if ($bmi < 30) {
            return 'Overweight';
        }

        return 'Obese';
    }

    public function getPositiveAnswers()
    {
        $positive = [];
        if ($this->q5a_treatment === 'YES') {
            $positive['q5a'] = ['question' => 'Treatment/Medication', 'details' => $this->q5a_nature];
        }

        // Add other conditions as needed
        return $positive;
    }

    public function getRecording(int $meeting_id)
    {
        $appointment = Appointment::where('zoom_meeting_id', $meeting_id)
            ->orWhere('id', $meeting_id)
            ->first();

        if (! $appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found.',
            ], 404);
        }

        $doctor = Doctor::where('user_id', $appointment->doctor_id)->first();

        if (! $doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found for this appointment.',
            ], 404);
        }

        $res = $this->zoomService->getMeetingRecordings(
            $doctor,
            $appointment->zoom_meeting_id ?? $meeting_id
        );

        return response()->json($res);
    }

    public function uploadRecording(Request $request)
    {
        try {
            Log::info('Recording upload started');
            $request->validate([
                'recording' => 'required|file',
                'meeting_id' => 'required',
            ]);
            if (! file_exists(public_path('recordings'))) {
                mkdir(public_path('recordings'), 0777, true);
            }
            $fileName =
                'meeting_'.
                $request->meeting_id.
                '_'.
                time().
                '.webm';
            $request->file('recording')
                ->move(public_path('recordings'), $fileName);
            $path = 'recordings/'.$fileName;
            $fileUrl = asset($path);
            Log::info('Recording uploaded', [
                'path' => $path,
            ]);
            // Save into database
            $recording = MeetingRecording::create([
                'meeting_id' => $request->meeting_id,
                'file_name' => $fileName,
                'file_path' => $path,
                'file_url' => $fileUrl,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recording uploaded successfully',
                'path' => $fileUrl,
                'data' => $recording,
            ]);
        } catch (\Exception $e) {
            Log::error('Recording upload failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
