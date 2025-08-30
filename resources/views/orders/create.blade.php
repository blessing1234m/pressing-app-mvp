<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander - PressingApp</title>
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
                        <a href="{{ url('/') }}" class="text-gray-700 hover:text-indigo-600 mr-4">Accueil</a>
                        <span class="text-gray-500">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="flex-grow">
            <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Nouvelle Commande - {{ $pressing->name }}</h1>

                    <!-- Messages d'erreur -->
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

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pressing_id" value="{{ $pressing->id }}">

                        <!-- Section Articles -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">Articles à nettoyer</h2>
                            <p class="text-gray-600 mb-6">Sélectionnez les types de vêtements et les quantités</p>

                            <div class="space-y-3" id="items-container">
                                @foreach ($pressing->prices as $item => $price)
                                    <div
                                        class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium text-gray-700 capitalize mb-1">
                                                {{ str_replace('_', ' ', $item) }}
                                            </label>
                                            <p class="text-sm text-gray-500">{{ number_format($price, 0, ',', ' ') }}
                                                FCFA par article</p>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="text-sm text-gray-600 whitespace-nowrap"
                                                id="total-{{ $item }}">
                                                0 FCFA
                                            </span>
                                            <div class="flex items-center border border-gray-300 rounded-md">
                                                <button type="button"
                                                    class="decrement-btn px-3 py-2 text-gray-600 hover:bg-gray-100"
                                                    data-item="{{ $item }}" data-price="{{ $price }}">
                                                    −
                                                </button>
                                                <input type="number" name="items[{{ $item }}]" value="0"
                                                    min="0" max="20"
                                                    class="quantity-input w-12 text-center border-x border-gray-300 py-2"
                                                    data-item="{{ $item }}" data-price="{{ $price }}"
                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                <button type="button"
                                                    class="increment-btn px-3 py-2 text-gray-600 hover:bg-gray-100"
                                                    data-item="{{ $item }}" data-price="{{ $price }}">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div id="no-items-message"
                                class="hidden mt-4 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                <p class="text-yellow-700">Veuillez sélectionner au moins un article.</p>
                            </div>
                        </div>
                        <!-- Section Informations de Récupération -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">Informations de récupération</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date de récupération souhaitée
                                    </label>
                                    <input type="datetime-local" id="pickup_date" name="pickup_date"
                                        min="{{ date('Y-m-d\TH:i') }}"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required>
                                </div>

                                <div>
                                    <label for="client_address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Adresse de récupération
                                    </label>
                                    <textarea id="client_address" name="client_address" rows="3"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="Votre adresse complète" required>{{ old('client_address', auth()->user()->address ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section Instructions Spéciales -->
                        <div class="mb-6">
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                Instructions spéciales (optionnel)
                            </label>
                            <textarea id="special_instructions" name="special_instructions" rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Précisions sur les vêtements, préférences particulières...">{{ old('special_instructions') }}</textarea>
                        </div>

                        <!-- Récapitulatif et Total -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-lg font-semibold text-gray-800">Total:</span>
                                <span id="total-amount" class="text-2xl font-bold text-indigo-600">0 FCFA</span>
                            </div>
                            <p class="text-sm text-gray-600">* 50% à payer à la récupération (soit <span
                                    id="advance-amount">0 FCFA</span>)</p>
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ url('/') }}"
                                class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition-colors">
                                Annuler
                            </a>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                                Commander
                            </button>
                        </div>
                    </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemsContainer = document.getElementById('items-container');
            const noItemsMessage = document.getElementById('no-items-message');
            const totalAmountElement = document.getElementById('total-amount');
            const advanceAmountElement = document.getElementById('advance-amount');
            const submitButton = document.querySelector('button[type="submit"]');

            let total = 0;

            // Fonction pour mettre à jour les totaux
            function updateTotals() {
                total = 0;
                let hasItems = false;

                document.querySelectorAll('.quantity-input').forEach(input => {
                    const quantity = parseInt(input.value);
                    const price = parseInt(input.dataset.price);
                    const item = input.dataset.item;
                    const itemTotal = quantity * price;

                    // Mettre à jour le total par article
                    document.getElementById(`total-${item}`).textContent =
                        itemTotal.toLocaleString('fr-FR') + ' FCFA';

                    total += itemTotal;

                    if (quantity > 0) {
                        hasItems = true;
                    }
                });

                // Mettre à jour les totaux généraux
                totalAmountElement.textContent = total.toLocaleString('fr-FR') + ' FCFA';
                advanceAmountElement.textContent = Math.floor(total / 2).toLocaleString('fr-FR') + ' FCFA';

                // Afficher/cacher le message d'erreur
                if (hasItems) {
                    noItemsMessage.classList.add('hidden');
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    noItemsMessage.classList.remove('hidden');
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // Gestion des boutons +/-
            itemsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('increment-btn')) {
                    const input = e.target.parentElement.querySelector('.quantity-input');
                    if (parseInt(input.value) < 20) {
                        input.value = parseInt(input.value) + 1;
                        input.dispatchEvent(new Event('input'));
                    }
                }

                if (e.target.classList.contains('decrement-btn')) {
                    const input = e.target.parentElement.querySelector('.quantity-input');
                    if (parseInt(input.value) > 0) {
                        input.value = parseInt(input.value) - 1;
                        input.dispatchEvent(new Event('input'));
                    }
                }
            });

            // Écouter les changements sur les inputs
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', function() {
                    // Validation manuelle
                    if (this.value < 0) this.value = 0;
                    if (this.value > 20) this.value = 20;
                    if (this.value === '') this.value = 0;

                    updateTotals();
                });
            });

            // Validation du formulaire
            document.querySelector('form').addEventListener('submit', function(e) {
                if (total === 0) {
                    e.preventDefault();
                    noItemsMessage.classList.remove('hidden');
                    noItemsMessage.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });

            // Initialiser les totaux
            updateTotals();

            // Amélioration UX: Focus sur le premier champ
            document.getElementById('pickup_date')?.focus();
        });
    </script>

</body>

</html>
