<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side -->
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <img class="block lg:hidden h-8 w-auto" src="/img/logo-small.png" alt="RapiGas">
                    <img class="hidden lg:block h-8 w-auto" src="/img/logo.png" alt="RapiGas">
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center">
                <!-- Notifications -->
                <div x-data="notifications" class="relative">
                    <button @click="open = !open" 
                            class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="sr-only">Ver notificaciones</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <!-- Notification badge -->
                        <span x-show="unreadCount > 0"
                              class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                    </button>

                    <!-- Dropdown menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false"
                         class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                         role="menu">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-900">Notificaciones</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-3 text-sm text-gray-500">
                                    No hay notificaciones nuevas
                                </div>
                            </template>
                            <template x-for="notification in notifications" :key="notification.id">
                                <div class="px-4 py-3 hover:bg-gray-50" :class="{'bg-gray-50': !notification.read}">
                                    <p class="text-sm text-gray-900" x-text="notification.message"></p>
                                    <p class="text-xs text-gray-500 mt-1" x-text="formatTime(notification.created_at)"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Profile dropdown -->
                <div x-data="{ open: false }" class="ml-3 relative">
                    <div>
                        <button @click="open = !open" 
                                class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                                id="user-menu-button">
                            <span class="sr-only">Abrir menú de usuario</span>
                            <img class="h-8 w-8 rounded-full" 
                                 src="<?php echo $_SESSION['user']['avatar'] ?? '/img/default-avatar.png'; ?>" 
                                 alt="">
                        </button>
                    </div>

                    <!-- Dropdown menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         @click.away="open = false"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                         role="menu">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">
                                <?php echo $_SESSION['user']['name']; ?>
                            </p>
                            <p class="text-sm text-gray-500">
                                <?php echo $_SESSION['user']['email']; ?>
                            </p>
                        </div>
                        <a href="/profile" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                           role="menuitem">Mi Perfil</a>
                        <a href="/settings" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                           role="menuitem">Configuración</a>
                        <form action="/logout" method="POST">
                            <button type="submit" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                    role="menuitem">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notifications', () => ({
        open: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.loadNotifications();
            // Check for new notifications every minute
            setInterval(() => this.loadNotifications(), 60000);
        },

        async loadNotifications() {
            try {
                const response = await fetch('/notifications');
                const data = await response.json();
                if (data.success) {
                    this.notifications = data.notifications;
                    this.unreadCount = data.notifications.filter(n => !n.read).length;
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = (now - date) / 1000; // difference in seconds

            if (diff < 60) return 'Justo ahora';
            if (diff < 3600) return `Hace ${Math.floor(diff / 60)} minutos`;
            if (diff < 86400) return `Hace ${Math.floor(diff / 3600)} horas`;
            
            return date.toLocaleDateString('es-PE', {
                day: '2-digit',
                month: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }));
});
</script>