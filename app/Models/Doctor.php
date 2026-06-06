<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'preferred_language',
        'phone',
        'gender',
        'image',
        'reference_number',
        'education',
        'signature',
        'stamp',
        'degree',
        'identity_proof',
        'license',
        'available_days',
        'offline_fees',
        'online_fees',
        'department',
        'is_active',
        'year_of_experince',
        'associated_hospitals',
        'created_by',
        'updated_by',
        'zoom_email',
        'zoom_account_id',
        'zoom_client_id',
        'zoom_client_secret',
        'zoom_sdk_client_id',
        'zoom_sdk_client_secret',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'available_days' => 'array',
        'offline_fees' => 'float',
        'online_fees' => 'float',
    ];

    protected $table = 'doctors';

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
