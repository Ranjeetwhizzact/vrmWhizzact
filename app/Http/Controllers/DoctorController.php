<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\PatientReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Vinkla\Hashids\Facades\Hashids;

class DoctorController extends Controller
{
    public function createdoctor(Request $request)
    {
        return view('add-doctor');
    }

    public function searchuser(Request $request)
    {
        $term = $request->input('term');
        $users = User::where('email', 'LIKE', "%{$term}%")->get();

        return response()->json($users);
    }

    public function doctor(Request $request)
    {
        $doctors = Doctor::orderBy('id', 'desc')->paginate(10);

        Log::info('Doctors fetched count:', ['count' => $doctors->count()]);

        $doctors->transform(function ($doctor) {

            Log::info('Doctor row:', [
                'id' => $doctor->id,
                'email' => $doctor->email,
            ]);

            $doctor->hashed_id = Hashids::encode($doctor->id);

            $doctor->available_days = is_string($doctor->available_days)
                ? json_decode($doctor->available_days, true)
                : ($doctor->available_days ?? []);

            return $doctor;
        });

        return view('doctors', compact('doctors'));
    }

    public function editdoctor($hashed_id)
    {
        $decoded = Hashids::decode($hashed_id);
        $doctorId = $decoded[0];
        $doctor = Doctor::find($doctorId);
        $user_id = $doctor->user_id;
        $user = User::find($doctor->user_id);
        // $decryptedPassword = Crypt::decrypt($user->password);

        if (! $doctor) {
            return redirect()->back()->with('error', 'Doctor not found!');
        }
        $doctor->available_days = $doctor->available_days ?? [];

        // dd($doctor);
        // return $doctor;
        return view('add-doctor', ['doctor' => $doctor, 'user' => $user]);
    }

    public function deletedoctor($hashed_id)
    {
        $decoded = Hashids::decode($hashed_id);
        $doctorId = $decoded[0];
        $doctor = Doctor::find($doctorId);
        $doctor->delete();

        return back()->with('success', 'Doctor is deleted Sucessfully');
    }

