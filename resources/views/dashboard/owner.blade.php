@if (!$pressing)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-yellow-800">Configuration requise</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Vous devez créer votre pressing pour commencer à recevoir des commandes.</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('pressings.create') }}"
                        class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                        Créer mon Pressing
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tableau de Bord - {{ $pressing->name }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Bienvenue, {{ Auth::user()->name }} !</h3>

                        <div class="mb-6">
                            <div class="flex space-x-4">
                                <a href="{{ route('dashboard.pricing') }}"
                                    class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                                    Modifier Mes Prix
                                </a>
                                <!-- Autres boutons... -->
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <div class="bg-blue-100 p-4 rounded-lg">
                                <h4 class="font-semibold">Commandes Total</h4>
                                <p class="text-2xl">{{ $orders->count() }}</p>
                            </div>
                            <div class="bg-green-100 p-4 rounded-lg">
                                <h4 class="font-semibold">En Cours</h4>
                                <p class="text-2xl">{{ $orders->where('status', 'in_progress')->count() }}</p>
                            </div>
                            <div class="bg-yellow-100 p-4 rounded-lg">
                                <h4 class="font-semibold">En Attente</h4>
                                <p class="text-2xl">{{ $orders->where('status', 'pending')->count() }}</p>
                            </div>
                        </div>

                        <h4 class="text-lg font-medium mb-4">Commandes Récentes</h4>

                        @if ($orders->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 border">Client</th>
                                            <th class="px-4 py-2 border">Date</th>
                                            <th class="px-4 py-2 border">Montant</th>
                                            <th class="px-4 py-2 border">Statut</th>
                                            <th class="px-4 py-2 border">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="px-4 py-2 border">{{ $order->client->name }}</td>
                                                <td class="px-4 py-2 border">
                                                    {{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="px-4 py-2 border">
                                                    {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                                <td class="px-4 py-2 border">
                                                    <span
                                                        class="px-2 py-1 rounded-full text-xs
                                                    @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'in_progress') bg-indigo-100 text-indigo-800
                                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                    @elseif($order->status == 'delivered') bg-purple-100 text-purple-800 @endif">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 border">
                                                    <a href="{{ route('orders.show', $order) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">Gérer</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-100 p-4 rounded-lg text-center">
                                <p class="text-gray-600">Aucune commande pour le moment.</p>
                            </div>
                        @endif

                        {{-- <h4 class="text-lg font-medium mb-4 mt-8">Liste des Prix</h4>
                    <form action="{{ route('dashboard.pricing.update') }}" method="POST" class="mb-8">
                        @csrf
                        <div class="space-y-4">
                            @foreach ($prices as $item => $price)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 capitalize mb-1">
                                            {{ str_replace('_', ' ', $item) }}
                                        </label>
                                        <p class="text-sm text-gray-500">Prix actuel: {{ number_format($price, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div class="w-32">
                                        <div class="flex rounded-md shadow-sm">
                                            <input type="number"
                                                   name="prices[{{ $item }}]"
                                                   value="{{ $price }}"
                                                   min="0"
                                                   step="100"
                                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form> --}}

                        <!-- Formulaire d'ajout d'un nouveau type de vêtement -->
                        <div class="mt-8 p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-semibold mb-3">Ajouter un nouveau type de vêtement</h4>
                            <div class="flex space-x-2">
                                <input type="text" placeholder="Nom du vêtement"
                                    class="flex-1 border border-gray-300 rounded-md px-3 py-2">
                                <input type="number" placeholder="Prix" min="0"
                                    class="w-24 border border-gray-300 rounded-md px-3 py-2">
                                <button type="button"
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif
