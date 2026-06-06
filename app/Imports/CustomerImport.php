<?php

namespace App\Imports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CustomerImport implements ToModel, WithHeadingRow
{
    protected $totalRows = 0;
    protected $processedRows = 0;
    protected $successRows = 0;
    protected $failedRows = 0;
    protected $failures = [];

    // Helper: convert empty string to null for integer fields
    private function toNullableInt($value)
    {
        if ($value === null || $value === '') return null;

        $trimmed = trim($value);

        return is_numeric($trimmed) ? (int)$trimmed : null;
    }

    // Helper: convert empty string to null for pincode (must be 6 digits)
    private function toNullablePincode($value)
    {
        if ($value === null || $value === '') return null;

        $trimmed = trim($value);

        return preg_match('/^\d{6}$/', $trimmed) ? (int)$trimmed : null;
    }

    public function model(array $row)
    {
        $this->processedRows++;

        Log::info('Processing bulk upload row', [
            'row_number' => $this->processedRows,
            'row_data' => $row
        ]);

        $errors = [];

        // Proposal Number
        $proposalNo = trim($row['proposal_no'] ?? $row['proposal_number'] ?? '');

        if (empty($proposalNo)) {

            $errors[] = 'Proposal Number is required';

        } elseif (Patient::where('proposal_number', $proposalNo)->exists()) {

            $errors[] = "Proposal Number '{$proposalNo}' already exists";
        }

        // Full Name
        $fullName = trim($row['customers_full_name'] ?? $row['full_name'] ?? '');

        if (empty($fullName)) {

            $errors[] = 'Customer Full Name is required';

        } elseif (strlen($fullName) < 3) {

            $errors[] = 'Customer Full Name must be at least 3 characters';
        }

        if (!empty($errors)) {

            Log::error('Bulk upload validation failed', [
                'row_number' => $this->processedRows,
                'errors' => $errors
            ]);

            $this->failedRows++;

            $this->failures[] = [
                'row' => $this->processedRows + 1,
                'proposal_number' => $proposalNo ?: 'N/A',
                'errors' => $errors
            ];

            return null;
        }

        $this->successRows++;

        $userId = Auth::id();

        try {

            Log::info('Creating patient from bulk upload');

            $patient = new Patient([
                'proposal_number' => $proposalNo,
                'full_name' => $fullName,
                'email' => trim($row['email_id'] ?? $row['email'] ?? null),
                'phone' => trim($row['customers_mobile'] ?? $row['mobile'] ?? null),
                'gender' => trim($row['customers_gender'] ?? $row['gender'] ?? null),
                'preferred_language' => trim($row['prefered_language'] ?? $row['preferred_language'] ?? 'ENGLISH'),
                'dob' => $this->parseDate($row['customer_dob'] ?? null),
                'mer_type' => trim($row['mer_type'] ?? null),
                'customer_profile' => trim($row['customers_profile'] ?? 'NORMAL'),
                'sum_assured' => $this->toNullableInt($row['sum_assured'] ?? null),
                'case_registration_datetime' => $this->parseDateTime($row['case_registration_date_time'] ?? null),
                'address' => trim($row['address'] ?? null),
                'pincode' => $this->toNullablePincode($row['pincode'] ?? null),
                'branch' => trim($row['branch_no'] ?? $row['branch'] ?? null),
                'zonal_code' => trim($row['division_code'] ?? $row['zonal_code'] ?? null),
                'division_Code' => trim($row['division_code'] ?? null),
                'status' => 'unassigned',
                'insurance_company_name' => trim($row['insurance_company_name'] ?? null),
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            Log::info('Patient object created', [
                'patient_data' => $patient->toArray()
            ]);

            $patient->save();

            Log::info('Bulk upload patient saved successfully', [
                'patient_id' => $patient->id,
                'proposal_number' => $patient->proposal_number
            ]);

            // ==========================================
            // SEND EMAIL + SMS
            // ==========================================

            try {

                Log::info('Preparing email and SMS for bulk upload patient');

                $encryptedProposal = Crypt::encrypt($patient->proposal_number);

                Log::info('Proposal encrypted', [
                    'encrypted_proposal' => $encryptedProposal
                ]);

                $availabilityLink = env('APP_URL') . "/avalableform/{$encryptedProposal}";

                Log::info('Availability link generated', [
                    'availability_link' => $availabilityLink
                ]);

                // ==========================================
                // EMAIL API
                // ==========================================

                Log::info('Sending email via MSG91');

                $emailResponse = Http::withHeaders([
                    'accept' => 'application/json',
                    'authkey' => '400026Aum41tS2Xqb68664708P1',
                    'content-type' => 'application/json',
                ])->post('https://control.msg91.com/api/v5/email/send', [

                    'recipients' => [[

                        'to' => [[
                            'name' => $patient->full_name,
                            'email' => $patient->email
                        ]],

                        'cc' => [[
                            'name' => 'Honest Health Care',
                            'email' => 'honesthealthcare3@gmail.com'
                        ]],

                        'variables' => [
                            'VAR1' => $patient->full_name,
                            'VAR2' => $patient->email,
                            'VAR3' => $patient->address,
                            'VAR4' => $availabilityLink,
                        ]
                    ]],

                    'from' => [
                        'name' => 'Honest Health Care',
                        'email' => 'honesthealthcare@email.whizzactsolutions.com'
                    ],

                    'domain' => 'email.whizzactsolutions.com',

                    'reply_to' => [[
                        'email' => 'honesthealthcare3@gmail.com'
                    ]],

                    'template_id' => 'honest_patient_availability_request'
                ]);

                Log::info('Bulk upload email response', [
                    'status' => $emailResponse->status(),
                    'response' => $emailResponse->body()
                ]);

                // ==========================================
                // SMS API
                // ==========================================

                Log::info('Sending SMS via MSG91');

                $smsResponse = Http::withHeaders([
                    'authkey' => '447742AMCfHYvVexw68079d89P1',
                    'Content-Type' => 'application/json',
                ])->post('https://control.msg91.com/api/v5/flow', [

                    'template_id' => '69e899ce90138080c00a4ab2',

                    'short_url' => 1,

                    'recipients' => [[
                        'mobiles' => '91' . $patient->phone,
                        'var1' => $patient->full_name,
                        'var2' => $availabilityLink,
                    ]]
                ]);

                Log::info('Bulk upload SMS response', [
                    'status' => $smsResponse->status(),
                    'response' => $smsResponse->body()
                ]);

            } catch (\Exception $e) {

                Log::error('Bulk upload email/SMS failed', [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            return $patient;

        } catch (\Exception $e) {

            Log::error('Bulk upload patient save failed', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->failedRows++;

            $this->failures[] = [
                'row' => $this->processedRows + 1,
                'proposal_number' => $proposalNo ?: 'N/A',
                'errors' => [$e->getMessage()]
            ];

            return null;
        }
    }

    protected function parseDate($value)
    {
        if (!$value) return null;

        try {

            if (is_numeric($value)) {

                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                )->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');

        } catch (\Exception $e) {

            Log::error('Date parse failed', [
                'value' => $value,
                'message' => $e->getMessage()
            ]);

            return null;
        }
    }

    protected function parseDateTime($value)
    {
        if (!$value) return null;

        try {

            if (is_numeric($value)) {

                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                );
            }

            return Carbon::parse($value);

        } catch (\Exception $e) {

            Log::error('Datetime parse failed', [
                'value' => $value,
                'message' => $e->getMessage()
            ]);

            return null;
        }
    }

    public function getProcessedRows()
    {
        return $this->processedRows;
    }

    public function getSuccessRows()
    {
        return $this->successRows;
    }

    public function getFailedRows()
    {
        return $this->failedRows;
    }

    public function getFailures()
    {
        return $this->failures;
    }

    public function setTotalRows($total)
    {
        $this->totalRows = $total;
    }

    public function getTotalRows()
    {
        return $this->totalRows;
    }
}
