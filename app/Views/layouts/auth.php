<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RapiGas - Autenticación</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Logo -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <img class="mx-auto h-12 w-auto" src="/img/logo.png" alt="RapiGas">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                <?php echo $pageTitle ?? 'Bienvenido'; ?>
            </h2>
            <?php if (isset($pageDescription)): ?>
            <p class="mt-2 text-center text-sm text-gray-600">
                <?php echo $pageDescription; ?>
            </p>
            <?php endif; ?>
        </div>

        <!-- Notification Area -->
        <div x-data="notifications"
             aria-live="assertive"
             class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-50">
            <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                <template x-for="notification in notifications" :key="notification.id">
                    <div x-show="notification.visible"
                         x-transition:enter="transform ease-out duration-300 transition"
                         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg x-show="notification.type === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <svg x-show="notification.type === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-medium text-gray-900" x-text="notification.message"></p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex">
                                    <button @click="removeNotification(notification.id)" class="rounded-md inline-flex text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Cerrar</span>
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <?php require_once $content; ?>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notifications', () => ({
            notifications: [],
            notificationCount: 0,

            init() {
                this.$watch('notifications', notifications => {
                    notifications.forEach(notification => {
                        if (notification.visible) {
                            setTimeout(() => {
                                this.removeNotification(notification.id);
                            }, 5000);
                        }
                    });
                });

                window.addEventListener('notify', event => {
                    this.addNotification(event.detail);
                });
            },

            addNotification({ type, message }) {
                const id = ++this.notificationCount;
                this.notifications.push({
                    id,
                    type,
                    message,
                    visible: true
                });
            },

            removeNotification(id) {
                const notification = this.notifications.find(n => n.id === id);
                if (notification) {
                    notification.visible = false;
                    setTimeout(() => {
                        this.notifications = this.notifications.filter(n => n.id !== id);
                    }, 300);
                }
            }
        }));
    });
    </script>
</body>
</html>