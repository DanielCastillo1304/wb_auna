<?php

namespace App\Http\Controllers;

use App\Models\Security\User;
use Illuminate\Validation\Rule;

class UserController extends CrudController
{
    protected string $model      = User::class;
    protected string $view       = 'user';
    protected string $primaryKey = 'coduser';

    public function __construct()
    {
        $this->extend = [
            'title'       => 'Usuarios',
            'title_form'  => 'User',
            'controller'  => 'user',
            'totalRecord' => User::count(),
        ];
    }

    protected function rules($id = null): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:250',
                Rule::unique('pgsql.security.user', 'username')
                    ->ignore($id, 'coduser')
                    ->whereNull('deleted_at'),
            ],
            'passowrd' => [
                'required',
                'string',
                'max:250',
            ],
        ];
    }

    protected function searchFields(): array
    {
        return ['username'];
    }
}
