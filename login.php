<?php if (!defined('APP_PATH')) { http_response_code(403); exit('Acceso directo no permitido.'); } ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= SecurityHelper::sanitize($titulo) ?> - <?= SecurityHelper::sanitize(APP_NAME) ?></title>

    <!-- ===== PWA: instalable en pantalla de inicio ===== -->
    <link rel="manifest" href="<?= URLROOT ?>/manifest.json">
    <meta name="theme-color" content="#0B2E59">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Bingo SaaS">
    <link rel="apple-touch-icon" href="<?= URLROOT ?>/assets/img/icons/icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= URLROOT ?>/assets/img/icons/icon-192x192.png">
    <link rel="shortcut icon" href="<?= URLROOT ?>/favicon.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/css/style.css">
    <script>window.APP_URL_ROOT = <?= json_encode(URLROOT) ?>;</script>
</head>
<body class="login-body">

    <div class="login-bg-decor" aria-hidden="true"></div>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-brand">
                <div class="login-brand-icon">
                    <i class="bi bi-circle-fill"></i>
                    <span>B</span>
                </div>
                <h1 class="login-brand-name"><?= SecurityHelper::sanitize(APP_NAME) ?></h1>
                <p class="login-brand-tagline">Plataforma profesional de Bingo Online</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div><?= SecurityHelper::sanitize($error) ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= URLROOT ?>/auth/authenticate" method="POST" class="login-form" autocomplete="off" novalidate>
                <?= SecurityHelper::csrfField() ?>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electronico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="correo@empresa.com"
                            required
                            maxlength="150"
                            autofocus
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contrasena</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="********"
                            required
                            maxlength="100"
                        >
                        <button class="btn btn-outline-secondary" type="button" id="btnTogglePassword" tabindex="-1">
                            <i class="bi bi-eye-fill" id="iconTogglePassword"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" disabled>
                        <label class="form-check-label text-muted" for="remember">Recordarme</label>
                    </div>
                    <a href="#" class="link-forgot disabled" tabindex="-1">Olvide mi contrasena</a>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                </button>
            </form>

            <div class="login-footer-note">
                <i class="bi bi-shield-lock-fill"></i>
                Acceso restringido y protegido. Todos los intentos son registrados.
            </div>

            <button type="button" id="btnInstallApp" class="btn-install-app-login d-none w-100 mt-3">
                <i class="bi bi-download"></i> Instalar aplicacion en este dispositivo
            </button>
        </div>

        <p class="login-copyright">&copy; <?= date('Y') ?> <?= SecurityHelper::sanitize(APP_NAME) ?> &mdash; v<?= SecurityHelper::sanitize(APP_VERSION) ?></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= URLROOT ?>/assets/js/main.js"></script>
</body>
</html>
