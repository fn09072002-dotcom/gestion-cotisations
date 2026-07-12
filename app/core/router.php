rtie
<?php
/**
 * router.php — routeur simple et procédural.
 *
 * Associe une méthode HTTP + un chemin exact à une fonction de
 * contrôleur (le nom de la fonction, sous forme de chaîne, ou une
 * closure). Les URLs suivent le modèle /{acteur}/{ressource}/{action}
 * défini au cahier des charges (section 5.1).
 */

$GLOBALS['routes'] = [];

function route_add(string $method, string $path, callable|string $handler): void
{
    $GLOBALS['routes'][strtoupper($method)][route_normalize($path)] = $handler;
}

function route_normalize(string $path): string
{
    $path = rtrim($path, '/');
    return $path === '' ? '/' : $path;
}

function route_dispatch(string $method, string $path): void
{
    $method = strtoupper($method);
    $path = route_normalize($path);

    $handler = $GLOBALS['routes'][$method][$path] ?? null;

    if ($handler === null) {
        route_not_found();
        return;
    }

    call_user_func($handler);
}

function route_not_found(): void
{
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">'
       . '<title>404 — Page introuvable</title></head>'
       . '<body style="font-family:sans-serif; text-align:center; padding:80px;">'
       . '<h1>404</h1><p>Cette page n\'existe pas.</p>'
       . '<a href="/login">Retour à la connexion</a>'
       . '</body></html>';
}
