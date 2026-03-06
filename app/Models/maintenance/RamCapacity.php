<?php

namespace App\Models\maintenance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RamCapacity extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance.ram_capacities';
    protected $primaryKey = 'codram_capacity';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'capacity_gb'
    ];
}
