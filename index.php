<?php
require_once __DIR__ . '/backend/config/config.php';

if (isAuthenticated()) {
    redirect('app/');
}

$isRootPage = true;
include_once __DIR__ . '/app/includes/page-config.php';
setPageConfig(getLoginConfig());
include_once __DIR__ . '/app/includes/head.php';
?>

<body class="min-h-screen bg-white font-sans">
    <div class="flex flex-col md:flex-row min-h-screen w-full items-center justify-center px-2">
        <div class="flex flex-col w-full md:w-1/2 max-w-xl p-6 md:p-16 flex-1 justify-center slide-in-left">
            <div class="flex items-center justify-center mb-8 bounce-in">
                <img src="app/assets/images/logo.png" alt="Sistema de Reservas" class="h-[70px] floating" />
            </div>

            <h2 id="formTitle" class="text-3xl font-bold text-[#111827] leading-9 mb-8 fade-in-down">
                Entrar no Sistema
            </h2>

            <div class="alert-container"></div>

            <form id="authForm" method="POST" class="w-full fade-in-up">
                <input type="hidden" id="formMode" name="mode" value="login">

                <!-- Campo Nome (apenas para cadastro) -->
                <div id="nameField" class="hidden">
                    <label class="block text-sm font-semibold text-[#1E1E1E] mb-3">
                        Nome Completo
                    </label>
                    <div class="relative mb-6">
                        <i data-lucide="user"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"></i>
                        <input type="text" name="name" placeholder="Seu nome completo"
                            class="w-full pl-12 pr-4 py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100" />
                    </div>
                </div>

                <!-- Card do usuário lembrado -->
                <div id="rememberedUserCard" class="hidden mb-4 animate__animated animate__fadeIn">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0 flex-1">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-[#FF6B00] to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span id="userInitials" class="text-white font-bold text-lg"></span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-500 mb-1">Olá novamente,</p>
                                    <p id="rememberedEmail" class="text-base font-medium text-gray-900 truncate"></p>
                                </div>
                            </div>
                            <button type="button" id="switchUserBtn"
                                class="text-[#FF6B00] hover:text-orange-600 text-sm font-medium whitespace-nowrap flex-shrink-0 self-start sm:self-center">
                                Trocar de conta
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="email" id="hiddenEmail" />
                </div>

                <!-- Input normal de email -->
                <div id="emailInputContainer">
                    <label class="block text-sm font-semibold text-[#1E1E1E] mb-3">
                        E-mail
                    </label>
                    <div class="relative mb-6">
                        <i data-lucide="mail"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"></i>
                        <input type="email" name="email" id="emailInput" placeholder="E-mail cadastrado"
                            class="w-full pl-12 pr-4 py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100"
                            required />
                    </div>
                </div>

                <label class="block text-sm font-semibold text-[#1E1E1E] mb-3">
                    Senha
                </label>
                <div class="relative mb-6">
                    <i data-lucide="lock"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"></i>
                    <input type="password" name="password" placeholder="Senha cadastrada"
                        class="w-full pl-12 pr-12 py-4 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:bg-gray-100"
                        required />
                    <button type="button"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                        onclick="togglePassword(this)">
                        <i data-lucide="eye" class="h-5 w-5"></i>
                    </button>
                </div>

                <div id="loginOptions" class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <label for="remember" class="flex items-center cursor-pointer select-none">
                            <span class="relative flex items-center mr-3">
                                <input id="remember" name="remember" type="checkbox"
                                    class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-md transition-colors duration-200 checked:bg-[#FF6B00] checked:border-[#FF6B00] focus:ring-2 focus:ring-[#FF6B00] focus:ring-opacity-50 cursor-pointer" />
                                <svg class="pointer-events-none absolute left-0 top-0 w-5 h-5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-[#1E1E1E] leading-5">Lembrar minha conta</span>
                        </label>
                    </div>
                    <a href="#"
                        class="text-sm font-medium text-[#FF6B00] hover:underline transition-colors duration-200">
                        Esqueceu a senha?
                    </a>
                </div>

                <button type="submit" id="submitButton"
                    class="w-full py-4 text-xl leading-7 font-bold text-white bg-[#FF6B00] hover:bg-orange-600 transition-all duration-200 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Entrar
                </button>

                <div class="text-center text-lg font-normal leading-7 mt-8">
                    <span id="switchText">Não tem uma conta?</span>
                    <a href="#" id="switchLink" class="text-lg font-bold text-[#FF6B00] leading-7 hover:underline ml-1">
                        Cadastre-se
                    </a>
                </div>
            </form>

            <div class="mt-12 text-center">
                <p class="text-xs text-gray-400 mb-2">Desenvolvido por</p>
                <a href="https://avantfiscal.com.br/" target="_blank" rel="noopener noreferrer" class="inline-block">
                    <img src="https://i0.wp.com/avantfiscal.com.br/wp-content/uploads/2020/04/logotipo-avant-fiscal-2022-primary.png?w=512&ssl=1"
                        alt="Avant Fiscal"
                        class="h-8 mx-auto opacity-70 hover:opacity-100 transition-all duration-300 hover:scale-105 cursor-pointer">
                </a>
            </div>
        </div>

        <div class="hidden lg:flex w-1/2 max-w-2xl items-center justify-center p-8">
            <div class="relative bg-[#FF6B00] rounded-2xl overflow-hidden inline-block">
                <img src="app/assets/images/login_right.png" alt="Mulher com notebook"
                    class="block max-w-full max-h-[90vh] object-contain rounded-2xl animate__animated animate__fadeIn animate__delay-1s" />
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/app/includes/scripts.php'; ?>

    <script>
        let isLoginMode = true;

        function toggleFormMode() {
            const formTitle = document.getElementById('formTitle');
            const submitButton = document.getElementById('submitButton');
            const switchText = document.getElementById('switchText');
            const switchLink = document.getElementById('switchLink');
            const nameField = document.getElementById('nameField');
            const loginOptions = document.getElementById('loginOptions');
            const formMode = document.getElementById('formMode');
            const emailInput = document.querySelector('input[name="email"]');
            const nameInput = document.querySelector('input[name="name"]');
            const passwordInput = document.querySelector('input[name="password"]');
            const rememberCheckbox = document.querySelector('input[name="remember"]');

            emailInput.value = '';
            passwordInput.value = '';
            if (nameInput) nameInput.value = '';
            if (rememberCheckbox) {
                rememberCheckbox.checked = false;
                localStorage.removeItem('remember_email');
                localStorage.removeItem('remember_checked');
            }

            document.getElementById('hiddenEmail').value = '';
            document.getElementById('rememberedUserCard').classList.add('hidden');
            document.getElementById('emailInputContainer').classList.remove('hidden');

            formTitle.classList.add('animate__animated', 'animate__fadeOut');

            setTimeout(() => {
                if (isLoginMode) {
                    formTitle.textContent = 'Criar Nova Conta';
                    submitButton.textContent = 'Cadastrar';
                    switchText.textContent = 'Já tem uma conta?';
                    switchLink.textContent = 'Entrar';

                    nameField.classList.remove('hidden');
                    nameField.classList.add('animate__animated', 'animate__fadeInUp');

                    loginOptions.classList.add('animate__animated', 'animate__fadeOutUp');
                    setTimeout(() => {
                        loginOptions.classList.add('hidden');
                        loginOptions.classList.remove('animate__animated', 'animate__fadeOutUp');
                    }, 300);

                    formMode.value = 'register';
                    emailInput.placeholder = 'Seu melhor e-mail';
                    nameInput.required = true;
                    isLoginMode = false;
                } else {
                    formTitle.textContent = 'Entrar no Sistema';
                    submitButton.textContent = 'Entrar';
                    switchText.textContent = 'Não tem uma conta?';
                    switchLink.textContent = 'Cadastre-se';

                    nameField.classList.add('animate__animated', 'animate__fadeOutUp');
                    setTimeout(() => {
                        nameField.classList.add('hidden');
                        nameField.classList.remove('animate__animated', 'animate__fadeOutUp');
                    }, 300);

                    loginOptions.classList.remove('hidden');
                    loginOptions.classList.add('animate__animated', 'animate__fadeInUp');

                    formMode.value = 'login';
                    emailInput.placeholder = 'E-mail cadastrado';
                    nameInput.required = false;
                    isLoginMode = true;
                }

                formTitle.classList.remove('animate__fadeOut');
                formTitle.classList.add('animate__fadeIn');
            }, 200);
        }

        document.getElementById('switchLink').addEventListener('click', function (e) {
            e.preventDefault();
            toggleFormMode();
        });

        document.getElementById('remember').addEventListener('change', function (e) {
            if (!e.target.checked) {
                localStorage.removeItem('remember_email');
                localStorage.removeItem('remember_checked');
            }
        });
    </script>
</body>

</html>