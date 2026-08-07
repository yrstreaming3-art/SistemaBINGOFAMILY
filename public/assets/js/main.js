/**
 * main.js
 * Script principal del sistema: comportamiento del sidebar responsive
 * y utilidades de la pantalla de login.
 *
 * Ruta: public/assets/js/main.js
 */

// ==========================================
// Registro del Service Worker (habilita instalacion PWA)
// ==========================================
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        // window.APP_URL_ROOT es inyectado por PHP (constante URLROOT) para que
        // funcione igual si el sistema esta en la raiz del dominio o en una subcarpeta.
        const root = window.APP_URL_ROOT || '';
        const swUrl = root + '/sw.js';
        const scopeUrl = root + '/';

        navigator.serviceWorker.register(swUrl, { scope: scopeUrl }).catch(function (err) {
            console.warn('No se pudo registrar el Service Worker:', err);
        });
    });
}

// ==========================================
// Boton de instalacion de la PWA ("Agregar a pantalla de inicio")
// ==========================================
let deferredInstallPrompt = null;

window.addEventListener('beforeinstallprompt', function (event) {
    event.preventDefault();
    deferredInstallPrompt = event;

    const btnInstall = document.getElementById('btnInstallApp');
    if (btnInstall) {
        btnInstall.classList.remove('d-none');
    }
});

document.addEventListener('click', function (event) {
    const btnInstall = event.target.closest('#btnInstallApp');
    if (!btnInstall || !deferredInstallPrompt) {
        return;
    }

    btnInstall.classList.add('d-none');
    deferredInstallPrompt.prompt();

    deferredInstallPrompt.userChoice.finally(function () {
        deferredInstallPrompt = null;
    });
});

window.addEventListener('appinstalled', function () {
    const btnInstall = document.getElementById('btnInstallApp');
    if (btnInstall) {
        btnInstall.classList.add('d-none');
    }
});

document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // Toggle del menu lateral (responsive)
    // ==========================================
    const btnToggleSidebar = document.getElementById('btnToggleSidebar');
    const sidebar = document.getElementById('sidebar');

    if (btnToggleSidebar && sidebar) {
        btnToggleSidebar.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });

        // Cierra el sidebar al hacer click fuera de el (solo en movil)
        document.addEventListener('click', function (event) {
            const isMobile = window.innerWidth < 992;
            const clickedInsideSidebar = sidebar.contains(event.target);
            const clickedToggleBtn = btnToggleSidebar.contains(event.target);

            if (isMobile && sidebar.classList.contains('show') && !clickedInsideSidebar && !clickedToggleBtn) {
                sidebar.classList.remove('show');
            }
        });
    }

    // ==========================================
    // Mostrar / Ocultar contrasena en el login
    // ==========================================
    const btnTogglePassword = document.getElementById('btnTogglePassword');
    const passwordInput = document.getElementById('password');
    const iconTogglePassword = document.getElementById('iconTogglePassword');

    if (btnTogglePassword && passwordInput && iconTogglePassword) {
        btnTogglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            iconTogglePassword.classList.toggle('bi-eye-fill', !isPassword);
            iconTogglePassword.classList.toggle('bi-eye-slash-fill', isPassword);
        });
    }

    // ==========================================
    // Auto-cierre de alertas de error tras 6 segundos
    // ==========================================
    const alertBox = document.querySelector('.alert');
    if (alertBox) {
        setTimeout(function () {
            alertBox.style.transition = 'opacity 0.4s ease';
            alertBox.style.opacity = '0';
            setTimeout(() => alertBox.remove(), 400);
        }, 6000);
    }
});
