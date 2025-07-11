<?php
require_once __DIR__ . '/../backend/config/config.php';

requireAuth();

$userName = $_SESSION['user_name'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';
$userRole = $_SESSION['user_role'] ?? '';
$userRoleText = $userRole === 'admin' ? 'Administrador' : 'Usuário';

$isRootPage = false;
include_once __DIR__ . '/includes/page-config.php';
setPageConfig(getDashboardConfig());
include_once __DIR__ . '/includes/head.php';
?>
    <?php include_once __DIR__ . '/includes/modal-styles.php'; ?>
</head>

<body class="min-h-screen bg-white font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <?php include_once __DIR__ . '/includes/header.php'; ?>

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
                                <input type="text" id="startTime" name="start_time" readonly
                                    class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100 cursor-pointer"
                                    placeholder="Selecione a data e hora de início" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2 sm:mb-3">Data e Hora de
                                Fim</label>
                            <div class="relative">
                                <i data-lucide="calendar"
                                    class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 h-4 w-4 sm:h-5 sm:w-5 text-gray-400"></i>
                                <input type="text" id="endTime" name="end_time" readonly
                                    class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100 cursor-pointer"
                                    placeholder="Selecione a data e hora de fim" required>
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

    <!-- Modal Visualizar Reservas -->
    <div id="viewReservationsModal"
        class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm modal-container z-50 hidden transition-all duration-300">
        <div class="bg-white rounded-2xl sm:rounded-3xl max-w-4xl modal-content relative shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
            id="viewReservationsModalContent">
            <!-- Header do Modal -->
            <div class="relative px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-100">
                <div class="flex items-center justify-center mb-3 sm:mb-4">
                    <div
                        class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="calendar" class="h-6 w-6 sm:h-8 sm:w-8 text-white"></i>
                    </div>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 text-center">Reservas da Sala</h3>
                <p class="text-sm sm:text-base text-gray-600 text-center mt-2 px-2" id="viewReservationsRoomName">Visualizando todas as reservas</p>
                <button onclick="closeViewReservationsModal()"
                    class="absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors group">
                    <i data-lucide="x" class="h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-gray-700"></i>
                </button>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="px-4 sm:px-8 py-6 sm:py-8">
                <div id="reservationsList" class="space-y-4">
                    <!-- Reservas serão carregadas aqui -->
                </div>
                
                <div id="noReservationsMessage" class="text-center py-8 hidden">
                    <i data-lucide="calendar-x" class="h-12 w-12 text-gray-300 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma reserva encontrada</h3>
                    <p class="text-gray-500">Esta sala ainda não possui reservas agendadas.</p>
                </div>

                <div id="reservationsLoading" class="text-center py-8">
                    <div class="inline-flex items-center space-x-2">
                        <div class="w-4 h-4 bg-orange-500 rounded-full animate-bounce"></div>
                        <div class="w-4 h-4 bg-orange-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-4 h-4 bg-orange-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                    <p class="text-gray-500 mt-2">Carregando reservas...</p>
                </div>
            </div>
        </div>
    </div>


    <?php include_once __DIR__ . '/includes/scripts.php'; ?>
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

        document.getElementById('participateModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeParticipateModal();
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

            const modal = document.getElementById('participateModal');
            const modalContent = document.getElementById('participateModalContent');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                adjustModalPosition();
                lucide.createIcons();
                initializeDatepickers();
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
                destroyDatepickers();
            }, 300);
        }



        function adjustModalPosition() {
            const modals = ['createRoomModal', 'deleteConfirmModal', 'cancelReservationModal', 'participateModal', 'viewReservationsModal'];

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

        let startDatepicker, endDatepicker;

        function initializeDatepickers() {
            const now = new Date();
            const minDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            const datepickerOptions = {
                locale: {
                    days: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                    daysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    daysMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                    months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                    monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    today: 'Hoje',
                    clear: 'Limpar',
                    dateFormat: 'dd/MM/yyyy',
                    timeFormat: 'HH:mm',
                    firstDay: 1
                },
                dateFormat: 'dd/MM/yyyy',
                timepicker: true,
                timeFormat: 'HH:mm',
                minDate: minDate,
                autoClose: true,
                classes: 'air-datepicker-custom',
                onSelect: function(formattedDate, date, inst) {
                    if (inst.el.id === 'startTime') {
                        if (endDatepicker && date) {
                            endDatepicker.update({
                                minDate: date
                            });
                        }
                    }
                }
            };

            startDatepicker = new AirDatepicker('#startTime', {
                ...datepickerOptions,
                onSelect: function(formattedDate, date, inst) {
                    if (endDatepicker && date) {
                        endDatepicker.update({
                            minDate: date
                        });
                    }
                }
            });

            endDatepicker = new AirDatepicker('#endTime', {
                ...datepickerOptions,
                onSelect: function(formattedDate, date, inst) {
                    if (startDatepicker && date) {
                        startDatepicker.update({
                            maxDate: date
                        });
                    }
                }
            });
        }

        function destroyDatepickers() {
            if (startDatepicker) {
                startDatepicker.destroy();
                startDatepicker = null;
            }
            if (endDatepicker) {
                endDatepicker.destroy();
                endDatepicker = null;
            }
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