<?php
require_once __DIR__ . '/../backend/config/config.php';

requireAuth();

$userName = $_SESSION['user_name'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';
$userRole = $_SESSION['user_role'] ?? '';
$userRoleText = $userRole === 'admin' ? 'Administrador' : 'Usuário';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Sistema de Reservas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="icon" href="https://avantfiscal.com.br/wp-content/uploads/2022/03/favicon-avant-fiscal-2022.svg"
        sizes="32x32" />
    <link rel="icon" href="https://avantfiscal.com.br/wp-content/uploads/2022/03/favicon-avant-fiscal-2022.svg"
        sizes="192x192" />
    <link rel="apple-touch-icon"
        href="https://avantfiscal.com.br/wp-content/uploads/2022/03/favicon-avant-fiscal-2022.svg" />
    <meta name="msapplication-TileImage"
        content="https://avantfiscal.com.br/wp-content/uploads/2022/03/favicon-avant-fiscal-2022.svg" />

    <link rel="stylesheet" href="assets/css/animations.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'orange': {
                            500: '#FB6206',
                            600: '#E55A00'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .modal-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 8px;
            box-sizing: border-box;
        }

        .modal-content {
            max-height: calc(100vh - 16px);
            overflow-y: auto;
            width: 100%;
            margin: auto;
            box-sizing: border-box;
            position: relative;
        }

        @media (min-width: 640px) {
            .modal-container {
                padding: 16px;
            }

            .modal-content {
                max-height: calc(100vh - 32px);
            }
        }

        @media (max-height: 600px) {
            .modal-content {
                max-height: calc(100vh - 8px);
            }

            .modal-container {
                padding: 4px;
            }
        }

        @media (max-height: 500px) {
            .modal-content {
                max-height: calc(100vh - 4px);
            }

            .modal-container {
                padding: 2px;
            }
        }

        /* Garantir que o modal sempre caiba na tela */
        @media (max-width: 480px) {
            .modal-content {
                width: calc(100vw - 8px);
                max-width: calc(100vw - 8px);
            }
        }

        /* Ajustes para notebooks pequenos */
        @media (max-width: 768px) and (max-height: 720px) {
            .modal-content {
                max-height: calc(100vh - 12px);
            }

            .modal-container {
                padding: 6px;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-white font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
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

        <!-- Main Content -->
        <main class="flex-1 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="text-center mb-8 sm:mb-12">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                        Boas-vindas ao clickai
                    </h1>
                    <p class="text-gray-600 max-w-2xl mx-auto text-sm sm:text-base px-4 sm:px-0">
                        Agora você pode organizar diferentes tipos de eventos e<br class="hidden sm:block">
                        convidar as pessoas a confirmar a presença do<br class="hidden sm:block">
                        seu evento.
                    </p>
                </div>

                <!-- Create Room Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-8 p-4 sm:p-6">
                    <!-- Desktop/Tablet Layout -->
                    <div class="hidden sm:flex items-center space-x-4">
                        <div class="flex-1 relative">
                            <i data-lucide="search"
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"></i>
                            <input type="text" id="roomName" placeholder="Pesquisar nome de sala"
                                class="w-full pl-12 pr-4 py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100">
                        </div>
                        <?php if ($userRole === 'admin'): ?>
                            <button onclick="createRoom()"
                                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-14 rounded-[60px] transition-colors">
                                Criar sala
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile Layout -->
                    <div class="sm:hidden space-y-4">
                        <div class="relative">
                            <i data-lucide="search"
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"></i>
                            <input type="text" id="roomNameMobile" placeholder="Pesquisar nome de sala"
                                class="w-full pl-12 pr-4 py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100">
                        </div>
                        <?php if ($userRole === 'admin'): ?>
                            <button onclick="createRoom()"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-6 rounded-xl transition-colors flex items-center justify-center space-x-2">
                                <i data-lucide="plus-circle" class="h-5 w-5"></i>
                                <span>Criar Nova Sala</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Rooms List -->
                <div class="space-y-4" id="roomsList">
                    <!-- Salas serão carregadas aqui via JavaScript -->
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Criar Sala -->
    <div id="createRoomModal"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm modal-container z-50 hidden transition-all duration-300">
        <div class="bg-white rounded-2xl sm:rounded-3xl max-w-2xl modal-content relative shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
            id="modalContent">
            <!-- Header do Modal -->
            <div class="relative px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100">
                <div class="flex items-center justify-center mb-3 sm:mb-4">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="plus" class="h-6 w-6 sm:h-8 sm:w-8 text-white"></i>
                    </div>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 text-center">Criar Nova Sala</h3>
                <p class="text-sm sm:text-base text-gray-600 text-center mt-2 px-2">Configure sua sala e comece a
                    organizar eventos</p>
                <button onclick="closeModal()"
                    class="absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors group">
                    <i data-lucide="x" class="h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-gray-700"></i>
                </button>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="px-4 sm:px-8 py-6 sm:py-8">
                <form id="createRoomForm" class="space-y-4 sm:space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-900 mb-2 sm:mb-3">Nome da Sala</label>
                            <div class="relative">
                                <i data-lucide="home"
                                    class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 h-4 w-4 sm:h-5 sm:w-5 text-gray-400"></i>
                                <input type="text" id="modalRoomName" placeholder="Ex: Reunião de Planejamento 2024"
                                    class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2 sm:mb-3">Localização</label>
                            <div class="relative">
                                <i data-lucide="map-pin"
                                    class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 h-4 w-4 sm:h-5 sm:w-5 text-gray-400"></i>
                                <input type="text" id="roomLocation" placeholder="Ex: Sala de Reuniões A"
                                    class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2 sm:mb-3">Capacidade</label>
                            <div class="relative">
                                <i data-lucide="users"
                                    class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 h-4 w-4 sm:h-5 sm:w-5 text-gray-400"></i>
                                <input type="number" id="roomCapacity" placeholder="50"
                                    class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100"
                                    min="1" max="100" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <i data-lucide="info" class="h-3 w-3 mr-1"></i>
                                Máximo de 100 participantes
                            </p>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-3 sm:p-4 border border-orange-200">
                        <div class="flex items-start space-x-2 sm:space-x-3">
                            <div class="flex-shrink-0">
                                <i data-lucide="lightbulb" class="h-4 w-4 sm:h-5 sm:w-5 text-orange-600 mt-0.5"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-orange-900">Dica</h4>
                                <p class="text-xs sm:text-sm text-orange-800 mt-1">
                                    Escolha um nome descritivo para facilitar a identificação da sala pelos
                                    participantes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4 sm:pt-6">
                        <button type="submit"
                            class="w-full px-6 py-3 sm:py-4 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i data-lucide="plus-circle" class="h-4 w-4 mr-2 inline"></i>
                            Criar Sala
                        </button>
                        <button type="button" onclick="closeModal()"
                            class="w-full px-6 py-3 sm:py-4 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Exclusão -->
    <div id="deleteConfirmModal"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm modal-container z-50 hidden transition-all duration-300">
        <div class="bg-white rounded-2xl sm:rounded-3xl max-w-md modal-content relative shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
            id="deleteModalContent">
            <!-- Header do Modal -->
            <div class="relative px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100">
                <div class="flex items-center justify-center mb-3 sm:mb-4">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="trash" class="h-6 w-6 sm:h-8 sm:w-8 text-white"></i>
                    </div>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 text-center">Confirmar Exclusão</h3>
                <p class="text-sm sm:text-base text-gray-600 text-center mt-2 px-2">Esta ação não pode ser desfeita</p>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="px-4 sm:px-8 py-6">
                <div class="text-center mb-6">
                    <p class="text-gray-700 text-base sm:text-lg">
                        Tem certeza que deseja excluir a sala
                        <span class="font-bold text-gray-900" id="roomNameToDelete"></span>?
                    </p>
                    <p class="text-red-600 text-sm mt-2 font-medium">
                        Todas as reservas associadas também serão removidas.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="confirmDelete()"
                        class="w-full px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i data-lucide="trash" class="h-4 w-4 mr-2 inline"></i>
                        Excluir Sala
                    </button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="w-full px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Cancelamento de Participação -->
    <div id="cancelReservationModal"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm modal-container z-50 hidden transition-all duration-300">
        <div class="bg-white rounded-2xl sm:rounded-3xl max-w-md modal-content relative shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
            id="cancelReservationModalContent">
            <!-- Header do Modal -->
            <div class="relative px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100">
                <div class="flex items-center justify-center mb-3 sm:mb-4">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="calendar-x" class="h-6 w-6 sm:h-8 sm:w-8 text-white"></i>
                    </div>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 text-center">Cancelar Participação</h3>
                <p class="text-sm sm:text-base text-gray-600 text-center mt-2 px-2">Esta ação não pode ser desfeita</p>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="px-4 sm:px-8 py-6">
                <div class="text-center mb-6">
                    <p class="text-gray-700 text-base sm:text-lg">
                        Tem certeza que deseja cancelar sua participação na sala
                        <span class="font-bold text-gray-900" id="reservationRoomName"></span>?
                    </p>
                    <p class="text-orange-600 text-sm mt-2 font-medium">
                        Você não poderá mais participar deste evento.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="confirmCancelReservation()"
                        class="w-full px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i data-lucide="calendar-x" class="h-4 w-4 mr-2 inline"></i>
                        Cancelar Participação
                    </button>
                    <button type="button" onclick="closeCancelReservationModal()"
                        class="w-full px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Manter Participação
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Participar -->
    <div id="participateModal"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm modal-container z-50 hidden transition-all duration-300">
        <div class="bg-white rounded-2xl sm:rounded-3xl max-w-2xl modal-content relative shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
            id="participateModalContent">
            <!-- Header do Modal -->
            <div class="relative px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100">
                <div class="flex items-center justify-center mb-3 sm:mb-4">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="calendar-plus" class="h-6 w-6 sm:h-8 sm:w-8 text-white"></i>
                    </div>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 text-center">Participar da Sala</h3>
                <p class="text-sm sm:text-base text-gray-600 text-center mt-2 px-2" id="participateRoomName">Selecione o
                    horário para sua
                    participação</p>
                <button onclick="closeParticipateModal()"
                    class="absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors group">
                    <i data-lucide="x" class="h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-gray-700"></i>
                </button>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="px-4 sm:px-8 py-6 sm:py-8">
                <form id="participateForm" class="space-y-4 sm:space-y-6">
                    <input type="hidden" id="participateRoomId" name="room_id">

                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2 sm:mb-3">Data e Hora de
                                Início</label>
                            <div class="relative">
                                <i data-lucide="calendar"
                                    class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 h-4 w-4 sm:h-5 sm:w-5 text-gray-400"></i>
                                <input type="datetime-local" id="startTime" name="start_time"
                                    class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2 sm:mb-3">Data e Hora de
                                Fim</label>
                            <div class="relative">
                                <i data-lucide="calendar"
                                    class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 h-4 w-4 sm:h-5 sm:w-5 text-gray-400"></i>
                                <input type="datetime-local" id="endTime" name="end_time"
                                    class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2 sm:mb-3">Descrição
                            (opcional)</label>
                        <div class="relative">
                            <i data-lucide="file-text"
                                class="absolute left-3 sm:left-4 top-4 h-4 w-4 sm:h-5 sm:w-5 text-gray-400"></i>
                            <textarea id="participationDescription" name="description" rows="3"
                                placeholder="Descreva brevemente o evento ou reunião..."
                                class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100 resize-none"></textarea>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-3 sm:p-4 border border-orange-200">
                        <div class="flex items-start space-x-2 sm:space-x-3">
                            <div class="flex-shrink-0">
                                <i data-lucide="info" class="h-4 w-4 sm:h-5 sm:w-5 text-orange-600 mt-0.5"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-orange-900">Informação</h4>
                                <p class="text-xs sm:text-sm text-orange-800 mt-1">
                                    Selecione o horário que deseja participar do evento nesta sala. A descrição é
                                    opcional.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4 sm:pt-6">
                        <button type="submit"
                            class="w-full px-6 py-3 sm:py-4 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i data-lucide="check-circle" class="h-4 w-4 mr-2 inline"></i>
                            Participar
                        </button>
                        <button type="button" onclick="closeParticipateModal()"
                            class="w-full px-6 py-3 sm:py-4 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/utils.js"></script>
    <script src="assets/js/rooms.js?v=<?php echo time(); ?>"></script>
    <script>
        const userRole = '<?php echo $userRole; ?>';
        const userName = '<?php echo htmlspecialchars($userName); ?>';
        const userEmail = '<?php echo htmlspecialchars($userEmail); ?>';
    </script>
    <script>
        function createRoom() {
            const modal = document.getElementById('createRoomModal');
            const modalContent = document.getElementById('modalContent');

            $('#createRoomModal h3').text('Criar Nova Sala');
            $('#createRoomForm button[type="submit"]').html('<i data-lucide="plus-circle" class="h-4 w-4 mr-2 inline"></i>Criar Sala');

            $('#createRoomForm')[0].reset();

            $('#createRoomForm').off('submit').on('submit', handleCreateRoom);

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                adjustModalPosition();
                lucide.createIcons();
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('createRoomModal');
            const modalContent = document.getElementById('modalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';

                $('#createRoomForm')[0].reset();
                $('#createRoomModal h3').text('Criar Nova Sala');
                $('#createRoomForm button[type="submit"]').html('<i data-lucide="plus-circle" class="h-4 w-4 mr-2 inline"></i>Criar Sala');
                $('#createRoomForm').off('submit').on('submit', handleCreateRoom);

                lucide.createIcons();
            }, 300);
        }


        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        function logout() {
            $.ajax({
                url: '../../app/api/auth/logout.php',
                method: 'POST',
                success: function (response) {
                    window.location.href = '../../index.php';
                },
                error: function () {
                    alert('Erro ao fazer logout');
                }
            });
        }

        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button[onclick="toggleDropdown()"]');

            if (!button && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });


        document.getElementById('createRoomModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeModal();
            }
        });

        let roomToDelete = null;
        let reservationToCancel = null;

        function openDeleteModal(roomId, roomName) {
            roomToDelete = roomId;
            document.getElementById('roomNameToDelete').textContent = roomName;

            const modal = document.getElementById('deleteConfirmModal');
            const modalContent = document.getElementById('deleteModalContent');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                adjustModalPosition();
                lucide.createIcons();
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteConfirmModal');
            const modalContent = document.getElementById('deleteModalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                roomToDelete = null;
            }, 300);
        }

        function confirmDelete() {
            if (roomToDelete) {
                executeDelete(roomToDelete);
                closeDeleteModal();
            }
        }

        document.getElementById('deleteConfirmModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        function openCancelReservationModal(reservationId, roomName) {
            reservationToCancel = reservationId;
            document.getElementById('reservationRoomName').textContent = roomName;

            const modal = document.getElementById('cancelReservationModal');
            const modalContent = document.getElementById('cancelReservationModalContent');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                adjustModalPosition();
                lucide.createIcons();
            }, 10);
        }

        function closeCancelReservationModal() {
            const modal = document.getElementById('cancelReservationModal');
            const modalContent = document.getElementById('cancelReservationModalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                reservationToCancel = null;
            }, 300);
        }

        function confirmCancelReservation() {
            if (reservationToCancel) {
                executeCancelReservation(reservationToCancel);
                closeCancelReservationModal();
            }
        }

        document.getElementById('cancelReservationModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeCancelReservationModal();
            }
        });

        let roomToParticipate = null;

        function openParticipateModal(roomId, roomName) {
            roomToParticipate = roomId;
            document.getElementById('participateRoomName').textContent = `Participar em: ${roomName}`;
            document.getElementById('participateRoomId').value = roomId;

            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const minDateTime = `${year}-${month}-${day}T00:00`;
            document.getElementById('startTime').min = minDateTime;
            document.getElementById('endTime').min = minDateTime;

            const modal = document.getElementById('participateModal');
            const modalContent = document.getElementById('participateModalContent');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                adjustModalPosition();
                lucide.createIcons();
            }, 10);
        }

        function closeParticipateModal() {
            const modal = document.getElementById('participateModal');
            const modalContent = document.getElementById('participateModalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                roomToParticipate = null;
                document.getElementById('participateForm').reset();
            }, 300);
        }

        document.getElementById('participateModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeParticipateModal();
            }
        });

        document.getElementById('participateForm').addEventListener('submit', handleParticipate);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeModal();
                closeDeleteModal();
                closeCancelReservationModal();
                closeParticipateModal();
            }
        });

        function adjustModalPosition() {
            const modals = ['createRoomModal', 'deleteConfirmModal', 'cancelReservationModal', 'participateModal'];

            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    const modalContent = modal.querySelector('.modal-content');
                    if (modalContent) {
                        let padding = '16px';
                        if (window.innerHeight < 500) {
                            padding = '4px';
                        } else if (window.innerHeight < 600) {
                            padding = '8px';
                        } else if (window.innerWidth >= 640) {
                            padding = '32px';
                        } else if (window.innerWidth <= 768 && window.innerHeight <= 720) {
                            padding = '12px';
                        }

                        if (window.innerWidth <= 480) {
                            modalContent.style.width = 'calc(100vw - 8px)';
                            modalContent.style.maxWidth = 'calc(100vw - 8px)';
                        } else {
                            modalContent.style.width = '100%';
                            modalContent.style.maxWidth = '';
                        }

                        modalContent.style.maxHeight = `calc(100vh - ${padding})`;

                        modal.scrollTop = 0;
                    }
                }
            });
        }

        window.addEventListener('resize', adjustModalPosition);
        window.addEventListener('orientationchange', function () {
            setTimeout(adjustModalPosition, 100);
        });

        document.addEventListener('DOMContentLoaded', function () {
            adjustModalPosition();
        });
    </script>
</body>

</html>