<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'doctor_id',
        'start_time',
        'end_time',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'status',
        'appointment_type',
        'doctor_access_key',
        'zoom_passcode',
        'customer_latitude',
        'customer_longitude',
        'doctor_latitude',
        'doctor_longitude',
        'customer_address',
        'doctor_address',
        'customer_pincode',
        'doctor_pincode',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationship with Client
    public function client()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relationship with Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
