<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitanteController extends Controller
{
    public function index(){
        return view('visitante');
    }
}
