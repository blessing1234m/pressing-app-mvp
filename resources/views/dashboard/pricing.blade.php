<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des Prix - {{ $pressing->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <a href="{{ route('dashboard') }}"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            ← Retour au dashboard
                        </a>
                    </div>

                    <form action="{{ route('dashboard.pricing.update') }}" method="POST">
                        @csrf

                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">Vos tarifs actuels</h3>

                            @if (!empty($prices) && count($prices) > 0)
                                <div class="space-y-4">
                                    @foreach ($prices as $item => $price)
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700 capitalize mb-1">
                                                    {{ str_replace('_', ' ', $item) }}
                                                </label>
                                                <p class="text-sm text-gray-500">Prix actuel: {{ number_format($price, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-32">
                                                    <input type="number" name="prices[{{ $item }}]"
                                                        value="{{ $price }}" min="0" step="100"
                                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                                <button type="button"
                                                    class="text-red-600 hover:text-red-800 remove-existing-item"
                                                    data-item="{{ $item }}">
                                                    ✕
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <p class="text-yellow-700">Aucun tarif configuré pour le moment. Ajoutez vos premiers tarifs ci-dessous.</p>
                                </div>
                            @endif

                            <!-- Ajout dynamique d'un nouveau type de vêtement -->
                            <div class="mt-8 p-6 border border-gray-200 rounded-lg bg-gray-50">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Ajouter un nouveau type de vêtement</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="new_item_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nom du vêtement
                                        </label>
                                        <input type="text" id="new_item_name" name="new_item_name"
                                            placeholder="Ex: Chemise, Costume, Robe de soirée..."
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="new_item_price"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Prix (FCFA)
                                        </label>
                                        <input type="number" id="new_item_price" name="new_item_price" min="0"
                                            step="100" placeholder="2500"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" id="add-new-item"
                                            class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors">
                                            Ajouter
                                        </button>
                                    </div>
                                </div>
                                <div id="new-items-container" class="mt-4 space-y-3 hidden">
                                    <!-- Les nouveaux items ajoutés dynamiquement apparaîtront ici -->
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg mb-6">
                            <h4 class="font-semibold text-blue-800 mb-2">💡 Conseil</h4>
                            <p class="text-blue-700 text-sm">Vos prix doivent être compétitifs tout en couvrant vos coûts. Pensez à inclure le coût de la main d'œuvre, des produits et de la livraison.</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newItemNameInput = document.getElementById('new_item_name');
        const newItemPriceInput = document.getElementById('new_item_price');
        const addNewItemBtn = document.getElementById('add-new-item');
        const newItemsContainer = document.getElementById('new-items-container');
        const form = document.querySelector('form');

        let newItems = [];

        addNewItemBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const name = newItemNameInput.value.trim();
            const price = newItemPriceInput.value.trim();

            if (!name || !price) {
                alert('Veuillez remplir tous les champs pour ajouter un nouveau vêtement.');
                return;
            }

            const itemId = 'new_' + Date.now();
            newItems.push({
                id: itemId,
                name: name,
                price: price
            });

            const itemElement = document.createElement('div');
            itemElement.className =
                'flex items-center justify-between p-3 bg-white border border-green-200 rounded-lg';
            itemElement.innerHTML = `
                <div>
                    <span class="font-medium capitalize">${name}</span>
                    <span class="text-green-600 ml-2">${price} FCFA</span>
                </div>
                <button type="button" class="text-red-600 hover:text-red-800 remove-item" data-id="${itemId}">
                    ✕
                </button>
                <input type="hidden" name="prices[${name}]" value="${price}">
            `;

            newItemsContainer.appendChild(itemElement);
            newItemsContainer.classList.remove('hidden');

            newItemNameInput.value = '';
            newItemPriceInput.value = '';

            itemElement.querySelector('.remove-item').addEventListener('click', function() {
                const itemIdToRemove = this.dataset.id;
                newItems = newItems.filter(item => item.id !== itemIdToRemove);
                itemElement.remove();
                if (newItems.length === 0) {
                    newItemsContainer.classList.add('hidden');
                }
            });
        });

        // Gestion de la suppression des items existants
        document.querySelectorAll('.remove-existing-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemName = this.dataset.item;
                if (confirm(`Voulez-vous vraiment supprimer "${itemName.replace('_', ' ')}" de votre liste ?`)) {
                    // Marquer l'item pour suppression
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `remove_items[${itemName}]`;
                    hiddenInput.value = '1';
                    form.appendChild(hiddenInput);

                    // Cacher l'élément
                    this.closest('.flex.items-center.justify-between.p-4').style.display = 'none';
                }
            });
        });
    });
</script>
