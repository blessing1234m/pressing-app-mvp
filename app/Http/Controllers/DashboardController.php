<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Pressing;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->type) {
            case 'client':
                return $this->clientDashboard($user);
            case 'owner':
                return $this->ownerDashboard($user);
            case 'admin':
                return $this->adminDashboard($user);
            default:
                return view('dashboard');
        }
    }

    private function clientDashboard($user)
    {
        $orders = Order::with('pressing')
            ->where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.client', compact('orders'));
    }

private function ownerDashboard($user)
{
    $pressing = Pressing::where('owner_id', $user->id)->first();

    if (!$pressing) {
        return view('dashboard.owner-setup'); // Vue pour les propriétaires sans pressing
    }

    $orders = Order::with('client')
                  ->where('pressing_id', $pressing->id)
                  ->orderBy('created_at', 'desc')
                  ->get();

    return view('dashboard.owner', compact('orders', 'pressing'));
}

    private function adminDashboard($user)
    {
        $pressings = Pressing::withCount('orders')->get();
        $totalOrders = Order::count();
        $totalClients = \App\Models\User::where('type', 'client')->count();

        return view('dashboard.admin', compact('pressings', 'totalOrders', 'totalClients'));
    }
    public function users()
    {
        if (Auth::user()->type !== 'admin') {
            abort(403);
        }

        // Charger les utilisateurs avec le nombre de commandes et le pressing
        $users = User::withCount(['orders' => function ($query) {
            $query->where('client_id', '!=', null);
        }])
            ->with('pressing') // Charger aussi la relation pressing si elle existe
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', compact('users'));
    }

    // ... autres méthodes ...

    public function editUser(User $user)
    {
        if (Auth::user()->type !== 'admin') {
            abort(403);
        }

        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        if (Auth::user()->type !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'type' => 'required|in:client,owner,admin',
            'password' => 'nullable|min:8|confirmed'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->type = $validated['type'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès!');
    }

    public function deleteUser(User $user)
    {
        if (Auth::user()->type !== 'admin') {
            abort(403);
        }

        // Empêcher l'auto-suppression
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'Vous ne pouvez pas supprimer votre propre compte!');
        }

        // Vérifier si l'utilisateur a des relations (commandes, pressing, etc.)
        if ($user->orders()->count() > 0 || $user->pressing()->exists()) {
            return redirect()->route('admin.users')->with('error', 'Impossible de supprimer cet utilisateur car il a des données associées.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès!');
    }

    public function editPricing()
    {
        $user = Auth::user();

        if ($user->type !== 'owner') {
            abort(403);
        }

        $pressing = Pressing::where('owner_id', $user->id)->first();

        if (!$pressing) {
            return redirect()->route('dashboard')->with('error', 'Aucun pressing associé à votre compte.');
        }

        // Décoder les prix JSON en tableau PHP
        $prices = $pressing->prices;

        // Vérifier si $prices est bien un tableau
        if (!is_array($prices)) {
            $prices = [];
        }

        return view('dashboard.pricing', compact('pressing', 'prices'));
    }

    public function updatePricing(Request $request)
    {
        $user = Auth::user();

        if ($user->type !== 'owner') {
            abort(403);
        }

        $pressing = Pressing::where('owner_id', $user->id)->first();

        if (!$pressing) {
            return redirect()->route('dashboard')->with('error', 'Aucun pressing associé à votre compte.');
        }

        $validated = $request->validate([
            'prices' => 'required|array',
            'prices.*' => 'required|integer|min:0',
            'new_item_name' => 'nullable|string|max:255',
            'new_item_price' => 'nullable|integer|min:0|required_with:new_item_name'
        ]);

        // Récupérer les prix actuels
        $currentPrices = Pressing::validatePrices($pressing->prices);

        // Mettre à jour les prix existants
        foreach ($validated['prices'] as $item => $price) {
            if (array_key_exists($item, $currentPrices)) {
                $currentPrices[$item] = (int) $price;
            }
        }

        // Ajouter le nouvel item si fourni
        if (!empty($validated['new_item_name']) && !empty($validated['new_item_price'])) {
            $newItemName = strtolower(trim($validated['new_item_name']));
            $newItemName = preg_replace('/[^a-z0-9_]/', '_', $newItemName); // Nettoyer le nom

            if (!array_key_exists($newItemName, $currentPrices)) {
                $currentPrices[$newItemName] = (int) $validated['new_item_price'];
            }
        }

        // Mettre à jour les prix
        $pressing->prices = $currentPrices;
        $pressing->save();

        $message = 'Prix mis à jour avec succès!';
        if (!empty($validated['new_item_name']) && !empty($validated['new_item_price'])) {
            $message .= ' Nouveau type de vêtement ajouté.';
        }

        return redirect()->route('dashboard.pricing')->with('success', $message);
    }
}
