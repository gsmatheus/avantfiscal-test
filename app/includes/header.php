<?php
if (!isset($userName) || !isset($userEmail) || !isset($userRole) || !isset($userRoleText)) {
    throw new Exception('Variáveis de usuário não definidas');
}
?>
<header class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center hidden sm:block">
                <img src="assets/images/logo.png" alt="Sistema de Reservas" class="h-[40px]" />
            </div>

            <div class="flex items-center space-x-6">
                <a href="#" class="text-orange-500 hover:text-orange-600 font-bold">Início</a>
            </div>
            <div class="relative">
                <button onclick="toggleDropdown()"
                    class="flex items-center space-x-2 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                    <span class="text-sm text-gray-700"><?php echo htmlspecialchars($userName); ?></span>
                    <div
                        class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-white text-sm font-medium">
                        <?php echo strtoupper(substr($userName, 0, 1)); ?>
                    </div>
                    <i data-lucide="chevron-down" class="h-4 w-4 text-gray-500"></i>
                </button>

                <div id="userDropdown"
                    class="absolute right-0 mt-2 w-72 max-w-[calc(100vw-2rem)] bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex items-start space-x-3">
                            <div
                                class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white font-medium flex-shrink-0">
                                <?php echo strtoupper(substr($userName, 0, 1)); ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-900 truncate">
                                    <?php echo htmlspecialchars($userName); ?>
                                </div>
                                <div class="text-sm text-gray-500 break-all">
                                    <?php echo htmlspecialchars($userEmail); ?>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <?php echo htmlspecialchars($userRoleText); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-2">
                        <button onclick="logout()"
                            class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md flex items-center space-x-2 transition-colors">
                            <i data-lucide="log-out" class="h-4 w-4"></i>
                            <span>Sair</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header> 