<?php

namespace App\Models\Maintenance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentType extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'codequipment_type';
    protected $table      = 'maintenance.equipment_types';
    protected $fillable   = ['name'];
}