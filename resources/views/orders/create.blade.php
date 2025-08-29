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

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pressing_id" value="{{ $pressing->id }}">

                        <!-- Section Articles -->
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">Articles à nettoyer</h2>

                            <div class="space-y-4">
                                @foreach ($pressing->prices as $item => $price)
                                    <div
                                        class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div>
                                            <h3 class="font-medium text-gray-800 capitalize">{{ $item }}</h3>
                                            <p class="text-gray-600">{{ number_format($price, 0, ',', ' ') }} FCFA par
                                                article</p>
                                        </div>
                                        <div class="flex items-center">
                                            <button type="button"
                                                class="decrement-btn bg-gray-200 rounded-l-md px-3 py-1"
                                                data-item="{{ $item }}">
                                                -
                                            </button>
                                            <input type="number" name="items[{{ $item }}]" value="0"
                                                min="0"
                                                class="quantity-input w-16 text-center border-y border-gray-200 py-1"
                                                data-price="{{ $price }}" data-item="{{ $item }}">
                                            <button type="button"
                                                class="increment-btn bg-gray-200 rounded-r-md px-3 py-1"
                                                data-item="{{ $item }}">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
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
            const incrementButtons = document.querySelectorAll('.increment-btn');
            const decrementButtons = document.querySelectorAll('.decrement-btn');
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const totalAmountElement = document.getElementById('total-amount');
            const advanceAmountElement = document.getElementById('advance-amount');

            function updateTotal() {
                let total = 0;

                quantityInputs.forEach(input => {
                    const quantity = parseInt(input.value);
                    const price = parseInt(input.dataset.price);
                    total += quantity * price;
                });

                totalAmountElement.textContent = total.toLocaleString('fr-FR') + ' FCFA';
                advanceAmountElement.textContent = Math.floor(total / 2).toLocaleString('fr-FR') + ' FCFA';
            }

            incrementButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const item = this.dataset.item;
                    const input = document.querySelector(`input[name="items[${item}]"]`);
                    input.value = parseInt(input.value) + 1;
                    updateTotal();
                });
            });

            decrementButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const item = this.dataset.item;
                    const input = document.querySelector(`input[name="items[${item}]"]`);
                    if (parseInt(input.value) > 0) {
                        input.value = parseInt(input.value) - 1;
                        updateTotal();
                    }
                });
            });

            quantityInputs.forEach(input => {
                input.addEventListener('input', updateTotal);
            });

            // Initialiser le total
            updateTotal();
        });
    </script>
</body>

</html>
