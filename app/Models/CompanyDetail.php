<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    protected $table = 'company_detail';

    protected $fillable = [
        'company_name',
        'company_code',
        'email',
        'phone',
        'address',
        'state',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function branches()
    {
        return $this->hasMany(BranchDetail::class,'company_id');
    }

    public function divisions()
    {
        return $this->hasMany(Division::class,'company_id');
    }
}