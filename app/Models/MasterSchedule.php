<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'unique_id',
        'text',
        'start_date',
        'duration',
        'progress',
        'type',
        'parent', // This will link tasks to their parent project
    ];
}
