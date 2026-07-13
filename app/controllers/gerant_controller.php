<?php
/**
 * gerant_controller.php — Incrément 1 : dashboard, paiements, apprenants,
 * campagnes, tableau croisé, historique (acteur Gérant).
 */

function gerant_dashboard(): void
{
    $user = require_role('gerant');
    $config = sess_get('config');
    $apprenants = apprenant_all();
    $semaine = paiement_semaine_courante();

    $payes = 0;
    foreach ($apprenants as $a) {
        if (in_array($semaine, paiement_semaines_payees($a['id']), true)) {
            $payes++;
        }
    }
    $retards = count(paiement_retardataires());

    $attendu = count($apprenants) * $config['montant_hebdo'] * $semaine;
    $progression = $attendu > 0 ? min(100, round(paiement_total_caisse() / $attendu * 100)) : 0;

    render('gerant/dashboard', [
        'user' => $user,
        'totalCaisse' => paiement_total_caisse(),
        'payes' => $payes,
        'retards' => $retards,
        'progression' => $progression,
        'campagnesOuvertes' => count(campagne_ouvertes()),
        'flash' => take_flash(),
    ]);
}

// -----------------------------------------------------------------
// Paiements
// -----------------------------------------------------------------

function gerant_paiement_create_form(): void
{
    require_role('gerant');
    render('gerant/paiement_create', [
        'apprenants' => apprenant_all(),
        'campagnesOuvertes' => campagne_ouvertes(),
        'montantHebdo' => sess_get('config')['montant_hebdo'],
    ]);
}

function gerant_paiement_create(): void
{
    require_role('gerant');
    verify_csrf_or_fail();

    $apprenantId = (int) input('apprenant_id');
    $montant = (float) str_replace(',', '.', (string) input('montant'));
    $type = (string) input('type');

    if ($apprenantId <= 0 || apprenant_find($apprenantId) === null) {
        flash('error', 'Apprenant invalide.');
        redirect('/gerant/paiements/create');
    }
    if ($montant <= 0) {
        flash('error', 'Le montant doit être supérieur à 0.');
        redirect('/gerant/paiements/create');
    }

    if ($type === 'hebdomadaire') {
        $resultat = paiement_ventiler_hebdomadaire($apprenantId, $montant);
        $nb = count($resultat['semaines']);
        $message = $nb > 0
            ? "Paiement enregistré : {$nb} semaine(s) validée(s)."
            : "Paiement enregistré, mais aucune semaine complète n'a pu être validée (montant insuffisant).";
        if ($resultat['reste'] > 0) {
            $message .= ' Reliquat non affecté : ' . number_format($resultat['reste'], 0, ',', ' ') . ' FCFA.';
        }
        flash('success', $message);
    } else {
        // $type contient l'identifiant de la campagne sélectionnée
        $campagneId = (int) $type;
        $campagne = campagne_find($campagneId);
        if ($campagne === null) {
            flash('error', 'Campagne invalide.');
            redirect('/gerant/paiements/create');
        }
        paiement_creer_campagne($apprenantId, $campagneId, $montant);
        flash('success', 'Paiement enregistré pour la campagne « ' . $campagne['titre'] . ' ».');
    }

    redirect('/gerant/dashboard');
}

// -----------------------------------------------------------------
// Apprenants
// -----------------------------------------------------------------

function gerant_apprenants(): void
{
    require_role('gerant');
    $q = (string) input('q', '');
    render('gerant/apprenants', [
        'apprenants' => apprenant_search($q),
        'q' => $q,
    ]);
}

function gerant_apprenant_create(): void
{
    require_role('gerant');
    verify_csrf_or_fail();

    $nom = trim((string) input('nom'));
    if ($nom === '') {
        flash('error', 'Le nom est obligatoire.');
    } else {
        apprenant_create($nom);
        flash('success', 'Apprenant ajouté.');
    }
    redirect('/gerant/apprenants');
}

function gerant_apprenant_show(): void
{
    require_role('gerant');
    $id = (int) input('id');
    $apprenant = apprenant_find($id);
    if ($apprenant === null) {
        redirect('/gerant/apprenants');
    }

    render('gerant/apprenant_show', [
        'apprenant' => $apprenant,
        'totalPaye' => paiement_total_paye($id),
        'semainesPayees' => paiement_semaines_payees($id),
        'totalSemaines' => sess_get('config')['total_semaines'],
    ]);
}

// -----------------------------------------------------------------
// Campagnes
// -----------------------------------------------------------------

function gerant_campagnes(): void
{
    require_role('gerant');
    $onglet = (string) input('onglet', 'ouvertes');
    render('gerant/campagnes', [
        'onglet' => $onglet,
        'campagnes' => $onglet === 'terminees' ? campagne_terminees() : campagne_ouvertes(),
        'flash' => take_flash(),
    ]);
}

function gerant_campagne_create_form(): void
{
    require_role('gerant');
    render('gerant/campagne_create', []);
}

function gerant_campagne_create(): void
{
    require_role('gerant');
    verify_csrf_or_fail();

    $titre = trim((string) input('titre'));
    $type = (string) input('type');
    $montant = input('montant');
    $dateCloture = (string) input('date_cloture');

    if ($titre === '' || !in_array($type, ['anniversaire', 'deces', 'autre'], true)) {
        flash('error', 'Merci de renseigner un titre et un type valides.');
        redirect('/gerant/campagnes/create');
    }

    campagne_create(
        $titre,
        $type,
        $montant !== '' ? (float) $montant : null,
        $dateCloture !== '' ? $dateCloture : null
    );

    flash('success', 'Campagne créée.');
    redirect('/gerant/campagnes');
}

// -----------------------------------------------------------------
// Tableau croisé et historique
// -----------------------------------------------------------------

function gerant_tableau_croise(): void
{
    require_role('gerant');
    $semaineCourante = paiement_semaine_courante();
    $premiere = max(1, $semaineCourante - 4);
    $semaines = range($premiere, $premiere + 4);

    $lignes = [];
    foreach (apprenant_all() as $a) {
        $payees = paiement_semaines_payees($a['id']);
        $etats = [];
        foreach ($semaines as $s) {
            if (in_array($s, $payees, true)) {
                $etats[$s] = 'payee';
            } elseif (paiement_est_en_retard($a['id'], $s)) {
                $etats[$s] = 'retard';
            } else {
                $etats[$s] = 'a_venir';
            }
        }
        $lignes[] = ['apprenant' => $a, 'etats' => $etats];
    }

    render('gerant/tableau_croise', [
        'semaines' => $semaines,
        'lignes' => $lignes,
    ]);
}

function gerant_historique(): void
{
    require_role('gerant');
    render('gerant/historique', [
        'paiements' => paiement_historique(),
    ]);
}
