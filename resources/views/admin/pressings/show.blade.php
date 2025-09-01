<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails du Pressing - {{ $pressing->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Boutons de navigation -->
            <div class="mb-6">
                <a href="{{ route('admin.pressings.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                    ← Retour à la liste
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- En-tête avec statut -->
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $pressing->name }}</h1>
                            <p class="text-gray-600">Créé le {{ $pressing->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($pressing->is_approved) bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                @if($pressing->is_approved)
                                    ✅ Approuvé
                                @else
                                    ⏳ En attente
                                @endif
                            </span>
                            @if($pressing->is_approved)
                                <p class="text-xs text-gray-500 mt-1">
                                    Approuvé le {{ $pressing->approved_at->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Informations générales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800 mb-3">Informations du Pressing</h3>
                            <div class="space-y-2">
                                <p><strong class="text-gray-700">Propriétaire:</strong> {{ $pressing->owner->name }}</p>
                                <p><strong class="text-gray-700">Email:</strong> {{ $pressing->owner->email }}</p>
                                <p><strong class="text-gray-700">Téléphone:</strong> {{ $pressing->phone }}</p>
                                <p><strong class="text-gray-700">Adresse:</strong> {{ $pressing->address }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Statistiques</h3>
                            <div class="space-y-2">
                                <p><strong class="text-gray-700">Commandes totales:</strong> {{ $pressing->orders->count() }}</p>
                                <p><strong class="text-gray-700">Date de création:</strong> {{ $pressing->created_at->format('d/m/Y') }}</p>
                                @if($pressing->is_approved)
                                    <p><strong class="text-gray-700">Approuvé par:</strong> {{ $pressing->approvedBy->name ?? 'Admin' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($pressing->description)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Description</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $pressing->description }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Tarifs -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tarifs (FCFA)</h3>

                        @if(count($prices) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase border-b">Article</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase border-b">Prix Unitaire</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase border-b">Prix Formaté</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prices as $item => $price)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-2 border-b capitalize">{{ $item }}</td>
                                                <td class="px-4 py-2 border-b">{{ $price }} FCFA</td>
                                                <td class="px-4 py-2 border-b">{{ number_format($price, 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="2" class="px-4 py-2 text-right font-semibold border-t">Total des articles:</td>
                                            <td class="px-4 py-2 font-semibold border-t">{{ count($prices) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                <p class="text-yellow-700">Aucun tarif configuré pour ce pressing.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions d'administration -->
                    @if(!$pressing->is_approved)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-4">Actions d'Administration</h3>

                        <div class="flex space-x-4">
                            <form action="{{ route('admin.pressings.approve', $pressing) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors"
                                        onclick="return confirm('Êtes-vous sûr de vouloir approuver ce pressing ?')">
                                    ✅ Approuver le Pressing
                                </button>
                            </form>

                            <form action="{{ route('admin.pressings.reject', $pressing) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition-colors"
                                        onclick="return confirm('Êtes-vous sûr de vouloir refuser ce pressing ? Cette action est irréversible.')">
                                    ❌ Refuser le Pressing
                                </button>
                            </form>
                        </div>

                        <p class="text-yellow-700 text-sm mt-4">
                            ⚠️ Une fois approuvé, le pressing sera visible par tous les clients. Le refus supprimera définitivement le pressing.
                        </p>
                    </div>
                    @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-4">Pressing Approuvé</h3>
                        <p class="text-green-700">Ce pressing a été approuvé et est visible par les clients.</p>

                        <div class="mt-4">
                            <a href="{{ route('home') }}" target="_blank"
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                👀 Voir sur le site public
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- <!-- Informations de debug (optionnel) -->
                    @if(config('app.debug'))
                    <div class="mt-8 bg-gray-100 p-4 rounded-lg">
                        <h4 class="font-mono text-sm text-gray-600 mb-2">Informations de Debug:</h4>
                        <pre class="text-xs text-gray-500">Pressing ID: {{ $pressing->id }}\nPropriétaire ID: {{ $pressing->owner_id }}\nJSON Prices: {{ json_encode($prices) }}</pre>
                    </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
