$(document).ready(function() {
    loadRooms();
    $('#createRoomForm').on('submit', handleCreateRoom);
    $('#roomName').on('input', debounce(handleSearch, 300));
    $('#roomNameMobile').on('input', debounce(handleSearchMobile, 300));
    
    $(document).on('submit', '#participateForm', handleParticipate);
});
function showSkeletonLoader() {
    const roomsList = $('#roomsList');
    const skeletonItems = [];
    for (let i = 0; i < 3; i++) {
        skeletonItems.push(`
            <div class="bg-[#FCFCFC] rounded-xl shadow-sm border border-[#EFEFEF] p-4 sm:p-6">
                <!-- Desktop/Tablet Layout -->
                <div class="hidden md:flex items-center justify-between">
                    <div class="flex-1">
                        <div class="h-8 skeleton-shimmer rounded-lg mb-3 w-2/3"></div>
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 skeleton-shimmer rounded"></div>
                                <div class="h-5 skeleton-shimmer rounded w-32"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 skeleton-shimmer rounded"></div>
                                <div class="h-5 skeleton-shimmer rounded w-40"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-24 h-12 skeleton-shimmer rounded-[60px]"></div>
                        <div class="w-12 h-12 skeleton-shimmer rounded-xl"></div>
                    </div>
                </div>
                <!-- Mobile Layout -->
                <div class="md:hidden">
                    <div class="flex items-start justify-between mb-4">
                        <div class="h-6 skeleton-shimmer rounded-lg w-2/3"></div>
                        <div class="w-20 h-8 skeleton-shimmer rounded-lg"></div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 skeleton-shimmer rounded"></div>
                                <div class="h-4 skeleton-shimmer rounded w-20"></div>
                            </div>
                            <div class="h-4 skeleton-shimmer rounded w-12"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 skeleton-shimmer rounded"></div>
                                <div class="h-4 skeleton-shimmer rounded w-24"></div>
                            </div>
                            <div class="h-4 skeleton-shimmer rounded w-16"></div>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }
    roomsList.html(skeletonItems.join(''));
}
function loadRooms() {
    showSkeletonLoader();
    const startTime = Date.now();
    $.ajax({
        url: '../../app/api/rooms/',
        method: 'GET',
        success: function(response) {
            const elapsed = Date.now() - startTime;
            const delay = Math.max(0, 500 - elapsed);
            setTimeout(() => {
                if (response.success) {
                    const rooms = response.data.rooms || response.data || response.rooms;
                    renderRooms(rooms);
                } else {
                    showAlert(response.message || 'Erro ao carregar salas', 'error');
                }
            }, delay);
        },
        error: function() {
            const elapsed = Date.now() - startTime;
            const delay = Math.max(0, 500 - elapsed);
            setTimeout(() => {
                showAlert('Erro ao conectar com o servidor', 'error');
            }, delay);
        }
    });
}
function renderRooms(rooms) {
    const roomsList = $('#roomsList');
    roomsList.empty();
    if (rooms.length === 0) {
        roomsList.html(`
            <div class="text-center py-8 sm:py-12 fade-in-up px-4">
                <i data-lucide="home" class="h-12 w-12 sm:h-16 sm:w-16 text-gray-300 mx-auto mb-4"></i>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Nenhuma sala encontrada</h3>
                <p class="text-sm sm:text-base text-gray-500">Crie sua primeira sala para começar a organizar eventos</p>
            </div>
        `);
        lucide.createIcons();
        return;
    }
    const isAdmin = typeof userRole !== 'undefined' && userRole === 'admin';
    rooms.forEach((room, index) => {
        let actionButtons = '';
        let orientationInfo = null;
        if (isAdmin) {
            const hasParticipants = (room.active_participants || 0) > 0;
            const viewReservationsButton = hasParticipants ? `
                <button onclick="openViewReservationsModal(${room.id}, '${escapeHtml(room.name)}')" 
                    class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <i data-lucide="calendar" class="h-4 w-4 mr-1 inline"></i>
                    Reservas
                </button>
            ` : '';
            
            const viewReservationsButtonMobile = hasParticipants ? `
                <button onclick="openViewReservationsModal(${room.id}, '${escapeHtml(room.name)}')" 
                    class="w-8 h-8 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center">
                    <i data-lucide="calendar" class="h-4 w-4 text-blue-600"></i>
                </button>
            ` : '';
            
            actionButtons = `
                <!-- Desktop/Tablet -->
                <div class="hidden md:flex items-center space-x-3">
                    ${viewReservationsButton}
                    <button onclick="editRoom(${room.id})" 
                        class="px-14 py-3 text-base font-bold text-gray-900 bg-white border border-gray-300 rounded-[60px] hover:bg-gray-50 transition-colors">
                        Editar
                    </button>
                    <button onclick="deleteRoom(${room.id})" 
                        class="w-12 h-12 bg-red-500 hover:bg-red-600 rounded-xl text-white flex items-center justify-center transition-colors">
                        <i data-lucide="trash" class="h-5 w-5"></i>
                    </button>
                </div>
                <!-- Mobile -->
                <div class="md:hidden flex items-center space-x-2">
                    ${viewReservationsButtonMobile}
                    <button onclick="editRoom(${room.id})" 
                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i data-lucide="edit-2" class="h-4 w-4"></i>
                    </button>
                    <button onclick="deleteRoom(${room.id})" 
                        class="w-8 h-8 bg-red-500 hover:bg-red-600 rounded-lg text-white flex items-center justify-center transition-colors">
                        <i data-lucide="trash" class="h-4 w-4"></i>
                    </button>
                </div>
            `;
        } else {
            const hasReservation = room.user_has_reservation > 0;
            if (hasReservation) {
                const startDate = new Date(room.user_start_time);
                const endDate = new Date(room.user_end_time);
                const formatDate = (date) => {
                    return date.toLocaleDateString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                };
                const formatTime = (date) => {
                    return date.toLocaleTimeString('pt-BR', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                };
                orientationInfo = {
                    startDate: formatDate(startDate),
                    startTime: formatTime(startDate),
                    endDate: formatDate(endDate),
                    endTime: formatTime(endDate)
                };
                actionButtons = `
                    <!-- Desktop/Tablet -->
                    <div class="hidden md:flex items-center space-x-2">
                        <div class="px-4 py-2 bg-green-100 text-green-800 rounded-full flex items-center">
                            <i data-lucide="check-circle" class="h-4 w-4 mr-2"></i>
                            <span class="text-sm font-medium">Participando!</span>
                        </div>
                        <button onclick="openCancelReservationModal(${room.user_reservation_id}, '${escapeHtml(room.name)}')" class="w-10 h-10 bg-red-500 hover:bg-red-600 rounded-xl text-white flex items-center justify-center">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                    </div>
                    <!-- Mobile -->
                    <div class="md:hidden flex items-center space-x-1">
                        <div class="px-2 py-1 bg-green-100 text-green-800 rounded-full flex items-center">
                            <i data-lucide="check-circle" class="h-3 w-3 mr-1"></i>
                            <span class="text-xs font-medium">Participando!</span>
                        </div>
                        <button onclick="openCancelReservationModal(${room.user_reservation_id}, '${escapeHtml(room.name)}')" class="w-8 h-8 bg-red-500 hover:bg-red-600 rounded-lg text-white flex items-center justify-center">
                            <i data-lucide="x" class="h-3 w-3"></i>
                        </button>
                    </div>
                `;
            } else {
                actionButtons = `
                    <button onclick="openParticipateModal(${room.id}, '${escapeHtml(room.name)}')" 
                        class="px-8 md:px-14 py-2.5 md:py-3 text-sm md:text-base font-bold text-gray-900 bg-white border border-gray-300 rounded-[60px] hover:bg-gray-50 transition-colors">
                        Participar
                    </button>
                `;
            }
        }
        const roomCard = `
            <div class="bg-[#FCFCFC] rounded-xl shadow-sm border border-[#EFEFEF] p-4 sm:p-6 hover:bg-gray-50 transition-all duration-300 fade-in-up opacity-0" 
                 data-room-id="${room.id}" 
                 style="animation-delay: ${index * 0.1}s">
                <!-- Layout Desktop/Tablet (md+) -->
                <div class="hidden md:flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-2xl font-medium text-gray-900 mb-2">${escapeHtml(room.name)}</h3>
                        <div class="flex items-center space-x-6 text-base mb-3">
                            <div class="flex items-center gap-2">
                                <i data-lucide="users" class="h-5 w-5"></i>
                                <span class="text-gray-700 font-medium">
                                    <b class="font-bold">Participantes:</b> ${room.active_participants || 0}/${room.capacity}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="h-5 w-5"></i>
                                <span class="text-gray-700 font-medium">
                                    <b class="font-bold">Localizado:</b> ${escapeHtml(room.location)}
                                </span>
                            </div>
                        </div>
                        ${orientationInfo ? `
                            <div class="text-base text-gray-700">
                                <b class="font-bold">Orientação:</b> Iniciará em ${orientationInfo.startDate} às ${orientationInfo.startTime} e terminará em ${orientationInfo.endDate} às ${orientationInfo.endTime}
                            </div>
                        ` : ''}
                    </div>
                    <div class="flex items-start ml-4">
                        ${actionButtons}
                    </div>
                </div>
                <!-- Layout Mobile (sm e menor) -->
                <div class="md:hidden">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex-1 pr-2">${escapeHtml(room.name)}</h3>
                        <div class="flex-shrink-0">
                            ${actionButtons}
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="users" class="h-4 w-4 text-gray-500"></i>
                                <span class="text-sm text-gray-600">Participantes</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">${room.active_participants || 0}/${room.capacity}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="h-4 w-4 text-gray-500"></i>
                                <span class="text-sm text-gray-600">Localização</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">${escapeHtml(room.location)}</span>
                        </div>
                        ${orientationInfo ? `
                            <div class="bg-green-50 rounded-lg p-3 mt-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="calendar" class="h-4 w-4 text-green-600"></i>
                                    <span class="text-sm font-medium text-green-800">Sua Participação</span>
                                </div>
                                <div class="text-xs text-green-700">
                                    <div class="flex justify-between mb-1">
                                        <span>Início:</span>
                                        <span>${orientationInfo.startDate} às ${orientationInfo.startTime}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Fim:</span>
                                        <span>${orientationInfo.endDate} às ${orientationInfo.endTime}</span>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        roomsList.append(roomCard);
    });
    setTimeout(() => {
        roomsList.find('.fade-in-up').removeClass('opacity-0');
        lucide.createIcons();
    }, 50);
}
function handleCreateRoom(event) {
    event.preventDefault();
    const formData = new FormData();
    formData.append('name', $('#modalRoomName').val().trim());
    formData.append('location', $('#roomLocation').val().trim());
    formData.append('capacity', $('#roomCapacity').val());
    if (!validateRoomForm(formData)) {
        return;
    }
    const submitBtn = $('#createRoomForm button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i data-lucide="loader" class="h-4 w-4 mr-2 inline animate-spin"></i>Criando...');
    $.ajax({
        url: '../../app/api/rooms/create.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                closeModal();
                loadRooms();
            } else {
                if (response.errors) {
                    displayFormErrors(response.errors);
                } else {
                    showAlert(response.message || response.error || 'Erro ao criar sala', 'error');
                }
            }
        },
        error: function(xhr, status, error) {
            console.log('Erro AJAX:', xhr.status, xhr.responseText);
            if (xhr.status === 400 || xhr.status === 422) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        displayFormErrors(response.errors);
                    } else {
                        showAlert(response.message || response.error || 'Erro ao criar sala', 'error');
                    }
                } catch (e) {
                    showAlert('Erro ao processar resposta do servidor', 'error');
                }
            } else {
                showAlert('Erro ao conectar com o servidor', 'error');
            }
        },
        complete: function() {
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
}
function editRoom(roomId) {
    $.ajax({
        url: `../../app/api/rooms/?id=${roomId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const room = response.data || response.room;
                openEditModal(room);
            } else {
                showAlert(response.message || 'Erro ao carregar dados da sala', 'error');
            }
        },
        error: function() {
            showAlert('Erro ao conectar com o servidor', 'error');
        }
    });
}
function openEditModal(room) {
    $('#modalRoomName').val(room.name);
    $('#roomLocation').val(room.location);
    $('#roomCapacity').val(room.capacity);
    $('#createRoomModal h3').text('Editar Sala');
    $('#createRoomForm button[type="submit"]').html('<i data-lucide="save" class="h-4 w-4 mr-2 inline"></i>Salvar Alterações');
    $('#createRoomForm').off('submit').on('submit', function(e) {
        handleUpdateRoom(e, room.id);
    });
    const modal = document.getElementById('createRoomModal');
    const modalContent = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}
