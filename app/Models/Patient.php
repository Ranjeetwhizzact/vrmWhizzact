<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $table = 'patients';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'gender',
        'preferred_language',
        'dob',
        'mer_type',
        'proposal_number',
        'customer_profile',
        'sum_assured',
        'case_registration_datetime',
        'third_party_administrator',
        'insurance_company_name',
        'insurance_company_email',
        'address',
        'pincode',                     // ✅ added
        'health_problems',
        'documents',
        'providedate',
        'status',
        'is_active',
        'created_by',
        'updated_by',
        'branch',
        'location_id',
        'zonal_Code',
        'division_Code',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'dob'                    => 'date',
        'case_registration_date' => 'datetime',
        'providedate'            => 'date',
        'sum_assured'            => 'integer',
        'location_id'            => 'integer',
        'zonal_Code'             => 'integer',
        'is_active'              => 'boolean',   // ✅ cast to boolean
    ];

    // Relationships
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function branchDetail()
    {
        return $this->belongsTo(BranchDetail::class, 'branch', 'branch_code');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_code', 'division_code');
    }
}
