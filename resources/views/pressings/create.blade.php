<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Créer votre Pressing
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                            <h3 class="text-red-800 font-semibold mb-2">Veuillez corriger les erreurs suivantes :</h3>
                            <ul class="list-disc list-inside text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pressings.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du Pressing *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       required placeholder="Ex: Pressing Express">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                       required placeholder="Ex: +221 77 123 45 67">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                      required placeholder="Adresse complète de votre pressing">{{ old('address') }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                      placeholder="Décrivez vos services, horaires d'ouverture...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Section des Prix -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tarifs (FCFA) *</h3>
                            <p class="text-gray-600 mb-6">Définissez vos tarifs pour chaque type de vêtement</p>

                            <div class="space-y-4">
                                @php
                                    $defaultItems = [
                                        'chemise' => 2000,
                                        'pantalon' => 3000,
                                        'robe' => 4000,
                                        'veste' => 5000,
                                        'jupe' => 2500,
                                        'costume' => 6000
                                    ];
                                @endphp

                                @foreach($defaultItems as $item => $defaultPrice)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 capitalize mb-1">
                                                {{ $item }}
                                            </label>
                                        </div>
                                        <div class="w-32">
                                            <input type="number"
                                                   name="prices[{{ $item }}]"
                                                   value="{{ old('prices.' . $item, $defaultPrice) }}"
                                                   min="0"
                                                   step="100"
                                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                   required>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg mb-6">
                            <h4 class="font-semibold text-blue-800 mb-2">💡 Conseil</h4>
                            <p class="text-blue-700 text-sm">Vous pourrez modifier ces tarifs et ajouter de nouveaux types de vêtements plus tard depuis votre dashboard.</p>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                                Créer mon Pressing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
