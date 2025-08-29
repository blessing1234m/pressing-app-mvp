<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pressing;
use App\Models\OrderItem;
use App\Models\Order;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Pressing $pressing)
    {
            // Décoder les prix du pressing
    $pressing->prices = json_decode($pressing->prices, true);

    return view('orders.create', compact('pressing'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
        'pressing_id' => 'required|exists:pressings,id',
        'pickup_date' => 'required|date',
        'client_address' => 'required|string|max:500',
        'special_instructions' => 'nullable|string|max:1000',
        'items' => 'required|array',
        'items.*' => 'required|integer|min:0'
    ]);

    // Calculer le montant total
    $pressing = Pressing::find($validated['pressing_id']);
    $prices = json_decode($pressing->prices, true);
    $totalAmount = 0;

    foreach ($validated['items'] as $itemType => $quantity) {
        if ($quantity > 0 && isset($prices[$itemType])) {
            $totalAmount += $quantity * $prices[$itemType];
        }
    }

    if ($totalAmount === 0) {
        return back()->withErrors(['items' => 'Veuillez sélectionner au moins un article.'])->withInput();
    }

    // Créer la commande
    $order = Order::create([
        'client_id' => Auth::id(),
        'pressing_id' => $validated['pressing_id'],
        'total_amount' => $totalAmount,
        'pickup_date' => $validated['pickup_date'],
        'client_address' => $validated['client_address'],
        'special_instructions' => $validated['special_instructions'],
        'status' => 'pending'
    ]);

    // Créer les articles de la commande
    foreach ($validated['items'] as $itemType => $quantity) {
        if ($quantity > 0 && isset($prices[$itemType])) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_type' => $itemType,
                'quantity' => $quantity,
                'unit_price' => $prices[$itemType]
            ]);
        }
    }

    return redirect()->route('dashboard')->with('success', 'Commande créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
