<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'division';

    protected $fillable = [
        'company_id',
        'division_name',
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