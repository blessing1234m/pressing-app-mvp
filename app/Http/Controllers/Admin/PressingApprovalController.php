<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pressing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PressingApprovalController extends Controller
{
    public function index()
    {
        $pendingPressings = Pressing::with('owner')->where('is_approved', false)->get();
        $approvedPressings = Pressing::with('owner')->where('is_approved', true)->get();

        return view('admin.pressings.index', compact('pendingPressings', 'approvedPressings'));
    }

    public function show(Pressing $pressing)
    {
        $pressing->load('owner');
        $prices = $pressing->prices;

        return view('admin.pressings.show', compact('pressing', 'prices'));
    }

    public function approve(Pressing $pressing)
    {
        $pressing->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        return redirect()->route('admin.pressings.index')
            ->with('success', 'Pressing approuvé avec succès !');
    }

    public function reject(Pressing $pressing)
    {
        $pressing->delete();

        return redirect()->route('admin.pressings.index')
            ->with('success', 'Pressing refusé et supprimé.');
    }

      /**
     * Affiche la page de confirmation de suppression
     */
    public function confirmDelete(Pressing $pressing)
    {
        $pressing->loadCount('orders');
        return view('admin.pressings.confirm-delete', compact('pressing'));
    }

    /**
     * Supprime définitivement un pressing
     */
    public function destroy(Pressing $pressing)
    {
        try {
            // Sauvegarde le nom pour le message de confirmation
            $pressingName = $pressing->name;

            // Supprime le pressing (les relations seront gérées par les events)
            $pressing->delete();

            return redirect()->route('admin.pressings.index')
                ->with('success', "Le pressing '{$pressingName}' a été supprimé définitivement.");

        } catch (\Exception $e) {
            Log::error('Erreur suppression pressing: ' . $e->getMessage());

            return redirect()->route('admin.pressings.index')
                ->with('error', 'Erreur lors de la suppression du pressing.');
        }
    }
}
