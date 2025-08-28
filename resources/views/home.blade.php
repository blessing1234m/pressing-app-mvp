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
                                    <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-indigo-600">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Connexion</a>
                                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Inscription</a>
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
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-6">Bienvenue sur PressingApp</h1>
                    <p class="text-xl text-gray-600 mb-8">La solution innovante pour gérer votre pressing en ligne</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
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
