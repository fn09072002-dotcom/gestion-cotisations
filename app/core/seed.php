
<?php
/**
 * seed.php — jeu de données de démonstration (procédural).
 *
 * Initialise $_SESSION au premier accès, pour que l'application soit
 * utilisable immédiatement (sans base de données ni script d'install).
 */

function seed_run(): void
{
    if (sess_has('seeded')) {
        return;
    }

    sess_set('config', [
        'montant_hebdo'  => 1000,
        'total_semaines' => 40,
        'date_debut'     => '2026-06-01', // lundi de la semaine 1
    ]);

    sess_set('apprenants', [
        ['id' => 1, 'nom' => 'Fatou Ndiaye'],
        ['id' => 2, 'nom' => 'Awa Sall'],
        ['id' => 3, 'nom' => 'Mariama Ndour'],
        ['id' => 4, 'nom' => 'Mouhamed Ba'],
        ['id' => 5, 'nom' => 'Mame Fatou'],
        ['id' => 6, 'nom' => 'Daba Diouf'],
        ['id' => 7, 'nom' => 'Rama Diop'],
        ['id' => 8, 'nom' => 'Baba Kane'],
        ['id' => 9, 'nom' => 'Lala Sy'],
        ['id' => 10, 'nom' => 'Aliou Fall'],
        ['id' => 11, 'nom' => 'Bada Cissé'],
    ]);

    sess_set('users', [
        [
            'id' => 1, 'role' => 'gerant', 'nom' => 'Fatou Ndiaye',
            'email' => 'gerant@cotise.sn',
            'password_hash' => password_hash('gerant123', PASSWORD_DEFAULT),
            'apprenant_id' => 1,
        ],
        [
            'id' => 2, 'role' => 'apprenant', 'nom' => 'Awa Sall',
            'email' => 'apprenant@cotise.sn',
            'password_hash' => password_hash('apprenant123', PASSWORD_DEFAULT),
            'apprenant_id' => 2,
        ],
        [
            'id' => 3, 'role' => 'coach', 'nom' => 'Baila Wane',
            'email' => 'coach@cotise.sn',
            'password_hash' => password_hash('coach123', PASSWORD_DEFAULT),
            'apprenant_id' => null,
        ],
    ]);

    sess_set('campagnes', [
        [
            'id' => 1, 'titre' => 'Anniversaire - juin', 'type' => 'anniversaire',
            'montant_fixe' => 500, 'date_creation' => '2026-06-01', 'date_cloture' => '2026-06-30',
        ],
        [
            'id' => 2, 'titre' => 'Décès de papa de X', 'type' => 'deces',
            'montant_fixe' => null, 'date_creation' => '2026-07-13', 'date_cloture' => '2026-07-20',
        ],
        [
            'id' => 3, 'titre' => 'Achat materiel', 'type' => 'autre',
            'montant_fixe' => null, 'date_creation' => '2026-07-01', 'date_cloture' => '2026-08-01',
        ],
    ]);

    // Quelques paiements hebdomadaires de démonstration (apprenant #2 = Awa Sall)
    $paiements = [];
    $pid = 1;
    for ($s = 1; $s <= 7; $s++) {
        $paiements[] = [
            'id' => $pid++, 'apprenant_id' => 2, 'type' => 'hebdomadaire',
            'semaine' => $s, 'montant' => 1000, 'date' => '2026-06-' . str_pad((string) ($s * 3), 2, '0', STR_PAD_LEFT),
        ];
    }
    $paiements[] = ['id' => $pid++, 'apprenant_id' => 2, 'type' => 'campagne', 'campagne_id' => 1, 'montant' => 200, 'date' => '2026-06-15'];
    $paiements[] = ['id' => $pid++, 'apprenant_id' => 2, 'type' => 'campagne', 'campagne_id' => 3, 'montant' => 20000, 'date' => '2026-06-22'];

    sess_set('paiements', $paiements);
    sess_set('next_paiement_id', $pid);
    sess_set('next_apprenant_id', 12);
    sess_set('next_campagne_id', 4);

    sess_set('seeded', true);
}
