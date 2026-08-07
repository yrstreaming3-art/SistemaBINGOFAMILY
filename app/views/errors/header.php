<?php if (!defined('APP_PATH')) { http_response_code(403); exit('Acceso directo no permitido.'); } ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= isset($titulo) ? SecurityHelper::sanitize($titulo) . ' - ' : '' ?><?= SecurityHelper::sanitize(APP_NAME) ?></title>

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

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Estilos propios del sistema -->
    <link rel="stylesheet" href="<?= URLROOT ?>/assets/css/style.css">
    <script>window.APP_URL_ROOT = <?= json_encode(URLROOT) ?>;</script>
</head>
<body class="app-body">

<div class="app-wrapper">
