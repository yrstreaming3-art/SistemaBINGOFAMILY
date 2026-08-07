<?php if (!defined('APP_PATH')) { http_response_code(403); exit('Acceso directo no permitido.'); } ?>
<header class="topbar">
    <button class="btn-toggle-sidebar" id="btnToggleSidebar" type="button" aria-label="Alternar menu">
        <i class="bi bi-list"></i>
    </button>

    <h1 class="topbar-title"><?= isset($titulo) ? SecurityHelper::sanitize($titulo) : 'Panel' ?></h1>

    <div class="topbar-actions">
        <button type="button" id="btnInstallApp" class="btn-install-app d-none">
            <i class="bi bi-download"></i> <span class="d-none d-md-inline">Instalar App</span>
        </button>

        <span class="topbar-badge">
            <i class="bi bi-shield-check"></i>
            <?= AuthHelper::esSuperAdmin() ? 'Super Administrador' : 'Cliente' ?>
        </span>

        <div class="dropdown">
            <button class="btn-user-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-md-inline"><?= SecurityHelper::sanitize(AuthHelper::nombre()) ?></span>
                <i class="bi bi-chevron-down small-chevron"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item disabled" href="#"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                <li><a class="dropdown-item disabled" href="#"><i class="bi bi-gear me-2"></i>Configuracion</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?= URLROOT ?>/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesion</a></li>
            </ul>
        </div>
    </div>
</header>
