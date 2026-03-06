<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Security\User;
use App\Models\Product;
use App\Models\Param;

class HomeController extends Controller
{
    public $extend = null;

    public function __construct()
    {
        $this->extend = [
            'title' => 'Inicio',
            'controller' => 'home',
        ];
    }
    public function index()
    {
        $data = null;
        $section = 0;
        return view('welcome', array_merge(['data' => $data], ['section' => $section], ['extend' => $this->extend] ));
    }

}
