<?php


// app/Models/Warehouse.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'location',
        'manager',
        'contact_info',
    ];

    /**
     * Get the materials stored in this warehouse.
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Get the equipment stored in this warehouse.
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    /**
     * Get the material costs associated with this warehouse.
     */
    public function materialCosts()
    {
        return $this->hasMany(MaterialCost::class);
    }

    /**
     * Get the equipment costs associated with this warehouse.
     */
    public function equipmentCosts()
    {
        return $this->hasMany(EquipmentCost::class);
    }
}