function handleUpdateRoom(event, roomId) {
    event.preventDefault();
    const formData = new FormData();
    formData.append('id', roomId);
    formData.append('name', $('#modalRoomName').val().trim());
    formData.append('location', $('#roomLocation').val().trim());
    formData.append('capacity', $('#roomCapacity').val());
    if (!validateRoomForm(formData)) {
        return;
    }
    const submitBtn = $('#createRoomForm button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i data-lucide="loader" class="h-4 w-4 mr-2 inline animate-spin"></i>Salvando...');
    $.ajax({
        url: '../../app/api/rooms/update.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                closeModal();
                loadRooms();
            } else {
                if (response.errors) {
                    displayFormErrors(response.errors);
                } else {
                    showAlert(response.message || response.error || 'Erro ao atualizar sala', 'error');
                }
            }
        },
        error: function(xhr, status, error) {
            console.log('Erro AJAX:', xhr.status, xhr.responseText);
            if (xhr.status === 400 || xhr.status === 422) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        displayFormErrors(response.errors);
                    } else {
                        showAlert(response.message || response.error || 'Erro ao atualizar sala', 'error');
                    }
                } catch (e) {
                    showAlert('Erro ao processar resposta do servidor', 'error');
                }
            } else {
                showAlert('Erro ao conectar com o servidor', 'error');
            }
        },
        complete: function() {
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
}
function deleteRoom(roomId) {
    const roomCard = $(`[data-room-id="${roomId}"]`);
    const roomName = roomCard.find('h3').text().trim();
    openDeleteModal(roomId, roomName);
}
function executeDelete(roomId) {
    const roomCard = $(`[data-room-id="${roomId}"]`);
    const deleteBtn = roomCard.find('button[onclick*="deleteRoom"]');
    const originalHtml = deleteBtn.html();
    deleteBtn.prop('disabled', true).html('<i data-lucide="loader" class="h-4 w-4 animate-spin"></i>');
    $.ajax({
        url: '../../app/api/rooms/delete.php',
        method: 'POST',
        data: { id: roomId },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                roomCard.fadeOut(300, function() {
                    $(this).remove();
                });
            } else {
                showAlert(response.message || response.error || 'Erro ao excluir sala', 'error');
                deleteBtn.prop('disabled', false).html(originalHtml);
            }
        },
        error: function() {
            showAlert('Erro ao conectar com o servidor', 'error');
            deleteBtn.prop('disabled', false).html(originalHtml);
        },
        complete: function() {
        }
    });
}
function executeCancelReservation(reservationId) {
    const btn = $(`button[onclick*="openCancelReservationModal(${reservationId}"]`);
    const originalHtml = btn.html();
    btn.prop('disabled', true).html('<i data-lucide="loader" class="h-4 w-4 animate-spin"></i>');
    $.ajax({
        url: '../../app/api/reservations/delete.php',
        method: 'POST',
        data: { id: reservationId },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                loadRooms();
            } else {
                showAlert(response.message || response.error || 'Erro ao cancelar reserva', 'error');
            }
        },
        error: function() {
            showAlert('Erro ao conectar com o servidor', 'error');
        },
        complete: function() {
            btn.prop('disabled', false).html(originalHtml);
        }
    });
}
function handleSearch() {
    const query = $('#roomName').val().trim();
    $('#roomNameMobile').val(query);
    performSearch(query);
}
function handleSearchMobile() {
    const query = $('#roomNameMobile').val().trim();
    $('#roomName').val(query);
    performSearch(query);
}
function performSearch(query) {
    if (query.length === 0) {
        loadRooms();
        return;
    }
    showSkeletonLoader();
    const startTime = Date.now();
    $.ajax({
        url: '../../app/api/rooms/',
        method: 'GET',
        data: { q: query },
        success: function(response) {
            const elapsed = Date.now() - startTime;
            const delay = Math.max(0, 500 - elapsed);
            setTimeout(() => {
                if (response.success) {
                    const rooms = response.data.rooms || response.data || response.rooms;
                    renderRooms(rooms);
                } else {
                    showAlert(response.message || 'Erro ao pesquisar salas', 'error');
                }
            }, delay);
        },
        error: function() {
            const elapsed = Date.now() - startTime;
            const delay = Math.max(0, 500 - elapsed);
            setTimeout(() => {
                showAlert('Erro ao conectar com o servidor', 'error');
            }, delay);
        }
    });
}
function validateRoomForm(formData) {
    const name = formData.get('name');
    const location = formData.get('location');
    const capacity = parseInt(formData.get('capacity'));
    if (!name || name.length < 2) {
        showAlert('Nome da sala deve ter pelo menos 2 caracteres', 'error');
        return false;
    }
    if (!location || location.length < 2) {
        showAlert('Localização deve ter pelo menos 2 caracteres', 'error');
        return false;
    }
    if (!capacity || capacity < 1) {
        showAlert('Capacidade deve ser maior que 0', 'error');
        return false;
    }
    if (capacity > 100) {
        showAlert('Capacidade máxima é de 100 pessoas', 'error');
        return false;
    }
    return true;
}
function displayFormErrors(errors) {
    let errorMessage = '';
    if (errors.name) errorMessage += errors.name;
    if (errors.location) errorMessage += (errorMessage ? '\n' : '') + errors.location;
    if (errors.capacity) errorMessage += (errorMessage ? '\n' : '') + errors.capacity;
    if (!errorMessage) {
        errorMessage = 'Erro de validação nos dados fornecidos';
    }
    showAlert(errorMessage, 'error');
}
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
}
function handleParticipate(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const form = event.target;
    const formData = new FormData(form);
    
    const startTime = formData.get('start_time');
    const endTime = formData.get('end_time');
    
    if (!startTime || !endTime) {
        showAlert('Por favor, selecione as datas de início e fim', 'error');
        return;
    }
    
    const startDate = convertBrazilianDateToISO(startTime);
    const endDate = convertBrazilianDateToISO(endTime);
    
    if (!startDate || !endDate) {
        showAlert('Formato de data inválido. Use o formato dd/mm/yyyy hh:mm', 'error');
        return;
    }
    
    if (new Date(startDate) >= new Date(endDate)) {
        showAlert('A data de término deve ser depois da data de início', 'error');
        return;
    }
    
    formData.set('start_time', startDate);
    formData.set('end_time', endDate);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i data-lucide="loader" class="h-4 w-4 mr-2 inline animate-spin"></i>Participando...';
    
    $.ajax({
        url: '../../app/api/reservations/create.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.success) {
                showAlert(response.message || 'Participação realizada com sucesso!', 'success');
                closeParticipateModal();
                loadRooms();
            } else {
                if (response.errors) {
                    for (let field in response.errors) {
                        showAlert(response.errors[field], 'error');
                    }
                } else {
                    showAlert(response.error || 'Erro ao participar', 'error');
                }
            }
        },
        error: function (xhr) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.errors) {
                    for (let field in response.errors) {
                        showAlert(response.errors[field], 'error');
                    }
                } else {
                    showAlert(response.error || 'Erro ao participar', 'error');
                }
            } catch (e) {
                showAlert('Erro ao conectar com o servidor', 'error');
            }
        },
        complete: function () {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

function convertBrazilianDateToISO(dateTimeString) {
    const match = dateTimeString.match(/(\d{2})\/(\d{2})\/(\d{4})\s+(\d{2}):(\d{2})/);
    if (!match) return null;
    
    const [, day, month, year, hour, minute] = match;
    return `${year}-${month}-${day}T${hour}:${minute}:00`;
}

function openViewReservationsModal(roomId, roomName) {
    document.getElementById('viewReservationsRoomName').textContent = `Reservas da sala: ${roomName}`;
    
    const modal = document.getElementById('viewReservationsModal');
    const modalContent = document.getElementById('viewReservationsModalContent');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
        adjustModalPosition();
        loadRoomReservations(roomId);
    }, 10);
}

