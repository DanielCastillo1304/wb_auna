<?php

namespace App\Models\maintenance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiskCapacity extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance.disk_capacities';
    protected $primaryKey = 'coddisk_capacity';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'capacity',
        'disk_type'
    ];
}
