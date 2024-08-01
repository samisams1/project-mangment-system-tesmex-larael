<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'id',
        'item',
        'unit',
        'quantity',
        'rate_with_vat',
        'amount',
        'remark',
        'status',
        'material_type',
        'reorder_quantity',
        'min_quantity',
        'warehouse_id',
        'unit_id'
    ];
}