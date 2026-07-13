<?php
/**
 * campagne_model.php — cotisations ponctuelles (anniversaires, décès, autres).
 * Lit et écrit exclusivement dans $_SESSION via les fonctions sess_*().
 *
 * Règles métier (cahier des charges §3.3 et §5) :
 *  - anniversaire : montant fixe, collecté sur la dernière semaine du mois.
 *  - décès / cas social : montant libre, clôture automatique à J+7.
 *  - autre : montant et date de clôture définis librement par le Gérant.
 *
 * Le statut (ouverte/terminée) n'est jamais stocké : il est recalculé à
 * chaque lecture en comparant la date du jour à la date de clôture, ce
 * qui joue le rôle de "clôture automatique" en l'absence de tâche
 * planifiée côté serveur.
 */

const CAMPAGNE_TYPE_ANNIVERSAIRE = 'anniversaire';
const CAMPAGNE_TYPE_DECES = 'deces';
const CAMPAGNE_TYPE_AUTRE = 'autre';

function campagne_with_status(array $campagne): array
{
    $campagne['statut'] = strtotime(date('Y-m-d')) >= strtotime($campagne['date_cloture'])
        ? 'terminée'
        : 'ouverte';
    return $campagne;
}

function campagne_all(): array
{
    return array_map('campagne_with_status', sess_get('campagnes', []));
}

function campagne_ouvertes(): array
{
    return array_values(array_filter(campagne_all(), fn (array $c) => $c['statut'] === 'ouverte'));
}

function campagne_terminees(): array
{
    return array_values(array_filter(campagne_all(), fn (array $c) => $c['statut'] === 'terminée'));
}

function campagne_find(int $id): ?array
{
    foreach (campagne_all() as $c) {
        if ($c['id'] === $id) {
            return $c;
        }
    }
    return null;
}

/**
 * @param string      $titre
 * @param string      $type        anniversaire|deces|autre
 * @param float|null  $montantFixe requis pour anniversaire/autre, ignoré pour décès
 * @param string|null $dateCloture format Y-m-d ; ignoré pour décès (calculé automatiquement)
 */
function campagne_create(string $titre, string $type, ?float $montantFixe, ?string $dateCloture): array
{
    $id = sess_get('next_campagne_id', 1);
    $today = date('Y-m-d');

    if ($type === CAMPAGNE_TYPE_DECES) {
        // Clôture automatique 7 jours après la création (règle imposée).
        $montantFixe = null;
        $dateCloture = date('Y-m-d', strtotime($today . ' +7 days'));
    } elseif ($dateCloture === null || $dateCloture === '') {
        // Sécurité : une campagne "autre"/"anniversaire" a toujours une échéance.
        $dateCloture = date('Y-m-d', strtotime($today . ' +30 days'));
    }

    $campagne = [
        'id' => $id,
        'titre' => trim($titre),
        'type' => $type,
        'montant_fixe' => $montantFixe,
        'date_creation' => $today,
        'date_cloture' => $dateCloture,
    ];

    sess_push('campagnes', $campagne);
    sess_set('next_campagne_id', $id + 1);

    return campagne_with_status($campagne);
}
