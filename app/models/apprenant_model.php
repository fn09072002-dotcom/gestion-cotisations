<?php
/**
 * apprenant_model.php — gestion de la classe des apprenants.
 * Lit et écrit exclusivement dans $_SESSION via les fonctions sess_*().
 */

function apprenant_all(): array
{
    return sess_get('apprenants', []);
}

function apprenant_find(int $id): ?array
{
    foreach (apprenant_all() as $a) {
        if ($a['id'] === $id) {
            return $a;
        }
    }
    return null;
}

function apprenant_create(string $nom): array
{
    $nom = trim($nom);
    $id = sess_get('next_apprenant_id', 1);

    $apprenant = ['id' => $id, 'nom' => $nom];
    sess_push('apprenants', $apprenant);
    sess_set('next_apprenant_id', $id + 1);

    return $apprenant;
}

function apprenant_search(string $term): array
{
    $term = mb_strtolower(trim($term));
    if ($term === '') {
        return apprenant_all();
    }
    return array_values(array_filter(
        apprenant_all(),
        fn (array $a) => str_contains(mb_strtolower($a['nom']), $term)
    ));
}
