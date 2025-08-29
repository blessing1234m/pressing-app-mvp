@php
    use App\Services\NotificationService;
    $unreadCount = NotificationService::getUnreadCount(Auth::user());
    $notifications = NotificationService::getRecentNotifications(Auth::user(), 5);
@endphp

<div class="relative" x-data="{ open: false }">
    <!-- Bouton de notification -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown des notifications -->
    <div x-show="open" @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50"
         x-cloak>
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
            @if($unreadCount > 0)
                <p class="text-sm text-gray-600">{{ $unreadCount }} non lue(s)</p>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @if($notifications->count() > 0)
                @foreach($notifications as $notification)
                    <div class="border-b border-gray-100 last:border-b-0">
                        <a href="{{ $notification->order_id ? route('orders.show', $notification->order_id) : '#' }}"
                           class="block p-4 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}"
                           onclick="markAsRead({{ $notification->id }})">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-medium text-gray-900">{{ $notification->title }}</span>
                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                            @if(!$notification->is_read)
                                <span class="inline-block mt-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                            @endif
                        </a>
                    </div>
                @endforeach
            @else
                <div class="p-4 text-center text-gray-500">
                    <p>Aucune notification</p>
                </div>
            @endif
        </div>

        <div class="p-4 border-t border-gray-200 bg-gray-50">
            <a href="{{ route('notifications.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                Voir toutes les notifications
            </a>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch('/notifications/' + notificationId + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
}
</script>
