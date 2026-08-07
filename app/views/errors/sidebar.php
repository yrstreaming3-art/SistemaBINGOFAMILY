<?php if (!defined('APP_PATH')) { http_response_code(403); exit('Acceso directo no permitido.'); } ?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-circle-fill brand-ball"></i>
        <span class="brand-text"><?= SecurityHelper::sanitize(APP_NAME) ?></span>
    </div>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="sidebar-user-info">
            <span class="sidebar-user-name"><?= SecurityHelper::sanitize(AuthHelper::nombre()) ?></span>
            <span class="sidebar-user-role">
                <?= AuthHelper::esSuperAdmin() ? 'Super Administrador' : 'Cliente' ?>
            </span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="<?= URLROOT ?>/dashboard">
                    <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
                </a>
            </li>

            <?php if (AuthHelper::esSuperAdmin()): ?>
                <li class="nav-section-title">Administracion SaaS</li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1">
                        <i class="bi bi-building"></i> <span>Clientes</span>
                        <span class="badge-soon">Proximo</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1">
                        <i class="bi bi-credit-card-2-front"></i> <span>Planes y Licencias</span>
                        <span class="badge-soon">Proximo</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1">
                        <i class="bi bi-people"></i> <span>Usuarios</span>
                        <span class="badge-soon">Proximo</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-section-title">Bingo</li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1">
                        <i class="bi bi-grid-3x3-gap-fill"></i> <span>Cartones</span>
                        <span class="badge-soon">Proximo</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1">
                        <i class="bi bi-dice-5-fill"></i> <span>Sorteos</span>
                        <span class="badge-soon">Proximo</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-section-title">Cuenta</li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1">
                    <i class="bi bi-gear-fill"></i> <span>Configuracion</span>
                    <span class="badge-soon">Proximo</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= URLROOT ?>/auth/logout">
                    <i class="bi bi-box-arrow-right"></i> <span>Cerrar Sesion</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
