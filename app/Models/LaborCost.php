<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaborCost extends Model
{
    protected $fillable = [
        'id',
        'subtask_id',
        'labor_id',
        'unit',
        'qty',
        'rate_with_vat',
        'amount',
        'remark',
    ];

    /**
     * Get the subtask associated with the material cost.
     */
    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }

    /**
     * Get the material associated with the material cost.
     */
    public function Labor()
    {
        return $this->belongsTo(Labor::class);
    }
}