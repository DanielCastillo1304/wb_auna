<?php

namespace App\Http\Controllers;

use App\Models\maintenance\ReturnReason;
use Illuminate\Validation\Rule;

class ReturnReasonController extends CrudController
{
    protected string $model      = ReturnReason::class;
    protected string $view       = 'return_reason';
    protected string $primaryKey = 'codreturn_reason';

    public function __construct()
    {
        $this->extend = [
            'title'       => 'Motivo de devolución',
            'title_form'  => 'Motivo de evolución',
            'controller'  => 'return_reason',
            'totalRecord' => ReturnReason::count(),
        ];
    }

    protected function rules($id = null): array
    {
        return [
            'description' => [
                'required',
                'string',
                'max:200'
            ],
        ];
    }

    protected function searchFields(): array
    {
        return ['description'];
    }
}
