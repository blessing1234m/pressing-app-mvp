<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de Bord - Client
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Bienvenue, {{ Auth::user()->name }} !</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-8">
                        <a href="{{ route('home') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Voir tous les pressings
                        </a>
                    </div>

                    <h4 class="text-lg font-medium mb-4">Vos Commandes Récentes</h4>

                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border">Pressing</th>
                                        <th class="px-4 py-2 border">Date de récupération</th>
                                        <th class="px-4 py-2 border">Montant</th>
                                        <th class="px-4 py-2 border">Statut</th>
                                        <th class="px-4 py-2 border">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $order->pressing->name }}</td>
                                            <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-2 border">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                            <td class="px-4 py-2 border">
                                                <span class="px-2 py-1 rounded-full text-xs
                                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'in_progress') bg-indigo-100 text-indigo-800
                                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                    @elseif($order->status == 'delivered') bg-purple-100 text-purple-800
                                                    @endif">
                                                    @if($order->status == 'pending') En attente
                                                    @elseif($order->status == 'confirmed') Confirmée
                                                    @elseif($order->status == 'in_progress') En cours
                                                    @elseif($order->status == 'completed') Terminée
                                                    @elseif($order->status == 'delivered') Livrée
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 border">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Détails</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-100 p-4 rounded-lg text-center">
                            <p class="text-gray-600">Vous n'avez pas encore de commandes.</p>
                            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-800 mt-2 inline-block">
                                Passer votre première commande
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
