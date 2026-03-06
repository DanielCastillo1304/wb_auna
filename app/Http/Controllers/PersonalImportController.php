<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PersonalImport;

class PersonalImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new PersonalImport, $request->file('file'));

        return response()->json([
            'message' => 'Datos importados correctamente'
        ]);
    }
}