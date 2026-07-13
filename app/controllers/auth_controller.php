<?php
/**
 * auth_controller.php — connexion / déconnexion pour les 3 rôles.
 */

function auth_show_login(): void
{
    if (auth_check()) {
        $user = auth_user();
        redirect('/' . $user['role'] . '/dashboard');
    }

    render('auth/login', [
        'error' => sess_get('_login_error'),
    ], null);
    sess_remove('_login_error');
}

function auth_login_submit(): void
{
    verify_csrf_or_fail();

    $email = trim((string) input('email'));
    $password = (string) input('mot_de_passe');

    $user = user_verify($email, $password);

    if ($user === null) {
        sess_set('_login_error', 'Email ou mot de passe incorrect.');
        redirect('/login');
    }

    auth_login($user);
    redirect('/' . $user['role'] . '/dashboard');
}

function auth_logout(): void
{
    sess_destroy();
    header('Location: /login');
    exit;
}
