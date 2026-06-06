<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAvailability extends Model
{
    use HasFactory;

    protected $table = 'customer_availabilities';

    protected $fillable = [
        'patient_id',
        'proposal_number',
        'available_date',
        'start_time',
        'end_time',
    ];

    // Relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
