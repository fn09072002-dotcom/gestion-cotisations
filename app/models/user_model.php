<?php
/**
 * user_model.php — comptes de connexion (un par acteur dans cette démo).
 * Lit et écrit exclusivement dans $_SESSION via les fonctions sess_*().
 */

function user_find_by_email(string $email): ?array
{
    foreach (sess_get('users', []) as $user) {
        if (strcasecmp($user['email'], $email) === 0) {
            return $user;
        }
    }
    return null;
}

function user_verify(string $email, string $password): ?array
{
    $user = user_find_by_email($email);
    if ($user === null) {
        return null;
    }
    if (!password_verify($password, $user['password_hash'])) {
        return null;
    }
    // Ne jamais garder le hash en session applicative une fois authentifié.
    unset($user['password_hash']);
    return $user;
}
