<?php

namespace App\Models\Maintenance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RamCapacity extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance.ram_capacities';
    protected $primaryKey = 'codram_capacity';
    protected $fillable = ['capacity_gb'];
}
