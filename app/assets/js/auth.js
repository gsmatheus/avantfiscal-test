$(document).ready(function() {
    checkAuthStatus();
    
    loadRememberedCredentials();

    $(document).on('click', '#switchUserBtn', function() {
        clearRememberedCredentials();
        showEmailInput();
        $('#remember').prop('checked', false);
    });

    $('#authForm').on('submit', function(e) {
        e.preventDefault();
        
        const formMode = $('#formMode').val();
        
        const email = $('#emailInput').val() || $('#hiddenEmail').val();
        if (!email) {
            showAlert('E-mail é obrigatório', 'error');
            return;
        }
        
        const formData = new FormData(this);
        
        if ($('#hiddenEmail').val() && !$('#emailInput').val()) {
            formData.set('email', $('#hiddenEmail').val());
        } else if ($('#emailInput').val()) {
            formData.set('email', $('#emailInput').val());
        }
        
        const basePath = window.location.pathname.includes('/app/') ? 'api/auth/' : 'app/api/auth/';
        const endpoint = basePath + (formMode === 'login' ? 'login.php' : 'register.php');
        
        const submitButton = $('#submitButton');
        const originalText = submitButton.text();
        submitButton.prop('disabled', true).text('Processando...');
        
        $('.alert-container').empty();
        
        $.ajax({
            url: endpoint,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    handleRememberMe(formData);
                    
                    showAlert(response.message, 'success');
                    
                    setTimeout(function() {
                        window.location.href = 'app/index.php';
                    }, 1000);
                } else {
                    handleErrors(response);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Erro interno do servidor';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        handleErrors(response);
                        return;
                    }
                    errorMessage = response.message || response.error || errorMessage;
                } catch (e) {
                }
                
                showAlert(errorMessage, 'error');
            },
            complete: function() {
                submitButton.prop('disabled', false).text(originalText);
            }
        });
    });
});

function checkAuthStatus() {
    const profileUrl = window.location.pathname.includes('/app/') ? 'api/auth/profile.php' : 'app/api/auth/profile.php';
    $.ajax({
        url: profileUrl,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                window.location.href = 'app/index.php';
            }
        },
        error: function() {
        }
    });
}


function handleErrors(response) {
    if (response.errors && typeof response.errors === 'object') {
        let errorMessages = [];
        
        for (let field in response.errors) {
            const fieldName = getFieldDisplayName(field);
            errorMessages.push(`<strong>${fieldName}:</strong> ${response.errors[field]}`);
        }
        
        showAlert(errorMessages.join('<br>'), 'error');
    } else if (response.message) {
        showAlert(response.message, 'error');
    } else if (response.error) {
        showAlert(response.error, 'error');
    } else {
        showAlert('Erro desconhecido', 'error');
    }
}

function getFieldDisplayName(field) {
    const fieldNames = {
        'name': 'Nome',
        'email': 'E-mail',
        'password': 'Senha',
        'confirm_password': 'Confirmação de Senha'
    };
    
    return fieldNames[field] || field;
}


function handleRememberMe(formData) {
    const formMode = $('#formMode').val();
    const rememberChecked = $('#remember').is(':checked');
    
    if (formMode === 'login' && rememberChecked) {
        const email = $('#emailInput').val() || $('#hiddenEmail').val();
        
        localStorage.setItem('remember_email', email);
        localStorage.setItem('remember_checked', 'true');
    } else if (formMode === 'login' && !rememberChecked) {
        clearRememberedCredentials();
    } else if (formMode === 'register') {
        const email = $('#emailInput').val();
        
        if (email) {
            localStorage.setItem('remember_email', email);
            localStorage.setItem('remember_checked', 'true');
        }
    }
}

function loadRememberedCredentials() {
    const rememberedEmail = localStorage.getItem('remember_email');
    const rememberChecked = localStorage.getItem('remember_checked');
    
    if (rememberedEmail && rememberChecked === 'true') {
        showRememberedUserCard(rememberedEmail);
        $('#remember').prop('checked', true);
    }
}

function showRememberedUserCard(email) {
    const initials = email.charAt(0).toUpperCase() + (email.split('@')[0].charAt(1) || '').toUpperCase();
    
    $('#userInitials').text(initials);
    $('#rememberedEmail').text(email);
    $('#hiddenEmail').val(email);
    $('#emailInput').val('');
    
    $('#emailInput').removeAttr('required');
    $('#hiddenEmail').attr('required', 'required');
    
    $('#rememberedUserCard').removeClass('hidden');
    $('#emailInputContainer').addClass('hidden');
}

function showEmailInput() {
    $('#emailInput').attr('required', 'required');
    $('#hiddenEmail').removeAttr('required');
    
    $('#hiddenEmail').val('');
    
    $('#rememberedUserCard').addClass('hidden');
    $('#emailInputContainer').removeClass('hidden');
    $('#emailInput').focus();
}

function clearRememberedCredentials() {
    localStorage.removeItem('remember_email');
    localStorage.removeItem('remember_checked');
    
    $('#hiddenEmail').val('');
} 