$(document).ready(function() {
    
    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    lucide.createIcons();

    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                colors: {
                    'orange': {
                        500: '#FB6206',
                        600: '#E55A00'
                    }
                }
            }
        }
    }
}); 

function showAlert(message, type = 'info') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `fixed top-4 right-4 z-[60] max-w-sm w-full transform transition-all duration-300 translate-x-full`;
    
    const alertColors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    const alertIcons = {
        success: 'check-circle',
        error: 'x-circle',
        warning: 'alert-triangle',
        info: 'info'
    };
    
    alertContainer.innerHTML = `
        <div class="rounded-lg shadow-lg p-4 ${alertColors[type]} flex items-center space-x-3 justify-between">
            <div class="flex items-center space-x-3">
                <i data-lucide="${alertIcons[type]}" class="h-5 w-5 flex-shrink-0"></i>
                <span class="text-sm font-medium">${message}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(alertContainer);
    
    lucide.createIcons();
    
    setTimeout(() => {
        alertContainer.classList.remove('translate-x-full');
    }, 10);
    
    setTimeout(() => {
        alertContainer.classList.add('translate-x-full');
        setTimeout(() => {
            if (alertContainer.parentElement) {
                alertContainer.remove();
            }
        }, 300);
    }, 5000);
} 

