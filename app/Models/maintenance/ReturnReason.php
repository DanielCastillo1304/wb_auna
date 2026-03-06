<?php

namespace App\Models\maintenance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnReason extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance.return_reasons';
    protected $primaryKey = 'codreturn_reason';
    protected $fillable = ['description'];
}
