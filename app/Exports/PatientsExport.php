<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PatientsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $patients;

    /**
     * Pass filtered patients collection
     */
    public function __construct($patients)
    {
        $this->patients = $patients;
    }

    /**
     * Return collection
     */
    public function collection()
    {
        return $this->patients;
    }

    /**
     * Excel column headings
     */
    public function headings(): array
    {
        return [
            'ID',
            'Proposal Number',
            'Full Name',
            'Phone',
            'Email',
            'Gender',
            'Preferred Language',
            'DOB',
            'MER Type',
            'Customer Profile',
            'Sum Assured',
            'Address',
            'Pincode',
            'Branch',
            'Division Code',
            'Insurance Company',
            'Status',
            'Location',
            'Created At',
        ];
    }

    /**
     * Map database fields to excel columns
     */
    public function map($patient): array
    {
        return [
            $patient->id,
            $patient->proposal_number,
            $patient->full_name,
            $patient->phone,
            $patient->email,
            $patient->gender,
            $patient->preferred_language,
            $patient->dob,
            $patient->mer_type,
            $patient->customer_profile,
            $patient->sum_assured,
            $patient->address,
            $patient->pincode,
            $patient->branch,
            $patient->division_code,
            $patient->insurance_company_name,
            $patient->status,
            optional($patient->location)->name,
            $patient->created_at ? $patient->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
