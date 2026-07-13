<h1 class="font-display text-2xl font-bold mb-1">Historique</h1>
<p class="text-slate-500 mb-6">Tous les paiements enregistrés.</p>

<div class="space-y-4 max-w-xl">
  <?php foreach ($paiements as $p): ?>
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
      <p class="text-sm text-slate-500 mb-1"><?= e($p['libelle']) ?> — <?= e($p['apprenant_nom']) ?></p>
      <p class="font-semibold text-lg"><?= number_format($p['montant'], 0, ',', ' ') ?> FCFA</p>
      <p class="text-xs text-slate-400 mt-1"><?= e(date('d/m/Y', strtotime($p['date']))) ?></p>
    </div>
  <?php endforeach; ?>
  <?php if (!$paiements): ?>
    <p class="text-sm text-slate-400">Aucun paiement enregistré pour le moment.</p>
  <?php endif; ?>
</div>
