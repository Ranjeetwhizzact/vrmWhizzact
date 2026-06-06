<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeComtroller;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Models\Doctor;
use App\Services\ZoomServices;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('index');

// });

// Public Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/meeting-rating-store', [AppointmentController::class, 'storeRating'])->name('meeting.rating.store');

Route::middleware('auth')->group(function () {

    Route::middleware([])->group(function () {
        Route::get('/customers', [PatientController::class, 'patients'])->name('customers');
        Route::get('/doctor-profile/{id}', [DoctorController::class, 'doctorprofile'])
            ->name('doctorprofile');
        Route::post('/patient/add-log', [PatientController::class, 'addPatientLog'])->name('patient.addLog');
        // Bulk upload routes
        Route::post('/bulk-upload', [PatientController::class, 'bulkUpload'])->name('bulk-upload.upload');

        Route::get('/bulk-upload/progress/{uploadId}', [PatientController::class, 'getProgress'])->name('bulk-upload.progress');
        Route::get('/bulk-upload/sample', [PatientController::class, 'downloadSample'])->name('bulk-upload.sample');
        Route::get('/get-divisions-by-company/{companyName}', [PatientController::class, 'getDivisionsByCompany']);
        Route::get('/get-branches-by-division/{divisionCode}', [PatientController::class, 'getBranchesByDivision']);
        // Add this temporary route in routes/web.php for debugging
        Route::get('/debug-bulk-upload', function () {
            $testResults = [
                'success' => true,
                'total_rows' => 15,
                'success_rows' => 8,
                'failed_rows' => 7,
                'failures' => [
                    [
                        'row' => 2,
                        'proposal_number' => 'PROP2024002',
                        'errors' => ['Invalid phone number', 'Invalid MER Type', 'Sum Assured too low'],
                    ],
                    [
                        'row' => 4,
                        'proposal_number' => 'PROP2024004',
                        'errors' => ['Customer Full Name is required'],
                    ],
                ],
                'message' => 'Uploaded 8 out of 15 records. 7 records failed.',
            ];

            return response()->json($testResults);
        });
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/download-word', [PatientController::class, 'downloadWord'])->name('download.word');
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/patient/reports', [DoctorController::class, 'allReports'])->name('all.reports');
        Route::get('/edituser/{id}', [AuthController::class, 'edituser'])->name('edituser');
        Route::get('/patients/export/excel', [PatientController::class, 'exportExcel'])->name('patients.export.excel');
        Route::get('/', [HomeComtroller::class, 'index'])->name('dashboard');
        Route::get('/createdoctor', [DoctorController::class, 'createdoctor']);
        Route::post('/storepatient', [PatientController::class, 'storepatient']);
        Route::delete('/deletepatient/{id}', [PatientController::class, 'deletepatient'])->name('deletepatient');
        Route::delete('/deletedoctor/{id}', [DoctorController::class, 'deletedoctor'])->name('deletedoctor');
        Route::get('/editdoctor/{id}', [DoctorController::class, 'editdoctor'])->name('editdoctor');
        Route::post('/storedoctor', [DoctorController::class, 'storedoctor'])->name('store.doctor');
        Route::post('/updatedoctor/{id}', [DoctorController::class, 'updatedoctor'])->name('update.doctor');
        Route::post('/storepatientlog', [PatientController::class, 'storepatientlog']);
        Route::get('/createpatient', [PatientController::class, 'createpatient']);
        Route::get('/editpatient/{id}', [PatientController::class, 'editpatient'])->name('editpatient');
        Route::get('/customers', [PatientController::class, 'patients'])->name('customers');
        Route::get('/viewprescriptions/{id}', [PrescriptionController::class, 'prescriptionForm'])->name('viewprescriptions');
        Route::post('/storerescription', [PrescriptionController::class, 'storerescription']);

        Route::post('/getrecording/{id}', [AppointmentController::class, 'getRecording'])->name('getRecording');

        Route::get('/schedule', [AppointmentController::class, 'index'])->name('schedule');
        Route::post('/schedule', [AppointmentController::class, 'scheduleCall'])->name('schedule-call');
        Route::get('/join-meeting/{id}', [AppointmentController::class, 'joinMeeting'])->name('join.meeting');

        // Route::get('/viewpatient', [PatientController::class, 'viewpatient'])->name('customers');
        Route::get('/viewpatient/{id}', [PatientController::class, 'viewpatient'])->name('viewpatient');
        Route::get('/patient/{id}', [PatientController::class, 'show'])->name('patient.show');
        Route::post('/storeschedule', [ScheduleController::class, 'storeschedule'])->name('storeschedule');
        Route::post('/storepatientlog', [PatientController::class, 'storepatientlog'])->name('storepatientlog');
        Route::get('/searchpatients', [ScheduleController::class, 'searchpatients']);
        Route::get('/get-branches', [ScheduleController::class, 'getBranches']);
        Route::get('/searchuser', [DoctorController::class, 'searchuser']);
        Route::get('/doctor', [DoctorController::class, 'doctor']);
        Route::get('/getavailabletimes', [AppointmentController::class, 'checkdoctor']);
        Route::get('/get-divisions/{companyName}', [PatientController::class, 'getDivisions'])->name('get.divisions');
        Route::get('/get-branches/{divisionId}', [PatientController::class, 'getBranches'])->name('get.branches');
    });
    Route::get('/assignpatients', [ScheduleController::class, 'assignpatients'])->name('assignpatients');
    Route::get('/report/{id}', [ReportController::class, 'report'])->name('report');
    Route::post('/changestatus', [ScheduleController::class, 'changestatus'])->name('changestatus');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/upload-report', [DoctorController::class, 'saveReport'])->name('upload.report');

});
Route::get('avalableform/{proposalno}', [HomeComtroller::class, 'avalableform']);
Route::post('customermail', [PatientController::class, 'customermail']);

Route::get('/join-meeting/{id}', [AppointmentController::class, 'joinMeeting'])
    ->name('join.meeting');
Route::post('/upload-recording', [AppointmentController::class, 'uploadRecording'])->name('upload.recording');
Route::post('/save-meeting-location', [AppointmentController::class, 'saveMeetingLocation'])->name('save.meeting.location');
Route::post('/storereport', [AppointmentController::class, 'storereport'])->name('storereport');

Route::get('/zoom-token-test/{id}', function ($id) {

    $doctor = Doctor::findOrFail($id);

    $zoom = new ZoomServices;

    return $zoom->generateAccessToken($doctor);
});
