<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCost extends Model
{
    protected $fillable = [
        'id',
        'subtask_id',
        'equipment_id',
        'unit',
        'qty',
        'rate_with_vat',
        'amount',
        'remark',
    ];

    /**
     * Get the subtask associated with the equipment cost.
     */
    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }

    /**
     * Get the equipment associated with the equipment cost.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}