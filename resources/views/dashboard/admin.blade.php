<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de Bord - Administrateur
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Bienvenue, Administrateur !</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h4 class="font-semibold">Pressings</h4>
                            <p class="text-2xl">{{ $pressings->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h4 class="font-semibold">Commandes Total</h4>
                            <p class="text-2xl">{{ $totalOrders }}</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg">
                            <h4 class="font-semibold">Clients</h4>
                            <p class="text-2xl">{{ $totalClients }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-medium mb-4">Actions Administrateur</h4>
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.users') }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Gérer les Utilisateurs
                            </a>
                            <a href="#" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Voir toutes les Commandes
                            </a>
                        </div>
                    </div>

                    <h4 class="text-lg font-medium mb-4">Liste des Pressings</h4>

                    @if ($pressings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border">Nom</th>
                                        <th class="px-4 py-2 border">Propriétaire</th>
                                        <th class="px-4 py-2 border">Commandes</th>
                                        <th class="px-4 py-2 border">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pressings as $pressing)
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $pressing->name }}</td>
                                            <td class="px-4 py-2 border">{{ $pressing->owner->name }}</td>
                                            <td class="px-4 py-2 border">{{ $pressing->orders_count }}</td>
                                            <td class="px-4 py-2 border">
                                                <a href="{{ route('admin.pressings.show', $pressing) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-gray-100 p-4 rounded-lg text-center">
                            <p class="text-gray-600">Aucun pressing enregistré.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
