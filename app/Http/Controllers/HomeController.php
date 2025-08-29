<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Correction: Request avec majuscule
use App\Models\Pressing; // Correction: Backslash au lieu de point

class HomeController extends Controller
{
    public function index()
    {
        $pressings = Pressing::with('owner')->get(); // Correction: :: au lieu de :

        // Décoder les prix JSON pour chaque pressing
        $pressings->each(function (Pressing $pressing) {
            $pressing->prices = json_decode($pressing->prices, true);
        });

        return view('home', compact('pressings')); // Correction: Ajout du ;
    }
}
