<?php
/**
 * paiement_model.php — saisie déclarative des paiements (hebdomadaires
 * et cotisations ponctuelles), et calcul du tableau croisé des semaines.
 * Lit et écrit exclusivement dans $_SESSION via les fonctions sess_*().
 */

function paiement_all(): array
{
    return sess_get('paiements', []);
}

function paiement_for_apprenant(int $apprenantId): array
{
    return array_values(array_filter(
        paiement_all(),
        fn (array $p) => $p['apprenant_id'] === $apprenantId
    ));
}

function paiement_total_caisse(): float
{
    return array_sum(array_column(paiement_all(), 'montant'));
}

function paiement_total_paye(int $apprenantId): float
{
    return array_sum(array_column(paiement_for_apprenant($apprenantId), 'montant'));
}

/** Numéros de semaines déjà réglées (cotisation hebdomadaire) pour un apprenant. */
function paiement_semaines_payees(int $apprenantId): array
{
    $semaines = [];
    foreach (paiement_for_apprenant($apprenantId) as $p) {
        if ($p['type'] === 'hebdomadaire') {
            $semaines[] = (int) $p['semaine'];
        }
    }
    sort($semaines);
    return $semaines;
}

// -----------------------------------------------------------------
// Semaine courante et échéances (samedi 00h00, cf. cahier des charges)
// -----------------------------------------------------------------

function paiement_semaine_courante(): int
{
    $config = sess_get('config');
    $debut = new DateTimeImmutable($config['date_debut']);
    $aujourdhui = new DateTimeImmutable('today');

    $diffJours = (int) $debut->diff($aujourdhui)->format('%r%a');
    $semaine = intdiv(max($diffJours, 0), 7) + 1;

    return min($semaine, $config['total_semaines']);
}

function paiement_date_limite(int $semaine): DateTimeImmutable
{
    $config = sess_get('config');
    $debutSemaine = (new DateTimeImmutable($config['date_debut']))->modify('+' . (7 * ($semaine - 1)) . ' days');
    // Lundi + 5 jours = samedi de la même semaine, à 00h00.
    return $debutSemaine->modify('+5 days')->setTime(0, 0);
}

/** Une semaine est en retard si la date limite (samedi 00h00) est dépassée et non payée. */
function paiement_est_en_retard(int $apprenantId, int $semaine): bool
{
    if (in_array($semaine, paiement_semaines_payees($apprenantId), true)) {
        return false;
    }
    return (new DateTimeImmutable('today')) >= paiement_date_limite($semaine);
}

/** Liste des apprenants n'ayant pas payé la semaine courante après échéance. */
function paiement_retardataires(): array
{
    $semaine = paiement_semaine_courante();
    $retardataires = [];
    foreach (apprenant_all() as $a) {
        if (paiement_est_en_retard($a['id'], $semaine)) {
            $retardataires[] = $a;
        }
    }
    return $retardataires;
}

// -----------------------------------------------------------------
// Enregistrement des paiements
// -----------------------------------------------------------------

function paiement_next_id(): int
{
    $id = sess_get('next_paiement_id', 1);
    sess_set('next_paiement_id', $id + 1);
    return $id;
}

/**
 * Ventilation automatique : le montant versé est divisé par le tarif
 * hebdomadaire pour déterminer combien de semaines consécutives non
 * payées peuvent être validées, en commençant par la plus ancienne.
 *
 * @return array{semaines: int[], reste: float}
 */
function paiement_ventiler_hebdomadaire(int $apprenantId, float $montant): array
{
    $config = sess_get('config');
    $tarif = (float) $config['montant_hebdo'];
    $totalSemaines = (int) $config['total_semaines'];

    $nbSemainesCouvertes = $tarif > 0 ? intdiv((int) $montant, (int) $tarif) : 0;
    $reste = $montant - ($nbSemainesCouvertes * $tarif);

    $dejaPayees = paiement_semaines_payees($apprenantId);
    $semainesValidees = [];

    for ($s = 1; $s <= $totalSemaines && count($semainesValidees) < $nbSemainesCouvertes; $s++) {
        if (!in_array($s, $dejaPayees, true)) {
            $semainesValidees[] = $s;
            sess_push('paiements', [
                'id' => paiement_next_id(),
                'apprenant_id' => $apprenantId,
                'type' => 'hebdomadaire',
                'semaine' => $s,
                'montant' => $tarif,
                'date' => date('Y-m-d'),
            ]);
        }
    }

    return ['semaines' => $semainesValidees, 'reste' => $reste];
}

function paiement_creer_campagne(int $apprenantId, int $campagneId, float $montant): array
{
    $paiement = [
        'id' => paiement_next_id(),
        'apprenant_id' => $apprenantId,
        'type' => 'campagne',
        'campagne_id' => $campagneId,
        'montant' => $montant,
        'date' => date('Y-m-d'),
    ];
    sess_push('paiements', $paiement);
    return $paiement;
}

/** Historique trié du plus récent au plus ancien, avec libellé lisible. */
function paiement_historique(): array
{
    $paiements = paiement_all();
    usort($paiements, fn ($a, $b) => strcmp($b['date'], $a['date']) ?: $b['id'] <=> $a['id']);

    return array_map(function (array $p) {
        $apprenant = apprenant_find($p['apprenant_id']);
        $p['apprenant_nom'] = $apprenant['nom'] ?? 'Apprenant supprimé';
        if ($p['type'] === 'campagne') {
            $campagne = campagne_find($p['campagne_id']);
            $p['libelle'] = $campagne['titre'] ?? 'Campagne';
        } else {
            $p['libelle'] = 'Semaine ' . $p['semaine'];
        }
        return $p;
    }, $paiements);
}
