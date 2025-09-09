<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Confirmation de Suppression - {{ $pressing->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Bouton de retour -->
            <div class="mb-6">
                <a href="{{ route('admin.pressings.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                    ← Retour à la liste
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-red-800">Attention ! Action irréversible</h3>
                            </div>
                        </div>

                        <p class="text-red-700 mb-4">
                            Vous êtes sur le point de supprimer définitivement le pressing <strong>"{{ $pressing->name }}"</strong>.
                        </p>

                        @if($pressing->orders_count > 0)
                        <div class="bg-red-100 p-4 rounded-md mb-4">
                            <h4 class="font-semibold text-red-800">⚠️ Données qui seront supprimées :</h4>
                            <ul class="list-disc list-inside text-red-700 mt-2">
                                <li>Le pressing et toutes ses informations</li>
                                <li>{{ $pressing->orders_count }} commande(s) associée(s)</li>
                                <li>Tous les articles de ces commandes</li>
                                <li>L'historique des transactions</li>
                            </ul>
                            <p class="text-red-700 font-semibold mt-2">
                                Cette action affectera les données des clients.
                            </p>
                        </div>
                        @else
                        <div class="bg-yellow-100 p-4 rounded-md mb-4">
                            <p class="text-yellow-700">
                                Ce pressing n'a aucune commande associée.
                            </p>
                        </div>
                        @endif

                        <p class="text-red-700 font-semibold">
                            ⚠️ Cette action ne peut pas être annulée.
                        </p>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('admin.pressings.index') }}"
                           class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            Annuler
                        </a>

                        <form action="{{ route('admin.pressings.destroy', $pressing) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition-colors">
                                 Confirmer la suppression définitive
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
