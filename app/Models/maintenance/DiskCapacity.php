<?php

namespace App\Models\Maintenance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiskCapacity extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance.disk_capacities';
    protected $primaryKey = 'coddisk_capacity';
    protected $fillable = ['capacity', 'disk_type'];
}
