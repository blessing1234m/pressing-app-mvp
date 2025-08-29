<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes Notifications
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Toutes mes notifications</h3>

                        @if ($notifications->where('is_read', false)->count() > 0)
                            <form action="{{ route('notifications.read-all') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                                    Tout marquer comme lu
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse($notifications as $notification)
                            <div
                                class="border border-gray-200 rounded-lg p-4 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $notification->title }}</h4>
                                    <span
                                        class="text-sm text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                </div>

                                <p class="text-gray-600 mb-3">{{ $notification->message }}</p>

                                <div class="flex justify-between items-center">
                                    @if ($notification->order)
                                        <a href="{{ route('orders.show', $notification->order) }}"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm">
                                            Voir la commande
                                        </a>
                                    @endif

                                    @if (!$notification->is_read)
                                        <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                                                Marquer comme lu
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Aucune notification</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
