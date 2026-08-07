<?php if (!defined('APP_PATH')) { http_response_code(403); exit('Acceso directo no permitido.'); } ?>

<?php if (!empty($esSuperAdmin)): ?>

    <!-- ==================== DASHBOARD SUPER ADMINISTRADOR ==================== -->
    <div class="row g-4 mb-2">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-card-blue">
                <div class="stat-card-icon"><i class="bi bi-building"></i></div>
                <div class="stat-card-body">
                    <span class="stat-card-value"><?= (int) $totalClientes ?></span>
                    <span class="stat-card-label">Clientes registrados</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-card-gold">
                <div class="stat-card-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div class="stat-card-body">
                    <span class="stat-card-value"><?= (int) $clientesActivos ?></span>
                    <span class="stat-card-label">Licencias activas</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-card-dark">
                <div class="stat-card-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-card-body">
                    <span class="stat-card-value"><?= (int) $clientesPorVencer ?></span>
                    <span class="stat-card-label">Por vencer (7 dias)</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card stat-card-blue">
                <div class="stat-card-icon"><i class="bi bi-cash-coin"></i></div>
                <div class="stat-card-body">
                    <span class="stat-card-value">--</span>
                    <span class="stat-card-label">Ingresos del mes</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-8">
            <div class="panel-card">
                <div class="panel-card-header">
                    <h2><i class="bi bi-building me-2"></i>Clientes recientes</h2>
                </div>
                <div class="panel-card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Subdominio</th>
                                    <th>Estado</th>
                                    <th>Vence</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($clientesRecientes)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Aun no hay clientes registrados en el sistema.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($clientesRecientes as $cliente): ?>
                                        <tr>
                                            <td><?= SecurityHelper::sanitize($cliente['nombre_empresa']) ?></td>
                                            <td><?= SecurityHelper::sanitize($cliente['subdominio']) ?></td>
                                            <td>
                                                <span class="badge-estado badge-estado-<?= SecurityHelper::sanitize($cliente['estado']) ?>">
                                                    <?= SecurityHelper::sanitize(ucfirst($cliente['estado'])) ?>
                                                </span>
                                            </td>
                                            <td><?= $cliente['fecha_fin'] ? date('d/m/Y', strtotime($cliente['fecha_fin'])) : '--' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel-card h-100">
                <div class="panel-card-header">
                    <h2><i class="bi bi-info-circle me-2"></i>Estado del sistema</h2>
                </div>
                <div class="panel-card-body">
                    <ul class="system-status-list">
                        <li><i class="bi bi-check-circle-fill text-success"></i> Conexion a base de datos activa</li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Sesion segura habilitada</li>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Proteccion CSRF activa</li>
                        <li><i class="bi bi-clock-history text-muted"></i> Modulo de facturacion (proximo)</li>
                        <li><i class="bi bi-clock-history text-muted"></i> Modulo de sorteos (proximo)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>

    <!-- ==================== DASHBOARD CLIENTE ==================== -->
    <div class="row g-4">
        <div class="col-12">
            <div class="welcome-banner">
                <div class="welcome-banner-text">
                    <h2>Bienvenido, <?= SecurityHelper::sanitize(AuthHelper::nombre()) ?> <i class="bi bi-stars"></i></h2>
                    <p>Este es el panel de control de tu cuenta. Desde aqui podras gestionar tus sorteos de bingo una vez se habiliten los siguientes modulos.</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="stat-card stat-card-blue">
                <div class="stat-card-icon"><i class="bi bi-grid-3x3-gap-fill"></i></div>
                <div class="stat-card-body">
                    <span class="stat-card-value">0</span>
                    <span class="stat-card-label">Cartones generados</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card stat-card-gold">
                <div class="stat-card-icon"><i class="bi bi-dice-5-fill"></i></div>
                <div class="stat-card-body">
                    <span class="stat-card-value">0</span>
                    <span class="stat-card-label">Sorteos realizados</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card stat-card-dark">
                <div class="stat-card-icon"><i class="bi bi-calendar-check-fill"></i></div>
                <div class="stat-card-body">
                    <span class="stat-card-value">--</span>
                    <span class="stat-card-label">Licencia vigente hasta</span>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
