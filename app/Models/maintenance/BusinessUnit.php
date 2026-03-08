<?php

namespace App\Models\Maintenance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessUnit extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance.business_units';
    protected $primaryKey = 'codbusiness_unit';
    protected $fillable = ['name'];
}
