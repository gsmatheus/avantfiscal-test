function showLoading(element) {
    $(element).prop('disabled', true).html('<i data-lucide="loader-2" class="animate-spin inline w-4 h-4 mr-2"></i> Carregando...');
    lucide.createIcons();
}

function hideLoading(element, originalText) {
    $(element).prop('disabled', false).html(originalText);
}


window.togglePassword = function(element) {
    const input = element.parentElement.querySelector('input');
    const eye = element.querySelector('i[data-lucide]');
    
    if (input.type === 'password') {
        input.type = 'text';
        if (eye) eye.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        if (eye) eye.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
}; 