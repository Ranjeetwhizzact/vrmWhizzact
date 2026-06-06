<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRating extends Model
{
    use HasFactory;
    protected $table = 'meeting_ratings';

    protected $fillable = [
        'appointment_id',
        'rating',
        'review',
    ];
}
