<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchDetail extends Model
{
    protected $table = 'branch_detail';

    protected $fillable = [
        'company_id',
        'branch_name',
        'branch_code',
        'email',
        'phone',
        'address',
        'state',
        'location',
        'zonal_code',
        'division_code',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyDetail::class,'company_id');
    }

}