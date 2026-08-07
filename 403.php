<?php if (!defined('APP_PATH')) { http_response_code(403); exit('Acceso directo no permitido.'); } ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso denegado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= defined('URLROOT') ? URLROOT : '' ?>/assets/css/style.css">
</head>
<body class="error-body">
    <div class="error-wrapper">
        <i class="bi bi-shield-lock-fill error-icon"></i>
        <h1 class="error-code">403</h1>
        <p class="error-message">No tienes permisos suficientes para acceder a este recurso.</p>
        <a href="<?= defined('URLROOT') ? URLROOT : '/' ?>/dashboard" class="btn btn-login mt-3">
            <i class="bi bi-house-fill me-1"></i> Volver al inicio
        </a>
    </div>
</body>
</html>
