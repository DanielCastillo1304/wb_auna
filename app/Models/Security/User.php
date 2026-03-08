<?php

namespace App\Models\Security;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

use App\Models\Security\ProfilePermission;
use App\Models\Security\Permission;
use App\Models\Security\Profile;
use App\Models\Person;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table      = "security.user";
    protected $primaryKey = "coduser";

    protected $fillable = [
        'codprofile',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'deleted_at'
    ];



    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'codprofile', 'codprofile');
    }

    public function permissions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Permission::class,
            ProfilePermission::class,
            'codprofile',
            'codpermission',
            'codprofile',
            'codpermission'
        );
    }
}
