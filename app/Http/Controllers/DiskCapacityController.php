<?php

namespace App\Http\Controllers;

use App\Models\maintenance\DiskCapacity;
use Illuminate\Validation\Rule;

class DiskCapacityController extends CrudController
{
    protected string $model      = DiskCapacity::class;
    protected string $view       = 'disk_capacity';
    protected string $primaryKey = 'coddisk_capacity';

    public function __construct()
    {
        $this->extend = [
            'title'       => 'Capacidad de disco',
            'title_form'  => 'Capacidad de disco',
            'controller'  => 'disk_capacity',
            'totalRecord' => DiskCapacity::count(),
        ];
    }

    protected function rules($id = null): array
    {
        return [
            'capacity' => [
                'required',
                'string',
                'max:20'
            ],
            'disk_type' => [
                'required',
                'string',
                'max:20'
            ],
        ];
    }

    protected function searchFields(): array
    {
        return ['capacity','disk_type'];
    }
}
