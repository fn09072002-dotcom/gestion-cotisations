<?php
/**
 * Front Controller — point d'entrée unique de l'application (procédural).
 * Toutes les requêtes HTTP passent par ce fichier.
 */

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/app/core/session.php';
require APP_ROOT . '/app/core/router.php';
require APP_ROOT . '/app/core/view.php';
require APP_ROOT . '/app/core/helpers.php';
require APP_ROOT . '/app/core/seed.php';

require APP_ROOT . '/app/models/user_model.php';
require APP_ROOT . '/app/models/apprenant_model.php';
require APP_ROOT . '/app/models/campagne_model.php';
require APP_ROOT . '/app/models/paiement_model.php';

require APP_ROOT . '/app/controllers/auth_controller.php';
require APP_ROOT . '/app/controllers/gerant_controller.php';
require APP_ROOT . '/app/controllers/apprenant_controller.php';
require APP_ROOT . '/app/controllers/coach_controller.php';

// --- Session sécurisée ---
session_manager_start();

// --- Jeu de données de démonstration (première visite uniquement) ---
seed_run();

// --- Routage ---

// Authentification
route_add('GET',  '/login',  'auth_show_login');
route_add('POST', '/login',  'auth_login_submit');
route_add('GET',  '/logout', 'auth_logout');

// Gérant
route_add('GET',  '/gerant/dashboard',        'gerant_dashboard');
route_add('GET',  '/gerant/paiements/create', 'gerant_paiement_create_form');
route_add('POST', '/gerant/paiements/create', 'gerant_paiement_create');
route_add('GET',  '/gerant/apprenants',       'gerant_apprenants');
route_add('POST', '/gerant/apprenants',       'gerant_apprenant_create');
route_add('GET',  '/gerant/apprenants/show',  'gerant_apprenant_show');
route_add('GET',  '/gerant/campagnes',        'gerant_campagnes');
route_add('GET',  '/gerant/campagnes/create', 'gerant_campagne_create_form');
route_add('POST', '/gerant/campagnes/create', 'gerant_campagne_create');
route_add('GET',  '/gerant/tableau-croise',   'gerant_tableau_croise');
route_add('GET',  '/gerant/historique',       'gerant_historique');

// Apprenant
route_add('GET', '/apprenant/dashboard',    'apprenant_ctrl_dashboard');
route_add('GET', '/apprenant/mes-semaines', 'apprenant_ctrl_mes_semaines');
route_add('GET', '/apprenant/campagnes',    'apprenant_ctrl_campagnes');
route_add('GET', '/apprenant/historique',   'apprenant_ctrl_historique');

// Coach
route_add('GET', '/coach/dashboard', 'coach_ctrl_dashboard');
route_add('GET', '/coach/audit',     'coach_ctrl_audit');

// Racine -> redirige vers le login ou le dashboard selon la session
route_add('GET', '/', function () {
    if (auth_check()) {
        $user = auth_user();
        header('Location: /' . $user['role'] . '/dashboard');
        exit;
    }
    header('Location: /login');
    exit;
});

route_dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));