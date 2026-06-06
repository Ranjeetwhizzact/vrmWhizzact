<?php

namespace App\Http\Controllers;

use App\Exports\PatientsExport;
use App\Imports\CustomerImport;
use App\Mail\CustomerMail;
use App\Models\BranchDetail;
use App\Models\CompanyDetail;
use App\Models\CustomerAvailability;
use App\Models\Division;
use App\Models\Location;
use App\Models\Patient;
use App\Models\Patientlog;
use App\Models\PatientReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use Vinkla\Hashids\Facades\Hashids;

class PatientController extends Controller
{
    //

    public function show($id)
    {
        $product = Patient::find($id);

        return response()->json($product);
    }

    public function getDivisions($companyName)
    {
        $divisions = Division::whereHas('company', function ($q) use ($companyName) {
            $q->where('company_name', $companyName);
        })
            ->where('is_active', 1)
            ->get(['id', 'division_name', 'division_code']);

        return response()->json($divisions);
    }

    // Add this new method to fetch branches via AJAX
    public function getBranches($divisionId)
    {
        $branches = BranchDetail::where('division_code', $divisionId)
            ->where('is_active', 1)
            ->get(['id', 'branch_name', 'branch_code', 'location']);

        return response()->json($branches);
    }

    // public function exportExcel(Request $request)
    // {
    //     $query = Patient::with('location');