function closeViewReservationsModal() {
    const modal = document.getElementById('viewReservationsModal');
    const modalContent = document.getElementById('viewReservationsModalContent');

    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}

function loadRoomReservations(roomId) {
    const reservationsList = document.getElementById('reservationsList');
    const noReservationsMessage = document.getElementById('noReservationsMessage');
    const reservationsLoading = document.getElementById('reservationsLoading');

    reservationsList.innerHTML = '';
    noReservationsMessage.classList.add('hidden');
    reservationsLoading.classList.remove('hidden');

    $.ajax({
        url: `../../app/api/reservations/?room_id=${roomId}`,
        method: 'GET',
        success: function(response) {
            reservationsLoading.classList.add('hidden');
            
            if (response.success && response.data && response.data.length > 0) {
                const reservations = response.data;
                renderReservations(reservations);
            } else {
                noReservationsMessage.classList.remove('hidden');
            }
        },
        error: function() {
            reservationsLoading.classList.add('hidden');
            noReservationsMessage.classList.remove('hidden');
            showAlert('Erro ao carregar reservas', 'error');
        }
    });
}

function renderReservations(reservations) {
    const reservationsList = document.getElementById('reservationsList');
    
    reservations.forEach((reservation, index) => {
        const startDate = new Date(reservation.start_time);
        const endDate = new Date(reservation.end_time);
        const now = new Date();
        
        const formatDate = (date) => {
            return date.toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        };
        
        const formatTime = (date) => {
            return date.toLocaleTimeString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        const isPast = endDate < now;
        const isCurrent = startDate <= now && endDate >= now;
        
        let statusBadge = '';
        if (isPast) {
            statusBadge = '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">Finalizada</span>';
        } else if (isCurrent) {
            statusBadge = '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-600 rounded-full">Em andamento</span>';
        } else {
            statusBadge = '<span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-full">Agendada</span>';
        }

        const reservationCard = `
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:bg-gray-100 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                ${reservation.user_name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">${escapeHtml(reservation.user_name)}</h4>
                                <p class="text-sm text-gray-500">${escapeHtml(reservation.user_email)}</p>
                            </div>
                            ${statusBadge}
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div class="flex items-center space-x-2">
                                <i data-lucide="calendar" class="h-4 w-4 text-gray-400"></i>
                                <span class="text-gray-700">
                                    <strong>Início:</strong> ${formatDate(startDate)} às ${formatTime(startDate)}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i data-lucide="clock" class="h-4 w-4 text-gray-400"></i>
                                <span class="text-gray-700">
                                    <strong>Fim:</strong> ${formatDate(endDate)} às ${formatTime(endDate)}
                                </span>
                            </div>
                        </div>
                        
                        ${reservation.description ? `
                            <div class="mt-3 p-3 bg-white rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-700">${escapeHtml(reservation.description)}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        reservationsList.innerHTML += reservationCard;
    });
    
} 

function adjustModalPosition() {
    const modal = document.getElementById('viewReservationsModal');
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
} 