<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'client_id',
        'meeting_id',
        'isDoctor',

        // Header Information
        'branch_code',
        'policy_no',
        'msp_code',
        'exam_datetime',
        'medical_diary_no',
        'mobile_no',
        'mer_done',
        'identity_proof_type',
        'id_proof_no',
        'id_proof_screenshot',

        // Consent Section
        'examiner_consent_name',
        'consent_method',
        'consent_proof',

        // Personal Information
        'full_name',
        'dob',
        'age',
        'gender',
        'height',
        'weight',
        'pulse',
        'bp_systolic_1',
        'bp_diastolic_1',
        'bp_systolic_2',
        'bp_diastolic_2',

        // Question 5a
        'q5a_treatment',
        'q5a_date',
        'q5a_nature',
        'q5a_medicine',
        'q5a_impairment',
        'q5a_unconscious',
        // 'q5a_documents',

        // Question 5b
        'q5b_surgery',
        'q5b_date',
        'q5b_nature',
        'q5b_medicine',
        'q5b_impairment',
        'q5b_unconscious',
        // 'q5b_documents',

        // Question 5c
        'q5c_doctor_visit',
        'q5c_date',
        'q5c_nature',
        'q5c_medicine',
        'q5c_impairment',
        'q5c_unconscious',
        'q5c_documents',

        // Question 6
        'q6_diagnostic_tests',
        'q6_details',
        // 'q6_documents',

        // Question 7
        'q7_covid',
        'q7_details',
        // 'q7_documents',

        // Question 8
        'q8_hypertension_diabetes',
        'q8b_details',
        'q8c_medication',
        'q8e_complications',
        'q8f_endocrine',
        'q8g_weight_change',
        // 'q8_documents',

        // Question 9
        'q9a_heart_history',
        'q9b_cholesterol',
        'q9c_medication',
        'q9d_surgery',
        // 'q9_documents',

        // Question 10
        'q10_kidney',
        'q10_details',
        // 'q10_documents',

        // Question 11
        'q11_liver_respiratory',
        'q11_details',
        // 'q11_documents',

        // Question 12
        'q12_blood_disorders',
        'q12_details',
        // 'q12_documents',

        // Question 13
        'q13_cancer',
        'q13_details',
        // 'q13_documents',

        // Question 14
        'q14_neurological',
        'q14_details',
        // 'q14_documents',

        // Question 15
        'q15_physical_impairment',
        'q15_details',
        // 'q15_documents',

        // Question 16
        'q16_digestive',
        'q16_details',
        // 'q16_documents',

        // Question 17
        'q17a_mental',
        'q17b_details',
        // 'q17_documents',

        // Question 18
        'q18_ent',
        'q18_details',
        // 'q18_documents',

        // Question 19
        'q19_hiv_sti',
        'q19_details',
        // 'q19_documents',

        // Question 20
        'q20_other',
        'q20a_details',
        // 'q20_documents',

        // Female Specific
        'pregnancy_status',
        'pregnancy_complications',
        'gynecological_issues',
        'gynae_documents',

        // Medical Examiner Assessment
        'appears_healthy',
        'healthy_notes',
        'place',
        'exam_date',

        // Declaration
        'declaration_name',
        'life_assured_signature',
        'declaration_proof',
        'certificate_date',
        'examiner_place',
        'examiner_date',
        'examiner_signature',
        'doctor_name',
        'doctor_qualification',
        'doctor_registration_number',
        'doctor_photo',
        'examiner_stamp',

        // Additional Documents
        'additional_documents',

        // Final Declaration
        'final_declaration',
        'pdf_path',

        // Audit fields
        'created_by',
        'updated_by',

        'customer_pincode',
        'customer_address',
        'doctor_pincode',
        'doctor_address',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'dob' => 'date',
        'exam_datetime' => 'datetime',
        'exam_date' => 'date',
        'certificate_date' => 'date',
        'examiner_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'isDoctor' => 'boolean',
        'final_declaration' => 'boolean',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'age' => 'integer',
        'q5a_documents' => 'array',
        'q5b_documents' => 'array',
        'q5c_documents' => 'array',
        'q6_documents' => 'array',
        'q7_documents' => 'array',
        'q8_documents' => 'array',
        'q9_documents' => 'array',
        'q10_documents' => 'array',
        'q11_documents' => 'array',
        'q12_documents' => 'array',
        'q13_documents' => 'array',
        'q14_documents' => 'array',
        'q15_documents' => 'array',
        'q16_documents' => 'array',
        'q17_documents' => 'array',
        'q18_documents' => 'array',
        'q19_documents' => 'array',
        'q20_documents' => 'array',
        'gynae_documents' => 'array',
        'additional_documents' => 'array',
    ];

    // Helper methods (BMI, conditions, scopes) remain unchanged
    public function getBmiAttribute()
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;

            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }

        return null;
    }

    // Get BMI Category
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

    // Check if any medical condition exists
    public function hasAnyMedicalCondition()
    {
        $conditions = [
            $this->q5a_treatment, $this->q5b_surgery, $this->q5c_doctor_visit,
            $this->q6_diagnostic_tests, $this->q7_covid, $this->q8_hypertension_diabetes,
            $this->q9a_heart_history, $this->q10_kidney, $this->q11_liver_respiratory,
            $this->q12_blood_disorders, $this->q13_cancer, $this->q14_neurological,
            $this->q15_physical_impairment, $this->q16_digestive, $this->q17a_mental,
            $this->q18_ent, $this->q19_hiv_sti, $this->q20_other,
        ];

        return in_array('YES', $conditions);
    }

    // Get all YES answers with details
    public function getPositiveAnswers()
    {
        $positive = [];

        if ($this->q5a_treatment === 'YES') {
            $positive['q5a'] = [
                'question' => 'Treatment/Medication',
                'details' => [
                    'date' => $this->q5a_date,
                    'nature' => $this->q5a_nature,
                    'medicine' => $this->q5a_medicine,
                    'impairment' => $this->q5a_impairment,
                    'unconscious' => $this->q5a_unconscious,
                ],
            ];
        }

        if ($this->q5b_surgery === 'YES') {
            $positive['q5b'] = [
                'question' => 'Surgery/Hospitalization',
                'details' => [
                    'date' => $this->q5b_date,
                    'nature' => $this->q5b_nature,
                    'medicine' => $this->q5b_medicine,
                    'impairment' => $this->q5b_impairment,
                    'unconscious' => $this->q5b_unconscious,
                ],
            ];
        }

        if ($this->q5c_doctor_visit === 'YES') {
            $positive['q5c'] = [
                'question' => 'Doctor Visit in last 5 years',
                'details' => [
                    'date' => $this->q5c_date,
                    'nature' => $this->q5c_nature,
                    'medicine' => $this->q5c_medicine,
                    'impairment' => $this->q5c_impairment,
                    'unconscious' => $this->q5c_unconscious,
                ],
            ];
        }

        if ($this->q6_diagnostic_tests === 'YES') {
            $positive['q6'] = [
                'question' => 'Diagnostic Tests',
                'details' => $this->q6_details,
            ];
        }

        if ($this->q7_covid === 'YES') {
            $positive['q7'] = [
                'question' => 'COVID-19',
                'details' => $this->q7_details,
            ];
        }

        if ($this->q8_hypertension_diabetes === 'YES') {
            $positive['q8'] = [
                'question' => 'Hypertension/Diabetes',
                'details' => [
                    'follow_up' => $this->q8b_details,
                    'medication' => $this->q8c_medication,
                    'complications' => $this->q8e_complications,
                    'endocrine' => $this->q8f_endocrine,
                    'weight_change' => $this->q8g_weight_change,
                ],
            ];
        }

        // Add more conditions as needed...

        return $positive;
    }

    // Scopes
    public function scopeDoctorReports($query)
    {
        return $query->where('isDoctor', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('final_declaration', true);
    }

    public function scopeWithMedicalConditions($query)
    {
        return $query->where(function ($q) {
            $q->where('q5a_treatment', 'YES')
                ->orWhere('q5b_surgery', 'YES')
                ->orWhere('q5c_doctor_visit', 'YES')
                ->orWhere('q6_diagnostic_tests', 'YES')
                ->orWhere('q7_covid', 'YES')
                ->orWhere('q8_hypertension_diabetes', 'YES')
                ->orWhere('q9a_heart_history', 'YES')
                ->orWhere('q10_kidney', 'YES')
                ->orWhere('q11_liver_respiratory', 'YES')
                ->orWhere('q12_blood_disorders', 'YES')
                ->orWhere('q13_cancer', 'YES')
                ->orWhere('q14_neurological', 'YES')
                ->orWhere('q15_physical_impairment', 'YES')
                ->orWhere('q16_digestive', 'YES')
                ->orWhere('q17a_mental', 'YES')
                ->orWhere('q18_ent', 'YES')
                ->orWhere('q19_hiv_sti', 'YES')
                ->orWhere('q20_other', 'YES');
        });
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