    //     // Apply same filters as the index page
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->filled('location')) {
    //         $query->where('location_id', $request->location);
    //     }

    //     if ($request->filled('proposal_number')) {
    //         $query->where('proposal_number', 'LIKE', '%' . $request->proposal_number . '%');
    //     }

    //     // Apply Insurance Company Filter - FIXED
    //     if ($request->filled('insurance_company_name')) {
    //         $companyName = $request->insurance_company_name;
    //         $query->where('insurance_company_name', 'LIKE', '%' . $companyName . '%'); // Added LIKE for partial match
    //     }

    //     // Apply Division Filter - FIXED
    //     if ($request->filled('division_code')) {
    //         $divisionCode = $request->division_code;
    //         $query->where('division_code', $divisionCode);
    //     }

    //     // Also check for division_id if that's being sent

    //     // Apply Branch Code Filter - FIXED
    //     if ($request->filled('branch')) {  // Changed from 'branch' to 'branch_code'
    //         $branchCode = $request->branch;
    //         $query->where('branch', $branchCode);  // Changed from 'branch' to 'branch_code'
    //     }

    //     // Also handle branch_id if sent

    //     // Apply date range filter
    //     if ($request->filled('date_range')) {
    //         $dateRange = $request->date_range;
    //         $today = now();

    //         switch ($dateRange) {
    //             case 'today':
    //                 $query->whereDate('created_at', $today->toDateString());
    //                 break;
    //             case 'yesterday':
    //                 $query->whereDate('created_at', $today->subDay()->toDateString());
    //                 break;
    //             case 'week':
    //                 $query->whereBetween('created_at', [$today->subDays(7), $today]);
    //                 break;
    //             case 'month':
    //                 $query->whereBetween('created_at', [$today->subDays(30), $today]);
    //                 break;
    //             case '3months':
    //                 $query->whereBetween('created_at', [$today->subMonths(3), $today]);
    //                 break;
    //             case '6months':
    //                 $query->whereBetween('created_at', [$today->subMonths(6), $today]);
    //                 break;
    //             case 'year':
    //                 $query->whereBetween('created_at', [$today->subYear(), $today]);
    //                 break;
    //         }
    //     }

    //     if ($request->filled('from_date') && $request->filled('to_date')) {
    //         $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
    //     }

    //     // Debug: Check if query is being built correctly
    //     Log::info('Export Query SQL: ' . $query->toSql());
    //     Log::info('Export Query Bindings: ', $query->getBindings());

    //     $patients = $query->orderBy('id', 'desc')->get();

    //     // Check if patients exist
    //     if ($patients->isEmpty()) {
    //         return redirect()->back()->with('error', 'No records found to export with the selected filters.');
    //     }

    //     return Excel::download(new PatientsExport($patients), 'patients_' . date('Y-m-d_His') . '.xlsx');
    // }

    public function exportExcel(Request $request)
    {
        $query = Patient::with('location');

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Location Filter
        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        // Proposal Number Filter
        if ($request->filled('proposal_number')) {
            $query->where(
                'proposal_number',
                'LIKE',
                '%'.$request->proposal_number.'%'
            );
        }

        // Insurance Company Filter
        if ($request->filled('insurance_company_name')) {
            $query->where(
                'insurance_company_name',
                'LIKE',
                '%'.$request->insurance_company_name.'%'
            );
        }

        // Division Code Filter
        if ($request->filled('division_code')) {
            $query->where('division_code', $request->division_code);
        }

        // Branch Filter
        if ($request->filled('branch')) {
            $query->where('branch', $request->branch);
        }

        // Date Range Filter
        if ($request->filled('date_range')) {

            switch ($request->date_range) {

                case 'today':
                    $query->whereDate('created_at', now());
                    break;

                case 'yesterday':
                    $query->whereDate('created_at', now()->subDay());
                    break;

                case 'week':
                    $query->whereBetween(
                        'created_at',
                        [now()->subDays(7), now()]
                    );
                    break;

                case 'month':
                    $query->whereBetween(
                        'created_at',
                        [now()->subDays(30), now()]
                    );
                    break;

                case '3months':
                    $query->whereBetween(
                        'created_at',
                        [now()->subMonths(3), now()]
                    );
                    break;

                case '6months':
                    $query->whereBetween(
                        'created_at',
                        [now()->subMonths(6), now()]
                    );
                    break;

                case 'year':
                    $query->whereBetween(
                        'created_at',
                        [now()->subYear(), now()]
                    );
                    break;
            }
        }

        // Custom Date Filter
        if (
            $request->filled('from_date') &&
            $request->filled('to_date')
        ) {
            $query->whereBetween('created_at', [
                $request->from_date,
                $request->to_date,
            ]);
        }

        // Debug Logs
        Log::info('Export Query SQL: '.$query->toSql());
        Log::info('Export Query Bindings: ', $query->getBindings());

        // Fetch Data
        $patients = $query->orderBy('id', 'desc')->get();

        // Check Data Exists
        if ($patients->isEmpty()) {
            return redirect()->back()->with(
                'error',
                'No records found to export.'
            );
        }

        // Download Excel
        return Excel::download(
            new PatientsExport($patients),
            'patients_'.date('Y-m-d_His').'.xlsx'
        );
    }

    public function downloadWord()
    {
        $records = Patient::all();
        $phpWord = new PhpWord;

        // Title style
        $phpWord->addTitleStyle(1, [
            'bold' => true,
            'size' => 20,
            'color' => '2E75B6',
        ], [
            'alignment' => Jc::CENTER,
        ]);

        $labelStyle = ['bold' => true, 'size' => 10];
        $valueStyle = ['size' => 10, 'color' => '333333'];

        $section = $phpWord->addSection([
            'marginTop' => 800,
            'marginBottom' => 800,
            'marginLeft' => 1200,
            'marginRight' => 1200,
        ]);

        $section->addTitle('Patient Records Report', 1);
        $section->addTextBreak(1);

        foreach ($records as $index => $record) {
            $section->addText('Patient #'.($index + 1), ['bold' => true, 'size' => 12, 'color' => '1F4E79']);
            $section->addTextBreak(0.5);

            $fields = [
                'ID' => $record->id,
                'First Name' => $record->first_name,
                'Last Name' => $record->last_name,
                'Email' => $record->email,
                'Phone' => $record->phone,
                'Gender' => $record->gender,
                'Marital Status' => $record->marital_status,
                'Date of Birth' => $record->dob,
                'Blood Group' => $record->blood_group,
                'Proposal Number' => $record->proposal_number,
                'ID Type' => $record->id_type,
                'ID Number' => $record->id_number,
                'ID Document' => $record->id_document,
                'Third Party Administrator' => $record->third_party_administrator,
                'Insurance Company' => $record->insurance_company_name,
                'Insurance Email' => $record->insurance_company_email,
                'Address' => $record->address,
                'Documents' => $record->documents,
                'Status' => $record->status,
                'Active' => $record->is_active ? 'Yes' : 'No',
            ];

            foreach ($fields as $label => $value) {
                $textRun = $section->addTextRun();
                $textRun->addText("{$label}: ", $labelStyle);
                $textRun->addText($value ?? '—', $valueStyle);
            }

            $section->addTextBreak(1);
            $section->addLine(['weight' => 1, 'width' => 480, 'color' => 'AAAAAA']);
            $section->addTextBreak(1);
        }

        // Export the file
        $fileName = 'Patient_Records_'.date('Ymd_His').'.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word_');
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function addPatientLog(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'status' => 'required|string',
            'comment' => 'required|string|max:500',
        ]);

        $log = new Patientlog;
        $log->patient_id = $request->patient_id;
        $log->status = $request->status;
        $log->comment = $request->comment;
        $log->created_by = Auth::user()->email ?? Auth::user()->name;
        $log->save();

        // Optionally update patient's main status if needed
        // $patient = Patient::find($request->patient_id);
        // $patient->status = $request->status;
        // $patient->save();

        return response()->json(['success' => true, 'message' => 'Log added successfully']);
    }

    public function patients(Request $request)
    {
        // Get all filter parameters
        $status = $request->get('status');
        $locationId = $request->get('location');
        $proposalNumber = $request->get('proposal_number');
        $dateRange = $request->get('date_range');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $companyName = $request->get('insurance_company_name');
        $divisionCode = $request->get('division_code');
        $branchCode = $request->get('branch');

        $patientsQuery = Patient::with('location')->orderBy('id', 'desc');

        // Apply Status Filter
        if (! empty($status)) {
            $patientsQuery->where('status', $status);
        }

        // Apply Location Filter

        // Apply Proposal Number Filter
        if (! empty($proposalNumber)) {
            $patientsQuery->where('proposal_number', 'LIKE', '%'.$proposalNumber.'%');
        }

        // Apply Insurance Company Filter
        if (! empty($companyName)) {
            $patientsQuery->where('insurance_company_name', $companyName);
        }

        // Apply Division Filter
        if (! empty($divisionCode)) {
            $patientsQuery->where('division_code', $divisionCode);
        }

        // Apply Branch Code Filter
        if (! empty($branchCode)) {
            $patientsQuery->where('branch', $branchCode);
        }

        // Apply Date Range Filter (FIXED)
        if (! empty($dateRange)) {
            $today = Carbon::today();

            switch ($dateRange) {
                case 'today':
                    $patientsQuery->whereDate('created_at', $today);
                    break;
                case 'yesterday':
                    $patientsQuery->whereDate('created_at', Carbon::yesterday());
                    break;
                case 'week':
                    $patientsQuery->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
                    break;
                case 'month':
                    $patientsQuery->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]);
                    break;
                case '3months':
                    $patientsQuery->whereBetween('created_at', [Carbon::now()->subMonths(3), Carbon::now()]);
                    break;
                case '6months':
                    $patientsQuery->whereBetween('created_at', [Carbon::now()->subMonths(6), Carbon::now()]);
                    break;
                case 'year':
                    $patientsQuery->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()]);
                    break;
            }
        }

        // Apply Custom Date Range Filter
        if (! empty($fromDate) && ! empty($toDate)) {
            $from = Carbon::parse($fromDate)->startOfDay();
            $to = Carbon::parse($toDate)->endOfDay();
            $patientsQuery->whereBetween('created_at', [$from, $to]);
        }

        $patients = $patientsQuery->paginate(10);

        $patients->transform(function ($patient) {
            $patient->hashed_id = Hashids::encode($patient->id);

            return $patient;
        });

        // Get data for filters
        $locations = Location::orderBy('id', 'asc')->get();
        $companies = CompanyDetail::where('is_active', 1)->get();
        $divisions = Division::all();

        return view('customers', compact('patients', 'locations', 'companies', 'divisions'));
    }

    public function bulkUpload(Request $request)
    {
        try {
            Log::info('Bulk upload request received');

            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            ]);

            $file = $request->file('excel_file');

            // Create import instance
            $import = new CustomerImport;

            // Get total rows first
            $totalRows = $this->getTotalRows($file);
            $import->setTotalRows($totalRows);

            // Import the file
            Excel::import($import, $file);

            $results = [
                'success' => true,
                'total_rows' => $import->getTotalRows(),
                'success_rows' => $import->getSuccessRows(),
                'failed_rows' => $import->getFailedRows(),
                'failures' => $import->getFailures(),
                'message' => $this->getResultMessage($import->getSuccessRows(), $import->getFailedRows(), $import->getTotalRows()),
            ];

            Log::info('Bulk upload completed', $results);

            return response()->json([
                'success' => true,
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk upload error: '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    private function getTotalRows($file)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();

            return $worksheet->getHighestRow() - 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getResultMessage($successCount, $failedCount, $totalCount)
    {
        if ($failedCount == 0) {
            return "✓ Successfully uploaded all {$successCount} records!";
        } elseif ($successCount > 0) {
            return "⚠ Uploaded {$successCount} out of {$totalCount} records. {$failedCount} records failed.";
        } else {
            return '✗ Failed to upload any records. Please check the errors below.';
        }
    }

    public function downloadSample()
    {
        $headers = [
            'proposal_no',
            'customers_full_name',
            'customers_mobile',
            'email_id',
            'customers_gender',
            'mer_type',
            'customers_profile',
            'sum_assured',
            'case_registration_date_time',
            'customer_dob',
            'prefered_language',
            'address',
            'pincode',
            'branch_no',
            'division_code',
            'insurance_company_name',
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            // Sample valid row
            fputcsv($file, [
                'PROP2024001',
                'Rajesh Kumar Sharma',
                '9876543210',
                'rajesh@email.com',
                'MALE',
                'VIDEO MER',
                'NORMAL',
                '2500000',
                '2024-01-15 10:30:00',
                '1985-05-20',
                'ENGLISH',
                '123 Park Street',
                '400001',
                '1001',
                'D088',
                'Life Insurance Corporation of India',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_patients.csv"',
        ]);
    }

    public function getProgress($uploadId)
    {
        $uploadService = new BulkUploadService;
        $progress = $uploadService->getProgress($uploadId);

        if (! $progress) {
            return response()->json([
                'exists' => false,
                'message' => 'Upload session not found',
            ]);
        }

        return response()->json([
            'exists' => true,
            'status' => $progress['status'],
            'progress' => $progress['progress'],
            'message' => $progress['message'],
            'results' => $progress['results'] ?? null,
            'updated_at' => $progress['updated_at'],
        ]);
    }

    public function createpatient(Request $request)
    {
        $companies = CompanyDetail::where('is_active', 1)->get();

        return view('addpatient', ['companies' => $companies]);
    }

    public function viewpatient($encrypted_id)
    {
        try {
            // Decrypt the ID
            $patientId = Crypt::decrypt($encrypted_id);
        } catch (DecryptException $e) {
            // If the ID was tampered with or is invalid, redirect back
            return redirect()->back()->with('error', 'Invalid patient security token.');
        }

        $patient = Patient::find($patientId);

        if (! $patient) {
            return abort(404, 'Patient not found');
        }

        $logs = Patientlog::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get();
        $patientReports = PatientReport::where('patient_id', $patient->id)->get();

        return view('viewpatient', [
            'patient' => $patient,
            'logs' => $logs,
            'patientReports' => $patientReports,
        ]);
    }

    public function editpatient(Request $request, $hashed_id)
    {
        $decoded = Hashids::decode($hashed_id);
        $companies = CompanyDetail::where('is_active', 1)->get();
        $patientId = $decoded[0];
        $patient = Patient::with(['division', 'branchDetail'])->find($patientId);

        if (! $patient) {
            return redirect()->back()->with('error', 'Patient not found.');
        }

        // dd($patient);
        return view('addpatient', ['patient' => $patient, 'companies' => $companies]);
    }

    // public function storepatientlog(Request $request)
    // {
    //     // Validate Request
    //     $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|email',
    //         'message' => 'required|string',
    //     ]);

    //     // Save Data to Database
    //     $user = Auth::user()->email;
    //     $patientlog = new Patientlog;
    //     $patientlog->name = $request->name;
    //     $patientlog->email = $request->email;
    //     $patientlog->message = $request->message;
    //     $patientlog->created_by = $user;
    //     $patientlog->updated_by = $user;
    //     $patientlog->save();

    //     // Generate PDF
    //     $pdf = Pdf::loadView('pdf.template', compact('patientlog'));

    //     // Force download prompt (User can save it in the Downloads folder)
    //     return response()->streamDownload(function () use ($pdf) {
    //         echo $pdf->output();
    //     }, "patient_log.pdf");
    // }

    // public function customermail(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'avalibledate' => 'required|date',
    //         'start_time' => 'required',
    //         'end_time' => 'required',
    //         'proposal_number' => 'required'  // Ensure the email is provided
    //     ]);
    //     $patient = Patient::where('proposal_number', $request->proposal_number)->first();
    //     $customer_name = $patient->first_name . ' ' . $patient->last_name;
    //     $patient->providedate = $request->avalibledate;
    //     $patient->save();
    //     // Sending the email
    //     $response = Http::withHeaders([
    //         'accept' => 'application/json',
    //         'authkey' => '400026Aum41tS2Xqb68664708P1', // 🔒 Replace with your actual key
    //         'content-type' => 'application/json',
    //     ])->post('https://control.msg91.com/api/v5/email/send', [
    //         'recipients' => [
    //             [
    //                 'to' => [
    //                     [
    //                         'name' => 'Sachin',
    //                         'email' => 'honesthealthcare3@gmail.com',
    //                     ]
    //                 ],

    //                 'variables' => [
    //                     'VAR1' => $request->customer_name,
    //                     'VAR2' => $request->proposal_number,
    //                     'VAR3' => $request->avalibledate,
    //                     'VAR4' => $request->start_time,
    //                     'VAR5' => $request->end_time,

    //                 ]
    //             ]
    //         ],
    //         'from' => [
    //             'name' => 'Honest Health Care',
    //             'email' => 'honesthealthcare@email.whizzactsolutions.com'
    //         ],
    //         'domain' => 'email.whizzactsolutions.com',
    //         'reply_to' => [
    //             [
    //                 'email' => 'honesthealthcare3@gmail.com'
    //             ]
    //         ],
    //         'attachments' => [],
    //         'template_id' => 'honest_availability_requested'
    //     ]);

    //     // Handle response
    //     if ($response->successful()) {
    //         // return response()->json(['message' => 'Email sent successfully', 'data' => $response->json()]);
    //         return redirect()->back()->with('success', 'Your form is submitted Successfully ');
    //     }

    //     // return response()->json([
    //     //     'error' => 'Failed to send email',
    //     //     'status' => $response->status(),
    //     //     'body' => $response->body()
    //     // ], $response->status());

    // }

    public function customermail(Request $request)
    {
        $validatedData = $request->validate([
            'avalibledate' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'proposal_number' => 'required',
        ]);

        $patient = Patient::where('proposal_number', $request->proposal_number)->first();

        if (! $patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        // Save availability in separate table
        CustomerAvailability::create([
            'patient_id' => $patient->id,
            'proposal_number' => $request->proposal_number,
            'available_date' => $request->avalibledate,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        // Send email
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authkey' => '400026Aum41tS2Xqb68664708P1',
            'content-type' => 'application/json',
        ])->post('https://control.msg91.com/api/v5/email/send', [
            'recipients' => [
                [
                    'to' => [
                        [
                            'name' => 'Sachin',
                            'email' => 'honesthealthcare3@gmail.com',
                        ],
                    ],

                    'variables' => [
                        'VAR1' => $patient->first_name.' '.$patient->last_name,
                        'VAR2' => $request->proposal_number,
                        'VAR3' => $request->avalibledate,
                        'VAR4' => $request->start_time,
                        'VAR5' => $request->end_time,
                    ],
                ],
            ],
            'from' => [
                'name' => 'Honest Health Care',
                'email' => 'honesthealthcare@email.whizzactsolutions.com',
            ],
            'domain' => 'email.whizzactsolutions.com',
            'reply_to' => [
                [
                    'email' => 'honesthealthcare3@gmail.com',
                ],
            ],
            'attachments' => [],
            'template_id' => 'honest_availability_requested',
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Your form is submitted Successfully');
        }

        return redirect()->back()->with('error', 'Email sending failed');
    }

    public function getDivisionsByCompany($companyName)
    {
        $divisions = Division::whereHas('company', function ($query) use ($companyName) {
            $query->where('company_name', $companyName);
        })->get();

        return response()->json($divisions);
    }

    public function getBranchesByDivision($divisionCode)
    {
        $branches = BranchDetail::where('division_code', $divisionCode)->get();

        return response()->json($branches);
    }

    public function deletepatient($hashed_id)
    {
        $decoded = Hashids::decode($hashed_id);
        $patientId = $decoded[0];
        $patient = Patient::find($patientId);
        $patient->delete();

        return back()->with('success', 'Patient is deleted Sucessfully');
    }

    // public function storepatient(Request $request)
    // {
    //     // Validation (adjust rules as needed)
    //     $request->validate([
    //         'first_name' => 'required|string',
    //         'last_name'  => 'required|string',
    //         'phone'      => 'required|digits:10',
    //         'gender'     => 'required',
    //         'address'    => 'required',
    //         'proposal_number' => 'required',
    //         'insurance_company_name' => 'required',
    //         'branch' => 'required',
    //     ]);

    //     // Find or create patient
    //     if ($request->filled('id')) {
    //         $patient = Patient::findOrFail($request->id);
    //     } else {
    //         $patient = new Patient();
    //     }

    //     $userEmail = Auth::user()->email;

    //     // Handle file upload (documents column)
    //     if ($request->hasFile('documents')) {
    //         $file = $request->file('documents');
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $destinationPath = public_path('/patients/documents/');
    //         $file->move($destinationPath, $fileName);
    //         $patient->documents = '/patients/documents/' . $fileName;
    //     }

    //     // Determine location_id from branch_code
    //     $locationId = null;
    //     $branchCode = null;
    //     $branchDetail = BranchDetail::where('branch_code', $request->branch_code)->first();
    //     if ($branchDetail) {
    //         $branchCode = $branchDetail->branch_code;
    //         $location = Location::where('id', $branchDetail->location)->first();
    //         if (!$location) {
    //             $location = Location::where('location_name', $branchDetail->location)->first();
    //         }
    //         $locationId = $location ? $location->id : null;
    //     }

    //     // Assign data – map first_name + last_name to full_name
    //     $patient->full_name = trim($request->first_name . ' ' . $request->last_name);
    //     $patient->email = $request->email;
    //     $patient->phone = $request->phone;
    //     $patient->gender = $request->gender;
    //     $patient->dob = $request->dob;
    //     $patient->proposal_number = $request->proposal_number;
    //     $patient->insurance_company_name = $request->insurance_company_name;
    //     $patient->insurance_company_email = $request->insurance_company_email;
    //     $patient->third_party_administrator = $request->third_party_administrator;
    //     $patient->address = $request->address;
    //     $patient->pincode = $request->pincode ?? null;          // ✅ safe if column exists
    //     $patient->mer_type = $request->mer_type ?? null;
    //     $patient->preferred_language = $request->preferred_language ?? null;
    //     $patient->health_problems = $request->health_problems ?? null;
    //     $patient->customer_profile = $request->customer_profile ?? null;
    //     $patient->providedate = $request->providedate ?? null;
    //     $patient->sum_assured = $request->sum_assured ?? null;
    //     $patient->case_registration_datetime = $request->case_registration_datetime ?? null;

    //     // Fixed / derived values (corrected types)
    //     $patient->status = 'Unassigned';
    //     $patient->zonal_Code = 7;                              // ✅ integer, not string
    //     $patient->location_id = $locationId;
    //     $patient->branch = $branchCode;
    //     $patient->is_active = true;                            // ✅ boolean, not 'active'
    //     $patient->created_by = $userEmail;
    //     $patient->updated_by = $userEmail;

    //     $patient->save();

    //     // Send email/SMS only for new patients
    //     if (!$request->filled('id')) {
    //         $encryptedProposal = Crypt::encrypt($patient->proposal_number);
    //         $availabilityLink = env('APP_URL') . "/avalableform/{$encryptedProposal}";

    //         // Email via MSG91
    //         Http::withHeaders([
    //             'accept' => 'application/json',
    //             'authkey' => '400026Aum41tS2Xqb68664708P1',
    //             'content-type' => 'application/json',
    //         ])->post('https://control.msg91.com/api/v5/email/send', [
    //             'recipients' => [[
    //                 'to' => [[
    //                     'name' => $patient->full_name,
    //                     'email' => $patient->email
    //                 ]],
    //                 'cc' => [[
    //                     'name' => 'Honest Health Care',
    //                     'email' => 'honesthealthcare3@gmail.com'
    //                 ]],
    //                 'variables' => [
    //                     'VAR1' => $patient->full_name,
    //                     'VAR2' => $patient->email,
    //                     'VAR3' => $patient->address,
    //                     'VAR4' => $availabilityLink,
    //                 ]
    //             ]],
    //             'from' => [
    //                 'name' => 'Honest Health Care',
    //                 'email' => 'honesthealthcare@email.whizzactsolutions.com'
    //             ],
    //             'domain' => 'email.whizzactsolutions.com',
    //             'reply_to' => [['email' => 'honesthealthcare3@gmail.com']],
    //             'template_id' => 'honest_patient_availability_request'
    //         ]);

    //         // SMS via MSG91 Flow
    //         Http::withHeaders([
    //             'authkey' => '447742AMCfHYvVexw68079d89P1',
    //             'Content-Type' => 'application/json',
    //         ])->post('https://control.msg91.com/api/v5/flow', [
    //             'template_id' => '69e899ce90138080c00a4ab2',
    //             'short_url' => 1,
    //             'recipients' => [[
    //                 'mobiles' => '91' . $patient->phone,
    //                 'var1' => $patient->full_name,
    //                 'var2' => $availabilityLink,
    //             ]]
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Patient saved successfully!');
    // }

    public function storepatient(Request $request)
    {
        Log::info('storepatient function started', [
            'request_data' => $request->all(),
        ]);

        try {

            // Validation (adjust rules as needed)
            $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone' => 'required|digits:10',
                'gender' => 'required',
                'address' => 'required',
                'proposal_number' => 'required',
                'insurance_company_name' => 'required',
                'branch_code' => 'required',
            ]);

            Log::info('Validation successful');

            // Find or create patient
            if ($request->filled('id')) {

                Log::info('Updating existing patient', [
                    'patient_id' => $request->id,
                ]);

                $patient = Patient::findOrFail($request->id);

                Log::info('Patient found', [
                    'patient' => $patient,
                ]);
            } else {

                Log::info('Creating new patient');

                $patient = new Patient;
            }

            $userEmail = Auth::user()->email;

            Log::info('Authenticated user email fetched', [
                'user_email' => $userEmail,
            ]);

            // Handle file upload (documents column)
            if ($request->hasFile('documents')) {

                Log::info('Document upload detected');

                $file = $request->file('documents');

                Log::info('File details', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ]);

                $fileName = time().'_'.$file->getClientOriginalName();

                $destinationPath = public_path('/patients/documents/');

                Log::info('Moving uploaded file', [
                    'destination_path' => $destinationPath,
                    'file_name' => $fileName,
                ]);

                $file->move($destinationPath, $fileName);

                $patient->documents = '/patients/documents/'.$fileName;

                Log::info('Document uploaded successfully', [
                    'document_path' => $patient->documents,
                ]);
            } else {

                Log::info('No document uploaded');
            }

            // Determine location_id from branch_code
            $locationId = null;
            $branchCode = null;

            Log::info('Fetching branch detail', [
                'branch_code_request' => $request->branch_code,
            ]);

            $branchDetail = BranchDetail::where('branch_code', $request->branch_code)->first();

            if ($branchDetail) {

                Log::info('Branch detail found', [
                    'branch_detail' => $branchDetail,
                ]);

                $branchCode = $branchDetail->branch_code;

                $location = Location::where('id', $branchDetail->location)->first();

                Log::info('Location searched by ID', [
                    'location_result' => $location,
                ]);

                if (! $location) {

                    Log::info('Location not found by ID, searching by location_name');

                    $location = Location::where('location_name', $branchDetail->location)->first();

                    Log::info('Location searched by name', [
                        'location_result' => $location,
                    ]);
                }

                $locationId = $location ? $location->id : null;

                Log::info('Location ID determined', [
                    'location_id' => $locationId,
                ]);
            } else {

                Log::warning('Branch detail not found', [
                    'branch_code_request' => $request->branch_code,
                ]);
            }

            // Assign data – map first_name + last_name to full_name
            $patient->full_name = trim($request->first_name.' '.$request->last_name);
            $patient->email = $request->email;
            $patient->phone = $request->phone;
            $patient->gender = $request->gender;
            $patient->dob = $request->dob;
            $patient->proposal_number = $request->proposal_number;
            $patient->insurance_company_name = $request->insurance_company_name;
            $patient->insurance_company_email = $request->insurance_company_email;
            $patient->third_party_administrator = $request->third_party_administrator;
            $patient->address = $request->address;
            $patient->pincode = $request->pincode ?? null;
            $patient->mer_type = $request->mer_type ?? null;
            $patient->preferred_language = $request->preferred_language ?? null;
            $patient->health_problems = $request->health_problems ?? null;
            $patient->customer_profile = $request->customer_profile ?? null;
            $patient->providedate = $request->providedate ?? null;
            $patient->sum_assured = $request->sum_assured ?? null;
            $patient->case_registration_datetime = $request->case_registration_datetime ?? null;
            $patient->division_code = $request->division_code ?? null;

            Log::info('Patient data assigned', [
                'patient_data' => $patient->toArray(),
            ]);

            // Fixed / derived values (corrected types)
            $patient->status = 'Unassigned';
            $patient->zonal_Code = 7;
            $patient->location_id = $locationId;
            $patient->branch = $branchCode;
            $patient->is_active = true;
            $patient->created_by = $userEmail;
            $patient->updated_by = $userEmail;

            Log::info('Derived values assigned', [
                'status' => $patient->status,
                'zonal_Code' => $patient->zonal_Code,
                'location_id' => $patient->location_id,
                'branch' => $patient->branch,
                'is_active' => $patient->is_active,
            ]);

            $patient->save();

            Log::info('Patient saved successfully', [
                'patient_id' => $patient->id,
            ]);

            // Send email/SMS only for new patients
            if (! $request->filled('id')) {

                Log::info('Preparing email and SMS for new patient');

                $encryptedProposal = Crypt::encrypt($patient->proposal_number);

                Log::info('Proposal encrypted', [
                    'encrypted_proposal' => $encryptedProposal,
                ]);

                $availabilityLink = env('APP_URL')."/avalableform/{$encryptedProposal}";

                Log::info('Availability link generated', [
                    'availability_link' => $availabilityLink,
                ]);

                // Email via MSG91
                Log::info('Sending email via MSG91');

                $emailResponse = Http::withHeaders([
                    'accept' => 'application/json',
                    'authkey' => '400026Aum41tS2Xqb68664708P1',
                    'content-type' => 'application/json',
                ])->post('https://control.msg91.com/api/v5/email/send', [
                    'recipients' => [[
                        'to' => [[
                            'name' => $patient->full_name,
                            'email' => $patient->email,
                        ]],
                        'cc' => [[
                            'name' => 'Honest Health Care',
                            'email' => 'honesthealthcare3@gmail.com',
                        ]],
                        'variables' => [
                            'VAR1' => $patient->full_name,
                            'VAR2' => $patient->email,
                            'VAR3' => $patient->address,
                            'VAR4' => $availabilityLink,
                        ],
                    ]],
                    'from' => [
                        'name' => 'Honest Health Care',
                        'email' => 'honesthealthcare@email.whizzactsolutions.com',
                    ],
                    'domain' => 'email.whizzactsolutions.com',
                    'reply_to' => [['email' => 'honesthealthcare3@gmail.com']],
                    'template_id' => 'honest_patient_availability_request',
                ]);

                Log::info('Email API response', [
                    'status' => $emailResponse->status(),
                    'response' => $emailResponse->body(),
                ]);

                // SMS via MSG91 Flow
                Log::info('Sending SMS via MSG91');

                $smsResponse = Http::withHeaders([
                    'authkey' => '447742AMCfHYvVexw68079d89P1',
                    'Content-Type' => 'application/json',
                ])->post('https://control.msg91.com/api/v5/flow', [
                    'template_id' => '69e899ce90138080c00a4ab2',
                    'short_url' => 1,
                    'recipients' => [[
                        'mobiles' => '91'.$patient->phone,
                        'var1' => $patient->full_name,
                        'var2' => $availabilityLink,
                    ]],
                ]);

                Log::info('SMS API response', [
                    'status' => $smsResponse->status(),
                    'response' => $smsResponse->body(),
                ]);
            } else {

                Log::info('Existing patient updated, email/SMS skipped');
            }

            Log::info('storepatient function completed successfully');

            return redirect()->route('customers')->with('success', 'Patient saved successfully!');
        } catch (\Exception $e) {

            Log::error('Error in storepatient function', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
