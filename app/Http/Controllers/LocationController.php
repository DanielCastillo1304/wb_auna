<?php

namespace App\Http\Controllers;

use App\Models\maintenance\Location;
use Illuminate\Validation\Rule;

class LocationController extends CrudController
{
    protected string $model      = Location::class;
    protected string $view       = 'location';
    protected string $primaryKey = 'codlocation';

    public function __construct()
    {
        $this->extend = [
            'title'       => 'Nuestras Sedes',
            'title_form'  => 'Nuestras sedes',
            'controller'  => 'location',
            'totalRecord' => Location::count(),
        ];
    }

    protected function rules($id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:250',
                Rule::unique('pgsql.maintenance.locations', 'name')
                    ->ignore($id, 'codlocation')
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    protected function searchFields(): array
    {
        return ['name'];
    }
}
