<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zonal extends Model
{
    protected $table = 'zonal';

    protected $fillable = [
        'zonal_name',
        'zonal_code',
        'is_active',
        'created_by',
        'updated_by'
    ];
}