<h1 class="font-display text-2xl font-bold mb-1">Campagnes</h1>
<p class="text-slate-500 mb-6">Cotisations ponctuelles : anniversaires, décès, autres événements.</p>

<div class="flex gap-3 mb-6 max-w-xl">
  <a href="/gerant/campagnes?onglet=ouvertes" class="flex-1 text-center rounded-lg <?= $onglet !== 'terminees' ? 'bg-navy text-white' : 'bg-white border border-slate-200 text-slate-500' ?> text-sm font-semibold py-2.5">ouvertes</a>
  <a href="/gerant/campagnes?onglet=terminees" class="flex-1 text-center rounded-lg <?= $onglet === 'terminees' ? 'bg-brandgreen text-white' : 'bg-white border border-slate-200 text-slate-500' ?> text-sm font-semibold py-2.5">terminées</a>
</div>

<div class="space-y-4 max-w-xl">
  <?php foreach ($campagnes as $c): ?>
    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
      <div class="min-w-0">
        <h3 class="font-semibold mb-1 break-words"><?= e($c['titre']) ?></h3>
        <p class="text-sm text-slate-500 break-words">
          <?= $c['montant_fixe'] ? 'montant fixe : ' . number_format($c['montant_fixe'], 0, ',', ' ') . ' FCFA' : 'don libre' ?>
        </p>
        <p class="text-sm text-slate-500">cloture : <?= e(date('d/m/Y', strtotime($c['date_cloture']))) ?></p>
      </div>
      <span class="self-start shrink-0 text-xs font-semibold px-3 py-1.5 rounded-full <?= $c['statut'] === 'terminée' ? 'bg-badge-bg text-badge-text' : 'bg-badge-bg text-badge-text' ?>"><?= e($c['statut']) ?></span>
    </div>
  <?php endforeach; ?>
  <?php if (!$campagnes): ?>
    <p class="text-sm text-slate-400">Aucune campagne dans cette catégorie.</p>
  <?php endif; ?>

  <a href="/gerant/campagnes/create" class="block w-full text-center border border-slate-200 hover:border-navy transition rounded-lg py-3 text-sm font-medium text-navy">+ Nouvelle campagne</a>
</div>