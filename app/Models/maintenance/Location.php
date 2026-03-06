<?php

namespace App\Models\maintenance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance.locations';
    protected $primaryKey = 'codlocation';
    protected $fillable = ['name',];
}
