<?php

namespace App\Http\Controllers;
use App\Services\NotificationService;

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
            'pickup_date' => 'required|date|after:now',
            'client_address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'items' => 'required|array',
            'items.*' => 'required|integer|min:0|max:20' // Limite à 20 articles par type
        ], [
            'pickup_date.after' => 'La date de récupération doit être future.',
            'items.required' => 'Veuillez sélectionner au moins un article.',
            'items.*.min' => 'La quantité ne peut pas être négative.',
            'items.*.max' => 'La quantité ne peut pas dépasser 20 articles par type.'
        ]);

        // Vérifier qu'au moins un article a une quantité > 0
        $hasItems = false;
        foreach ($validated['items'] as $quantity) {
            if ($quantity > 0) {
                $hasItems = true;
                break;
            }
        }

        if (!$hasItems) {
            return back()->withErrors(['items' => 'Veuillez sélectionner au moins un article.'])->withInput();
        }

        // Calculer le montant total
        $pressing = Pressing::find($validated['pressing_id']);
        $prices = json_decode($pressing->prices, true);
        $totalAmount = 0;

        foreach ($validated['items'] as $itemType => $quantity) {
            if ($quantity > 0 && isset($prices[$itemType])) {
                $totalAmount += $quantity * $prices[$itemType];
            }
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

        // Notifier le propriétaire du pressing
        NotificationService::notifyNewOrder($order);

        return redirect()->route('dashboard')->with('success', 'Commande créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Vérifier que l'utilisateur a le droit de voir cette commande
        $user = Auth::user();

        if ($user->type === 'client' && $order->client_id !== $user->id) {
            abort(403, 'Accès non autorisé à cette commande.');
        }

        if ($user->type === 'owner') {
            $pressing = Pressing::where('owner_id', $user->id)->first();
            if (!$pressing || $order->pressing_id !== $pressing->id) {
                abort(403, 'Accès non autorisé à cette commande.');
            }
        }

        // Charger les relations
        $order->load('client', 'pressing.owner', 'items');

        // Décoder les dates
        $order->pickup_date = \Carbon\Carbon::parse($order->pickup_date);
        if ($order->confirmed_pickup_date) {
            $order->confirmed_pickup_date = \Carbon\Carbon::parse($order->confirmed_pickup_date);
        }

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();

        // Seul le propriétaire du pressing peut modifier le statut
        if ($user->type !== 'owner') {
            abort(403);
        }

        $pressing = Pressing::where('owner_id', $user->id)->first();
        if (!$pressing || $order->pressing_id !== $pressing->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,delivered'
        ]);

        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('orders.show', $order)->with('success', 'Statut mis à jour avec succès!');
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
