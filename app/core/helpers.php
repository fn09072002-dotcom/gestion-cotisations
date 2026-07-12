
<?php
/**
 * helpers.php — petites fonctions communes utilisées par tous les
 * contrôleurs (redirection, lecture des entrées, CSRF, messages flash).
 */

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function input(string $key, mixed $default = ''): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

function verify_csrf_or_fail(): void
{
    if (!csrf_check($_POST['_csrf'] ?? null)) {
        http_response_code(419);
        echo 'Session expirée, merci de rafraîchir la page et réessayer.';
        exit;
    }
}

function flash(string $type, string $message): void
{
    sess_set('_flash', ['type' => $type, 'message' => $message]);
}

function take_flash(): ?array
{
    $flash = sess_get('_flash');
    sess_remove('_flash');
    return $flash;
}
