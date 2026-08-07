<?php if (!defined('APP_PATH')) { http_response_code(403); exit('Acceso directo no permitido.'); } ?>
<footer class="app-footer">
    <div class="app-footer-content">
        <span>&copy; <?= date('Y') ?> <?= SecurityHelper::sanitize(APP_NAME) ?>. Todos los derechos reservados.</span>
        <span class="app-footer-version">v<?= SecurityHelper::sanitize(APP_VERSION) ?></span>
    </div>
</footer>
