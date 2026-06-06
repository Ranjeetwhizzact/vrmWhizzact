<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'location';

    protected $fillable = [
        'location_name',
        'location_code',
        'is_active',
        'created_by',
        'updated_by'
    ];
}