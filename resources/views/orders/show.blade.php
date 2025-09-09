<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails de la Commande #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Bouton de retour -->
                    <div class="mb-6">
                        <a href="{{ url()->previous() }}"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            ← Retour
                        </a>
                    </div>

                    <!-- Informations générales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Informations Client</h3>
                            <p><strong>Nom:</strong> {{ $order->client->name }}</p>
                            <p><strong>Email:</strong> {{ $order->client->email }}</p>
                            <p><strong>Téléphone:</strong>
                                @if ($order->client->phone)
                                    <a href="tel:{{ $order->client->phone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $order->client->phone }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                            <p><strong>Adresse:</strong> {{ $order->client_address }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Informations Commande</h3>
                            <p><strong>Pressing:</strong> {{ $order->pressing->name }}</p>
                            <p><strong>Statut:</strong>
                                <span
                                    class="px-2 py-1 rounded-full text-xs
                                    @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'in_progress') bg-indigo-100 text-indigo-800
                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                    @elseif($order->status == 'delivered') bg-purple-100 text-purple-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </p>
                            <p><strong>Total:</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    <!-- Détails des articles -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Articles commandés</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Article</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Quantité</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Prix unitaire
                                        </th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-4 py-2 border capitalize">
                                                {{ str_replace('_', ' ', $item->item_type) }}</td>
                                            <td class="px-4 py-2 border">{{ $item->quantity }}</td>
                                            <td class="px-4 py-2 border">
                                                {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                                            <td class="px-4 py-2 border">
                                                {{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }}
                                                FCFA</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 border text-right font-semibold">Total:</td>
                                        <td class="px-4 py-2 border font-semibold">
                                            {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Dates et instructions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Dates</h3>
                            <p><strong>Récupération demandée:</strong> {{ $order->pickup_date->format('d/m/Y H:i') }}
                            </p>
                            @if ($order->confirmed_pickup_date)
                                <p><strong>Récupération confirmée:</strong>
                                    {{ $order->confirmed_pickup_date->format('d/m/Y H:i') }}</p>
                            @endif
                            <p><strong>Commande passée le:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        @if ($order->special_instructions)
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-3">Instructions spéciales</h3>
                                <p class="text-gray-700">{{ $order->special_instructions }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Modification du statut (pour propriétaire) -->
                    @if (Auth::user()->type === 'owner')
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Modifier le statut</h3>

                            <form action="{{ route('orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="flex items-center space-x-4">
                                    <select name="status"
                                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En
                                            attente</option>
                                        <option value="confirmed"
                                            {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                        <option value="in_progress"
                                            {{ $order->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                        <option value="completed"
                                            {{ $order->status == 'completed' ? 'selected' : '' }}>Terminée</option>
                                        <option value="delivered"
                                            {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                    </select>

                                    <button type="submit"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                                        Mettre à jour
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
