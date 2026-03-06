<?php

namespace App\Models\Security;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

    protected $table        = "security.user";
    protected $primaryKey   = "coduser";
    protected $fillable     = ['username', 'password'];
    protected $hidden       = ['updated_at'];

}
