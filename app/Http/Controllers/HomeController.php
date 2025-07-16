<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;

class HomeController extends Controller
{
    public function index()
    {
        $offres = Offre::latest()->take(3)->get();

        return view('welcome', compact('offres'));
    }
}
