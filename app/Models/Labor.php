<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labor extends Model
{
    protected $fillable = [
        'id',
        'item',
        'unit',
        'quantity',
        'rate_with_vat',
        'amount',
        'remark',
    ];
}
