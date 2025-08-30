<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de Bord - Propriétaire
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <div class="mx-auto w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m2 0H9m2 0H5m2 0H3m2 0v-5a1 1 0 011-1h8a1 1 0 011 1v5m-6 0h4"></path>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Bienvenue sur PressingApp !</h3>
                    <p class="text-gray-600 mb-8">Pour commencer à recevoir des commandes, vous devez d'abord créer votre pressing.</p>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h4 class="font-semibold text-blue-800 mb-3">Ce dont vous avez besoin :</h4>
                        <ul class="text-left list-disc list-inside text-blue-700 space-y-2">
                            <li>Le nom de votre pressing</li>
                            <li>Votre adresse complète</li>
                            <li>Votre numéro de téléphone</li>
                            <li>Vos tarifs pour différents types de vêtements</li>
                        </ul>
                    </div>

                    <a href="{{ route('pressings.create') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-md hover:bg-indigo-700 text-lg font-semibold">
                        Créer mon Pressing
                    </a>

                    <p class="text-gray-500 mt-6">Vous pourrez modifier ces informations à tout moment.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
