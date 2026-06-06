<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRecording extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'file_name',
        'file_path',
        'file_url',
    ];
}
