
<?php
/**
 * view.php — rendu des vues (procédural).
 *
 * Charge un template dans app/views, l'injecte dans le gabarit
 * (layout) partagé, avec échappement systématique via e().
 */

function render_view(string $template, array $data = [], ?string $layout = 'layout/app'): void
{
    extract($data, EXTR_SKIP);

    $viewFile = APP_ROOT . '/app/views/' . $template . '.php';
    if (!is_file($viewFile)) {
        throw new RuntimeException("Vue introuvable : {$template}");
    }

    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    if ($layout === null) {
        echo $content;
        return;
    }

    $layoutFile = APP_ROOT . '/app/views/' . $layout . '.php';
    require $layoutFile;
}

/** Échappement HTML court, à utiliser systématiquement dans les vues. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Rendu commun à tous les contrôleurs : ajoute l'utilisateur courant + le jeton CSRF. */
function render(string $template, array $data = [], ?string $layout = 'layout/app'): void
{
    $data['currentUser'] = auth_user();
    $data['csrfToken'] = csrf_token();
    render_view($template, $data, $layout);
}
