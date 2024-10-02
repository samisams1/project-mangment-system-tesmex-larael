<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labor extends Model
{
    protected $fillable = [
        'labor_type_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'hire_date',
        'status',
        'skills',
        'assigned_project',
    ];
    public function laborType()
    {
        return $this->belongsTo(LaborType::class);
    }
}
