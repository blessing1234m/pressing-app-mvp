<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier l'Utilisateur
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <a href="{{ route('admin.users') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            ← Retour à la liste
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type d'utilisateur</label>
                            <select id="type" name="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                <option value="client" {{ old('type', $user->type) == 'client' ? 'selected' : '' }}>Client</option>
                                <option value="owner" {{ old('type', $user->type) == 'owner' ? 'selected' : '' }}>Propriétaire</option>
                                <option value="admin" {{ old('type', $user->type) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                <h4 class="font-semibold text-yellow-800 mb-2">Changer le mot de passe (optionnel)</h4>
                                <p class="text-yellow-700 text-sm">Laissez vide pour conserver le mot de passe actuel.</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                                        <input type="password" id="password" name="password"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        @error('password')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmation</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.users') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                                Mettre à jour
                            </button>
                        </div>
                    </form>

                    <!-- Section Danger -->
                    @if($user->id !== Auth::id())
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-red-700 mb-4">Zone dangereuse</h3>

                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <p class="text-red-700 mb-3">La suppression est irréversible. Soyez certain de votre action.</p>

                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                                    Supprimer cet utilisateur
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
