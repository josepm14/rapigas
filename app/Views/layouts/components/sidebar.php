<div class="flex flex-col h-0 flex-1">
    <!-- Logo/Brand -->
    <div class="flex items-center h-16 flex-shrink-0 px-4 bg-indigo-700">
        <img class="h-8 w-auto" src="/img/logo-white.png" alt="RapiGas">
        <span class="ml-2 text-white text-lg font-semibold">RapiGas</span>
    </div>

    <!-- Navigation -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <nav class="flex-1 px-2 py-4 space-y-1">
            <!-- Dashboard -->
            <a href="/dashboard" 
               class="<?php echo request_is('/dashboard') ? 'bg-indigo-800 text-white' : 'text-gray-300 hover:bg-indigo-600 hover:text-white'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <!-- Employees -->
            <a href="/employees" 
               class="<?php echo request_is('/employees*') ? 'bg-indigo-800 text-white' : 'text-gray-300 hover:bg-indigo-600 hover:text-white'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Empleados
            </a>

            <!-- Attendance -->
            <a href="/attendance" 
               class="<?php echo request_is('/attendance*') ? 'bg-indigo-800 text-white' : 'text-gray-300 hover:bg-indigo-600 hover:text-white'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Asistencia
            </a>

            <!-- Reports -->
            <a href="/reports" 
               class="<?php echo request_is('/reports*') ? 'bg-indigo-800 text-white' : 'text-gray-300 hover:bg-indigo-600 hover:text-white'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Reportes
            </a>

            <!-- Settings -->
            <a href="/settings" 
               class="<?php echo request_is('/settings*') ? 'bg-indigo-800 text-white' : 'text-gray-300 hover:bg-indigo-600 hover:text-white'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Configuración
            </a>
        </nav>
    </div>

    <!-- User Profile Section -->
    <div class="flex-shrink-0 flex border-t border-indigo-800 p-4">
        <div class="flex items-center">
            <div>
                <img class="inline-block h-9 w-9 rounded-full" 
                     src="<?php echo $_SESSION['user']['avatar'] ?? '/img/default-avatar.png'; ?>" 
                     alt="">
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">
                    <?php echo $_SESSION['user']['name']; ?>
                </p>
                <p class="text-xs font-medium text-indigo-200">
                    <?php echo $_SESSION['user']['role']; ?>
                </p>
            </div>
        </div>
    </div>
</div>