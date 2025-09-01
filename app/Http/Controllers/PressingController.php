<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pressing;
use Illuminate\Validation\Rule;
use App\Notifications\NewPressingForApproval;
use App\Models\User;


class PressingController extends Controller
{
    public function create()
    {
        // Vérifier que l'utilisateur est un propriétaire
        if (Auth::user()->type !== 'owner') {
            abort(403, 'Seuls les propriétaires peuvent créer un pressing.');
        }

        // Vérifier que l'utilisateur n'a pas déjà un pressing
        $existingPressing = Pressing::where('owner_id', Auth::id())->first();
        if ($existingPressing) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà un pressing. Vous ne pouvez en créer qu\'un seul.');
        }

        return view('pressings.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->type !== 'owner') {
            abort(403);
        }

        // Vérifier que l'utilisateur n'a pas déjà un pressing
        $existingPressing = Pressing::where('owner_id', Auth::id())->first();
        if ($existingPressing) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà un pressing.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:pressings,name',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'prices' => 'required|array',
            'prices.*' => 'required|integer|min:0'
        ], [
            'name.unique' => 'Un pressing avec ce nom existe déjà.',
            'prices.required' => 'Veuillez définir au moins un tarif.',
            'prices.*.min' => 'Les prix ne peuvent pas être négatifs.'
        ]);

        // Vérifier qu'au moins un prix est > 0
        $hasValidPrices = false;
        foreach ($validated['prices'] as $price) {
            if ($price > 0) {
                $hasValidPrices = true;
                break;
            }
        }

        if (!$hasValidPrices) {
            return back()->withErrors(['prices' => 'Veuillez définir au moins un prix supérieur à 0.'])->withInput();
        }

        // Créer le pressing
        $pressing = Pressing::create([
            'owner_id' => Auth::id(),
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'description' => $validated['description'],
            'prices' => ($validated['prices'])
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Votre pressing a été créé avec succès !');

            // Créer le pressing NON approuvé
    $pressing = Pressing::create([
        'owner_id' => Auth::id(),
        'name' => $validated['name'],
        'address' => $validated['address'],
        'phone' => $validated['phone'],
        'description' => $validated['description'],
        'prices' => json_encode($validated['prices']),
        'is_approved' => false // IMPORTANT: false par défaut
    ]);

    // Notifier tous les administrateurs
    $admins = User::where('type', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new NewPressingForApproval($pressing));
    }


    // Nettoie et formatte les prix
    $cleanedPrices = [];
    foreach ($validated['prices'] as $item => $price) {
        // Convertit en integer et nettoie le nom de l'article
        $cleanedItem = strtolower(trim($item));
        $cleanedPrices[$cleanedItem] = (int) $price;
    }

    // Créer le pressing NON approuvé
    $pressing = Pressing::create([
        'owner_id' => Auth::id(),
        'name' => $validated['name'],
        'address' => $validated['address'],
        'phone' => $validated['phone'],
        'description' => $validated['description'],
        'prices' => json_encode($cleanedPrices), // JSON bien formaté
        'is_approved' => false
    ]);



    return redirect()->route('dashboard')
        ->with('success', 'Votre pressing a été créé ! Il sera visible après validation par l\'administrateur.');
    }

    public function edit(Pressing $pressing)
    {
        // Vérifier que l'utilisateur est le propriétaire du pressing
        if (Auth::id() !== $pressing->owner_id) {
            abort(403);
        }

        $prices = json_decode($pressing->prices, true);

        return view('pressings.edit', compact('pressing', 'prices'));
    }

    public function update(Request $request, Pressing $pressing)
    {
        // Vérifier que l'utilisateur est le propriétaire du pressing
        if (Auth::id() !== $pressing->owner_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pressings')->ignore($pressing->id)
            ],
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'prices' => 'required|array',
            'prices.*' => 'required|integer|min:0'
        ]);

        // Mettre à jour le pressing
        $pressing->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'description' => $validated['description'],
            'prices' => json_encode($validated['prices'])
        ]);

        return redirect()->route('dashboard.pricing')
            ->with('success', 'Votre pressing a été mis à jour avec succès !');
    }
}
