<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PressingApp - Accueil</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-indigo-600">PressingApp</a>
                    </div>
                    <div class="flex items-center">
                        @if (Route::has('login'))
                            <div class="space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                        class="text-gray-700 hover:text-indigo-600">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Connexion</a>
                                    <a href="{{ route('register') }}"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Inscription</a>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Bienvenue sur PressingApp</h1>
                    <p class="text-xl text-gray-600 mb-4">La solution innovante pour gérer votre pressing en ligne</p>
                </div>

                <!-- Titre pressings partenaires -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-2">Nos Pressings Partenaires</h2>
                    <p class="text-gray-500 mb-4">Découvrez les pressings disponibles près de chez vous</p>
                </div>

                <!-- Liste des Pressings -->
                <div class="mb-16">
                    @if ($pressings->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                            @foreach ($pressings as $pressing)
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow flex flex-col">
                                    <div class="p-6 flex-1 flex flex-col">
                                        <h3 class="text-xl font-semibold text-indigo-700 mb-2">{{ $pressing->name }}</h3>
                                        <p class="text-gray-600 mb-4 flex-1">{{ $pressing->description }}</p>

                                        <div class="mb-4">
                                            <p class="text-gray-700"><strong>Adresse:</strong> {{ $pressing->address }}</p>
                                            <p class="text-gray-700"><strong>Téléphone:</strong> {{ $pressing->phone }}</p>
                                        </div>

                                        {{-- <div class="mb-4">
                                            <h4 class="font-semibold text-gray-800 mb-2">Tarifs:</h4>
                                            <ul class="space-y-1">
                                                @php
                                                    $pricesArray = is_array($pressing->prices) ? $pressing->prices : [];
                                                @endphp

                                                @if(count($pricesArray) > 0)
                                                    @foreach($pricesArray as $item => $price)
                                                        <li class="flex justify-between text-gray-700">
                                                            <span class="capitalize">{{ $item }}:</span>
                                                            <span>{{ number_format($price, 0, ',', ' ') }} FCFA</span>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li class="text-red-500">❌ Aucun tarif configuré</li>
                                                @endif
                                            </ul>
                                        </div> --}}
                                    </div>
                                    <div class="p-6 pt-0">
                                        @auth
                                            @if (auth()->user()->type === 'client')
                                                <a href="{{ route('orders.create', $pressing) }}"
                                                    class="block w-full bg-indigo-600 text-white text-center py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors font-semibold">
                                                    Commander
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}"
                                                class="block w-full bg-indigo-600 text-white text-center py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors font-semibold">
                                                Se connecter pour commander
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-600 text-lg">Aucun pressing disponible pour le moment.</p>
                        </div>
                    @endif
                </div>

                <!-- Sections informatives -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Pour les Clients</h2>
                        <p class="text-gray-600">Commandez facilement et suivez votre lessive en temps réel</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Pour les Pressings</h2>
                        <p class="text-gray-600">Gérez vos commandes et augmentez votre visibilité</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Livraison</h2>
                        <p class="text-gray-600">Service de livraison pour plus de commodité</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Pied de page -->
        <footer class="bg-white shadow-md mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500">&copy; 2023 PressingApp. Tous droits réservés.</p>
            </div>
        </footer>
    </div>
</body>

</html>
