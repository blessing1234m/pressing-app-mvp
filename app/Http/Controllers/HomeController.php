<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pressing;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
public function index()
{
    try {
        // N'afficher que les pressings approuvés
        $pressings = Pressing::with('owner')
            ->where('is_approved', true)
            ->get();

        // Plus besoin de décoder manuellement, le modèle s'en charge
        return view('home', compact('pressings'));

    } catch (\Exception $e) {
        Log::error('Error in HomeController: ' . $e->getMessage());

        // En cas d'erreur, retourner une collection vide
        $pressings = collect([]);
        return view('home', compact('pressings'));
    }
}
}