    public function storedoctor(Request $request)
    {
        Log::info('Doctor Store Request Data:', $request->all());
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:doctors,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'gender' => 'required|string',
            'education' => 'required|string|max:255',
            'available_days' => 'required|array',
            'identity_proof' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:500', // Max 5MB
            'license' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:500',      // Max 5MB
            // Image validation
            'image' => 'required|image|mimes:jpeg,png,jpg|max:200',      // Max 2MB
            'signature' => 'required|image|mimes:png,jpg,jpeg|max:100',  // Max 1MB,  // Max 1MB
            'stamp' => 'nullable|image|mimes:png,jpg,jpeg|max:200',
        ], [
            'email.unique' => 'This email is already registered with another doctor.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'first_name.required' => 'The first name field is required.',
            'last_name.required' => 'The last name field is required.',
            'phone.required' => 'The phone number field is required.',
            'gender.required' => 'The gender field is required.',
            'education.required' => 'The education field is required.',
            'available_days.required' => 'Please select available days.',
            'image.image' => 'The profile image must be an image file.',
            'image.mimes' => 'Profile image must be a file of type: jpeg, jpg, png.',
            'image.max' => 'Profile image size must not exceed 200 KB.',
            'signature.image' => 'The signature must be an image file.',
            'signature.mimes' => 'Signature must be a file of type: png, jpg, jpeg.',
            'signature.max' => 'Signature size must not exceed 100 KB.',
            'stamp.image' => 'The stamp must be an image file.',
            'stamp.mimes' => 'Stamp must be a file of type: png, jpg, jpeg.',
            'stamp.max' => 'Stamp size must not exceed 200 KB.',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            Log::error('Doctor Store Validation Failed:', $validator->errors()->toArray());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            // Create a new user
            $user = new User;
            $user->name = $request->first_name.' '.$request->last_name;
            $user->email = $request->username;
            $user->password = Hash::make($request->password); // Default password if not provided
            $user->role = 'doctor';
            $user->save();

            // Create a new doctor record
            $doctor = new Doctor;
            $doctor->user_id = $user->id;
            $doctor->email = $request->email;
            $doctor->first_name = $request->first_name;
            $doctor->last_name = $request->last_name;
            $doctor->phone = $request->phone;
            $doctor->gender = $request->gender;
            $doctor->reference_number = $request->reference_number;
            $doctor->education = $request->education;
            $doctor->preferred_language =
                implode(
                    ',',
                    $request->preferred_language ?? []
                );

            foreach ($request->available_days as $day => $timing) {

                if (
                    empty($timing['start_time']) ||
                    empty($timing['end_time'])
                ) {
                    continue;
                }

                $availableDays[$day] = [

                    'start_time' => $timing['start_time'],

                    'end_time' => $timing['end_time'],
                ];
            }

            $doctor->available_days = $availableDays;
            $doctor->created_by = Auth::user()->email;
            $doctor->updated_by = Auth::user()->email;
            $doctor->is_active = 'active';

            if ($request->hasFile('image')) {
                $fileName = time().'_'.$request->file('image')->getClientOriginalName();
                $destinationPath = public_path('/doctors/profile/');
                $request->file('image')->move($destinationPath, $fileName);
                $doctor->image = '/doctors/profile/'.$fileName;
            }
            if ($request->hasFile('signature')) {
                $fileName = time().'_'.$request->file('signature')->getClientOriginalName();
                $destinationPath = public_path('/doctors/signature/');
                $request->file('signature')->move($destinationPath, $fileName);
                $doctor->signature = '/doctors/signature/'.$fileName;
            }
            if ($request->hasFile('degree')) {
                $fileName = time().'_'.$request->file('degree')->getClientOriginalName();
                $destinationPath = public_path('/doctors/degree/');
                $request->file('degree')->move($destinationPath, $fileName);
                $doctor->degree = '/doctors/degree/'.$fileName;
            }
            if ($request->hasFile('identity_proof')) {
                $fileName = time().'_'.$request->file('identity_proof')->getClientOriginalName();
                $destinationPath = public_path('/doctors/identity_proof/');
                $request->file('identity_proof')->move($destinationPath, $fileName);
                $doctor->identity_proof = '/doctors/identity_proof/'.$fileName;
            }
            if ($request->hasFile('stamp')) {
                $fileName = time().'_'.$request->file('stamp')->getClientOriginalName();
                $destinationPath = public_path('/doctors/stamp/');
                $request->file('stamp')->move($destinationPath, $fileName);
                $doctor->stamp = '/doctors/stamp/'.$fileName;
            }
            if ($request->hasFile('license')) {
                $fileName = time().'_'.$request->file('license')->getClientOriginalName();
                $destinationPath = public_path('/doctors/license/');
                $request->file('license')->move($destinationPath, $fileName);
                $doctor->license = '/doctors/license/'.$fileName;
            }

            $doctor->save();
            Log::info('Doctor created successfully', ['doctor_id' => $doctor->id]);
            DB::commit();

            return redirect()->back()->with('success', 'Doctor created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Doctor Store Exception:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            // Return error message to the view
            return redirect()->back()->withErrors(['error' => 'An error occurred: '.$e->getMessage()])->withInput();
        }
    }

