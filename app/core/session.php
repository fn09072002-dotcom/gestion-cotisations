<?php
/**
 * session.php — point d'accès unique à $_SESSION (procédural).
 *
 * Toute l'application (modèles inclus) passe par ces fonctions :
 * aucun autre fichier ne doit lire ou écrire $_SESSION directement.
 */

function session_manager_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            // 'secure' => true, // à activer derrière HTTPS en production
        ]);
        session_start();
    }

    // Régénère l'identifiant de session périodiquement (limite la fixation de session).
    if (!isset($_SESSION['_last_regen'])) {
        $_SESSION['_last_regen'] = time();
    } elseif (time() - $_SESSION['_last_regen'] > 900) {
        session_regenerate_id(true);
        $_SESSION['_last_regen'] = time();
    }
}

function sess_get(string $key, mixed $default = null): mixed
{
    return $_SESSION[$key] ?? $default;
}

function sess_set(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

function sess_has(string $key): bool
{
    return isset($_SESSION[$key]);
}

function sess_remove(string $key): void
{
    unset($_SESSION[$key]);
}

function sess_push(string $key, mixed $value): void
{
    $_SESSION[$key][] = $value;
}

function sess_destroy(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

// -----------------------------------------------------------------
// Authentification / autorisation
// -----------------------------------------------------------------

function auth_login(array $user): void
{
    session_regenerate_id(true);
    sess_set('user', $user);
}

function auth_user(): ?array
{
    return sess_get('user');
}

function auth_check(): bool
{
    return sess_has('user');
}

/**
 * Vérifie que l'utilisateur est connecté et possède le bon rôle.
 * Redirige vers /login sinon, et arrête l'exécution.
 */
function require_role(string $role): array
{
    $user = auth_user();
    if ($user === null) {
        header('Location: /login');
        exit;
    }
    if ($user['role'] !== $role) {
        http_response_code(403);
        echo 'Accès refusé pour ce profil.';
        exit;
    }
    return $user;
}


function csrf_token(): string
{
    if (!sess_has('_csrf')) {
        sess_set('_csrf', bin2hex(random_bytes(32)));
    }
    return sess_get('_csrf');
}

function csrf_check(?string $token): bool
{
    return is_string($token) && hash_equals(sess_get('_csrf', ''), $token);
}
