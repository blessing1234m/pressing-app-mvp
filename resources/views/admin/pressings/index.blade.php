<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Validation des Pressings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Pressings en attente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-4">
                        Pressings en Attente de Validation
                        @if($pendingPressings->count() > 0)
                            <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ $pendingPressings->count() }}
                            </span>
                        @endif
                    </h3>

                    @if($pendingPressings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border">Nom</th>
                                        <th class="px-4 py-2 border">Propriétaire</th>
                                        <th class="px-4 py-2 border">Date</th>
                                        <th class="px-4 py-2 border">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPressings as $pressing)
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $pressing->name }}</td>
                                            <td class="px-4 py-2 border">{{ $pressing->owner ? $pressing->owner->name : 'N/A' }}</td>
                                            <td class="px-4 py-2 border">{{ $pressing->created_at ? $pressing->created_at->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-4 py-2 border">
                                                <a href="{{ route('admin.pressings.show', $pressing) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                                <form action="{{ route('admin.pressings.approve', $pressing) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Approuver</button>
                                                </form>
                                                <form action="{{ route('admin.pressings.reject', $pressing) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir refuser ce pressing ?')">Refuser</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600">Aucun pressing en attente de validation.</p>
                    @endif
                </div>
            </div>

            <!-- Pressings approuvés -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">Pressings Approuvés</h3>

                    @if($approvedPressings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border">Nom</th>
                                        <th class="px-4 py-2 border">Propriétaire</th>
                                        <th class="px-4 py-2 border">Email</th>
                                        <th class="px-4 py-2 border">Approuvé le</th>
                                        <th class="px-4 py-2 border">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedPressings as $pressing)
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $pressing->name }}</td>
                                            <td class="px-4 py-2 border">{{ $pressing->owner ? $pressing->owner->name : 'N/A' }}</td>
                                            <td class="px-4 py-2 border">{{ $pressing->owner ? $pressing->owner->email : 'N/A' }}</td>
                                            <td class="px-4 py-2 border">{{ $pressing->approved_at ? $pressing->approved_at->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-4 py-2 border">
                                                <a href="{{ route('admin.pressings.show', $pressing) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600">Aucun pressing approuvé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