    public function doctorprofile($encrypted_id)
    {
        try {
            $doctorId = Crypt::decrypt($encrypted_id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid or tampered ID!');
        }

        $doctor = Doctor::find($doctorId);

        if (! $doctor) {
            return redirect()->back()->with('error', 'Doctor not found!');
        }

        // Decode available_days just like you did in the doctor() function
        $doctor->available_days = is_string($doctor->available_days)
            ? json_decode($doctor->available_days, true)
            : ($doctor->available_days ?? []);

        return view('doctorprofile', compact('doctor'));
    }

    // public function updatedoctor(Request $request, $id)
    // {
    //     // Validate input
    //     $request->validate([
    //         'email' => [
    //             'required',
    //             'email',
    //             Rule::unique('doctors', 'email')->ignore($id), // Allow the same email for the current doctor
    //         ],
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'phone' => 'required|string|max:15',
    //         'gender' => 'required|string',
    //         'education' => 'required|string|max:255',
    //         'available_days' => 'required|array',
    //         // file validation
    //         'license' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
    //         'identity_proof' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
    //         'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
    //     ], [
    //         'email.unique' => 'This email is already registered with another doctor.',
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         $doctor = Doctor::findOrFail($id);
    //         $user = User::findOrFail($doctor->user_id);

    //         // Update User
    //         $user->name = $request->first_name.' '.$request->last_name;
    //         $user->email = $request->username;

    //         if ($request->password) {
    //             $user->password = Hash::make($request->password);
    //         }

    //         $user->save();

    //         // Update Doctor
    //         $doctor->email = $request->email;
    //         $doctor->first_name = $request->first_name;
    //         $doctor->last_name = $request->last_name;
    //         $doctor->phone = $request->phone;
    //         $doctor->gender = $request->gender;
    //         $doctor->education = $request->education;
    //         $doctor->reference_number = $request->reference_number;
    //         $doctor->available_days = json_encode($request->available_days);
    //         $doctor->updated_by = Auth::user()->email;
    //         if ($request->hasFile('license')) {

    //             // delete old file if exists
    //             if ($doctor->license && file_exists(public_path($doctor->license))) {
    //                 unlink(public_path($doctor->license));
    //             }

    //             $fileName = time().'_'.$request->file('license')->getClientOriginalName();
    //             $request->file('license')->move(public_path('/doctors/license/'), $fileName);

    //             $doctor->license = '/doctors/license/'.$fileName;

    //             Log::info('License updated', ['doctor_id' => $doctor->id]);
    //         }

    //         // ✅ HANDLE IDENTITY PROOF
    //         if ($request->hasFile('identity_proof')) {

    //             // delete old file if exists
    //             if ($doctor->identity_proof && file_exists(public_path($doctor->identity_proof))) {
    //                 unlink(public_path($doctor->identity_proof));
    //             }

    //             $fileName = time().'_'.$request->file('identity_proof')->getClientOriginalName();
    //             $request->file('identity_proof')->move(public_path('/doctors/identity_proof/'), $fileName);

    //             $doctor->identity_proof = '/doctors/identity_proof/'.$fileName;

    //             Log::info('Identity proof updated', ['doctor_id' => $doctor->id]);
    //         }

    //         if ($request->hasFile('image')) {

    //             // delete old image
    //             if ($doctor->image && file_exists(public_path($doctor->image))) {
    //                 unlink(public_path($doctor->image));
    //             }

    //             $fileName = time().'_'.$request->file('image')->getClientOriginalName();

    //             $destinationPath = public_path('/doctors/profile/');

    //             // create folder if not exists
    //             if (! file_exists($destinationPath)) {
    //                 mkdir($destinationPath, 0777, true);
    //             }

    //             $request->file('image')->move($destinationPath, $fileName);

    //             $doctor->image = '/doctors/profile/'.$fileName;

    //             Log::info('Doctor profile image updated', [
    //                 'doctor_id' => $doctor->id,
    //             ]);
    //         }

    //         // ✅ HANDLE SIGNATURE
    //         if ($request->hasFile('signature')) {

    //             // delete old signature
    //             if ($doctor->signature && file_exists(public_path($doctor->signature))) {
    //                 unlink(public_path($doctor->signature));
    //             }

    //             $fileName = time().'_'.$request->file('signature')->getClientOriginalName();

    //             $destinationPath = public_path('/doctors/signature/');

    //             // create folder if not exists
    //             if (! file_exists($destinationPath)) {
    //                 mkdir($destinationPath, 0777, true);
    //             }

    //             $request->file('signature')->move($destinationPath, $fileName);

    //             $doctor->signature = '/doctors/signature/'.$fileName;

    //             Log::info('Doctor signature updated', [
    //                 'doctor_id' => $doctor->id,
    //             ]);
    //         }
    //         $doctor->save();

    //         DB::commit();

    //         Log::info('Doctor updated successfully', ['doctor_id' => $doctor->id]);

    //         return redirect()->back()->with('success', 'Doctor updated successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         Log::error('Error updating doctor', ['doctor_id' => $doctor->id, 'message' => $e->getMessage()]);

    //         return redirect()->back()->with('error', 'An error occurred: '.$e->getMessage());
    //     }
    // }

    public function updatedoctor(Request $request, $id)
    {
        Log::info('Doctor update started', [
            'doctor_id' => $id,
            'request_data' => $request->except([
                'password',
            ]),
            'files' => [
                'license' => $request->hasFile('license'),
                'identity_proof' => $request->hasFile('identity_proof'),
                'image' => $request->hasFile('image'),
                'signature' => $request->hasFile('signature'),
                'stamp' => $request->hasFile('stamp'),
                'degree' => $request->hasFile('degree'),
            ],
        ]);

        // Validate input
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('doctors', 'email')->ignore($id),
            ],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'gender' => 'required|string',
            'education' => 'required|string|max:255',
            'available_days' => 'nullable|array',

            'license' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'identity_proof' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'degree' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'stamp' => 'nullable|image|mimes:png,jpg,jpeg|max:200',
        ], [
            'email.unique' => 'This email is already registered with another doctor.',
        ]);

        DB::beginTransaction();

        try {

            $doctor = Doctor::findOrFail($id);

            Log::info('Doctor found', [
                'doctor_id' => $doctor->id,
            ]);

            $user = User::findOrFail($doctor->user_id);

            // Update User
            $user->name = $request->first_name.' '.$request->last_name;
            $user->email = $request->username;

            if ($request->password) {

                $user->password = Hash::make($request->password);

                Log::info('Password updated');
            }

            $user->save();

            Log::info('User updated successfully');

            // Update Doctor
            $doctor->email = $request->email;
            $doctor->first_name = $request->first_name;
            $doctor->last_name = $request->last_name;
            $doctor->phone = $request->phone;
            $doctor->gender = $request->gender;
            $doctor->education = $request->education;
            $doctor->reference_number = $request->reference_number;
            $doctor->updated_by = Auth::user()->email;
            // $doctor->available_days = $request->available_days ? json_encode($request->available_days) : null;
            if ($request->has('available_days')) {

                $existingSchedule = $doctor->available_days ?? [];
                $newSchedule = $request->available_days ?? [];
                $mergedSchedule = array_merge(
                    $existingSchedule,
                    $newSchedule
                );
                $doctor->available_days = $mergedSchedule;
            }

            /*
            |--------------------------------------------------------------------------
            | LICENSE
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('license')) {

                Log::info('License upload started');

                if ($doctor->license) {

                    $oldLicense = public_path($doctor->license);

                    Log::info('Old license path', [
                        'path' => $oldLicense,
                    ]);

                    if (file_exists($oldLicense)) {

                        unlink($oldLicense);

                        Log::info('Old license deleted');
                    }
                }

                $file = $request->file('license');

                Log::info('New license file details', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);

                $fileName = time().'_'.$file->getClientOriginalName();

                $destinationPath = public_path('/doctors/license/');

                if (! file_exists($destinationPath)) {

                    mkdir($destinationPath, 0777, true);

                    Log::info('License directory created');
                }

                $file->move($destinationPath, $fileName);

                $doctor->license = '/doctors/license/'.$fileName;

                Log::info('License uploaded successfully', [
                    'saved_path' => $doctor->license,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | IDENTITY PROOF
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('identity_proof')) {

                Log::info('Identity proof upload started');

                if ($doctor->identity_proof) {

                    $oldFile = public_path($doctor->identity_proof);

                    if (file_exists($oldFile)) {

                        unlink($oldFile);

                        Log::info('Old identity proof deleted');
                    }
                }

                $file = $request->file('identity_proof');

                $fileName = time().'_'.$file->getClientOriginalName();

                $destinationPath = public_path('/doctors/identity_proof/');

                if (! file_exists($destinationPath)) {

                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);

                $doctor->identity_proof =
                    '/doctors/identity_proof/'.$fileName;

                Log::info('Identity proof uploaded successfully', [
                    'saved_path' => $doctor->identity_proof,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | PROFILE IMAGE
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                Log::info('Profile image upload started');

                if ($doctor->image) {

                    $oldImage = public_path($doctor->image);

                    Log::info('Old image path', [
                        'path' => $oldImage,
                    ]);

                    if (file_exists($oldImage)) {

                        unlink($oldImage);

                        Log::info('Old profile image deleted');
                    }
                }

                $file = $request->file('image');

                Log::info('New image details', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);

                $fileName = time().'_'.$file->getClientOriginalName();

                $destinationPath = public_path('/doctors/profile/');

                if (! file_exists($destinationPath)) {

                    mkdir($destinationPath, 0777, true);

                    Log::info('Profile folder created');
                }

                $file->move($destinationPath, $fileName);

                $doctor->image = '/doctors/profile/'.$fileName;

                Log::info('Profile image uploaded successfully', [
                    'saved_path' => $doctor->image,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SIGNATURE
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('signature')) {

                Log::info('Signature upload started');

                if ($doctor->signature) {

                    $oldSignature = public_path($doctor->signature);

                    Log::info('Old signature path', [
                        'path' => $oldSignature,
                    ]);

                    if (file_exists($oldSignature)) {

                        unlink($oldSignature);

                        Log::info('Old signature deleted');
                    }
                }

                $file = $request->file('signature');

                Log::info('New signature details', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);

                $fileName = time().'_'.$file->getClientOriginalName();

                $destinationPath = public_path('/doctors/signature/');

                if (! file_exists($destinationPath)) {

                    mkdir($destinationPath, 0777, true);

                    Log::info('Signature folder created');
                }

                $file->move($destinationPath, $fileName);

                $doctor->signature = '/doctors/signature/'.$fileName;

                Log::info('Signature uploaded successfully', [
                    'saved_path' => $doctor->signature,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | DEGREE CERTIFICATE
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('degree')) {

                Log::info('Degree upload started');

                if ($doctor->degree) {

                    $oldDegree = public_path($doctor->degree);

                    Log::info('Checking old degree', [

                        'path' => $oldDegree,

                        'exists' => file_exists($oldDegree),
                    ]);

                    if (file_exists($oldDegree)) {

                        unlink($oldDegree);

                        Log::info('Old degree deleted');
                    }
                }

                $file = $request->file('degree');

                Log::info('Degree file info', [

                    'name' => $file->getClientOriginalName(),

                    'size' => $file->getSize(),

                    'mime' => $file->getMimeType(),
                ]);

                $fileName =
                    uniqid().'_'.$file->getClientOriginalName();

                $destinationPath =
                    public_path('/doctors/degree/');

                if (! file_exists($destinationPath)) {

                    mkdir($destinationPath, 0777, true);

                    Log::info('Degree folder created');
                }

                Log::info('Moving degree file');

                $file->move($destinationPath, $fileName);

                Log::info('Degree moved successfully');

                $doctor->degree =
                    '/doctors/degree/'.$fileName;

                Log::info('Degree DB path updated', [

                    'path' => $doctor->degree,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | STAMP
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('stamp')) {

                Log::info('Stamp upload started');

                if ($doctor->stamp) {

                    $oldStamp = public_path($doctor->stamp);

                    Log::info('Checking old stamp', [

                        'path' => $oldStamp,

                        'exists' => file_exists($oldStamp),
                    ]);

                    if (file_exists($oldStamp)) {

                        unlink($oldStamp);

                        Log::info('Old stamp deleted');
                    }
                }

                $file = $request->file('stamp');

                Log::info('Stamp file info', [

                    'name' => $file->getClientOriginalName(),

                    'size' => $file->getSize(),

                    'mime' => $file->getMimeType(),
                ]);

                $fileName =
                    uniqid().'_'.$file->getClientOriginalName();

                $destinationPath =
                    public_path('/doctors/stamp/');

                if (! file_exists($destinationPath)) {

                    mkdir($destinationPath, 0777, true);

                    Log::info('Stamp folder created');
                }

                Log::info('Moving stamp file');

                $file->move($destinationPath, $fileName);

                Log::info('Stamp moved successfully');

                $doctor->stamp =
                    '/doctors/stamp/'.$fileName;

                Log::info('Stamp DB path updated', [

                    'path' => $doctor->stamp,
                ]);
            }

            Log::info('Saving doctor model', [
                'doctor_data' => $doctor->toArray(),
            ]);

            $doctor->save();

            DB::commit();

            Log::info('Doctor updated successfully', [
                'doctor_id' => $doctor->id,
            ]);

            return redirect()->back()->with(
                'success',
                'Doctor updated successfully.'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Error updating doctor', [
                'doctor_id' => $id,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with(
                'error',
                'An error occurred: '.$e->getMessage()
            );
        }
    }

    // public function saveReport(Request $request)
    // {
    //     $request->validate([
    //         'patient_id' => 'required',
    //         'report_file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
    //     ]);

    //     $file = $request->file('report_file');

    //     $filename = time().'_'.$file->getClientOriginalName();

    //     $file->move(public_path('reports'), $filename);

    //     PatientReport::create([
    //         'patient_id' => $request->patient_id,
    //         'doctor_id' => Auth::user()->id,
    //         'appointment_id' => $request->appointment_id,
    //         'report_name' => $request->report_name,
    //         'report_file' => 'reports/'.$filename,
    //         'remarks' => $request->remarks,
    //     ]);

    //     return redirect()->back()->with('success', 'Report uploaded successfully');
    // }

    public function saveReport(Request $request)
    {
        try {

            Log::info('saveReport API called', [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['report_file']),
                'has_file' => $request->hasFile('report_file'),
                'all_files' => array_keys($request->allFiles()),
            ]);

            $request->validate([
                'patient_id' => 'required',
                'report_file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            ]);

            Log::info('Validation passed');

            if (! $request->hasFile('report_file')) {

                Log::error('File not found in request');

                return redirect()->back()->with('error', 'No file uploaded');
            }

            $file = $request->file('report_file');

            Log::info('Uploaded file details', [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'is_valid' => $file->isValid(),
            ]);

            $filename = time().'_'.$file->getClientOriginalName();

            $destinationPath = public_path('reports');

            Log::info('Moving file', [
                'destination' => $destinationPath,
                'filename' => $filename,
            ]);

            $file->move($destinationPath, $filename);

            Log::info('File moved successfully', [
                'stored_path' => 'reports/'.$filename,
            ]);

            $reportData = [
                'patient_id' => $request->patient_id,
                'doctor_id' => Auth::id(),
                'appointment_id' => $request->appointment_id,
                'report_name' => $request->report_name,
                'report_file' => 'reports/'.$filename,
                'remarks' => $request->remarks,
            ];

            Log::info('Creating PatientReport', $reportData);

            $report = PatientReport::create($reportData);

            Log::info('PatientReport created successfully', [
                'report_id' => $report->id,
            ]);

            return redirect()->back()->with('success', 'Report uploaded successfully');

        } catch (ValidationException $e) {

            Log::error('Validation failed', [
                'errors' => $e->errors(),
            ]);

            throw $e;
        } catch (\Exception $e) {

            Log::error('Report upload failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to upload report');
        }
    }

    public function allReports()
    {
        $reports = DB::table('patient_reports as pr')
            ->join('patients as p', 'pr.patient_id', '=', 'p.id')
            ->join('users as u', 'pr.doctor_id', '=', 'u.id')
            ->select(
                'pr.*',
                'p.full_name as patient_name',
                'u.name as doctor_name'
            )
            ->orderBy('pr.id', 'desc')
            ->paginate(10);

        return view('allreports', compact('reports'));
    }
}
