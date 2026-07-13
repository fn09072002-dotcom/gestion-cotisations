<h1 class="font-display text-2xl font-bold mb-1">Nouveau paiement</h1>
<p class="text-slate-500 mb-6">Saisie déclarative après réception des fonds.</p>

<form action="/gerant/paiements/create" method="post" class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 max-w-xl">
  <input type="hidden" name="_csrf" value="<?= e($csrfToken) ?>">

  <div>
    <label class="text-sm text-slate-500 mb-1 block">Apprenant</label>
    <select name="apprenant_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm" required>
      <option value="">-- choisir --</option>
      <?php foreach ($apprenants as $a): ?>
        <option value="<?= (int) $a['id'] ?>"><?= e($a['nom']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label class="text-sm text-slate-500 mb-1 block">montant (FCFA)</label>
    <input type="number" name="montant" min="1" step="1" value="<?= (int) $montantHebdo ?>"
      class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-mono" required>
  </div>

  <div>
    <label class="text-sm text-slate-500 mb-2 block">type de paiement</label>
    <div class="space-y-3">
      <label class="flex items-center gap-3 text-sm">
        <input type="radio" name="type" value="hebdomadaire" checked class="accent-brandgreen w-4 h-4"> Hebdomadaire
      </label>
      <?php foreach ($campagnesOuvertes as $c): ?>
        <label class="flex items-center gap-3 text-sm">
          <input type="radio" name="type" value="<?= (int) $c['id'] ?>" class="accent-brandgreen w-4 h-4">
          <?= e($c['titre']) ?>
          <?php if ($c['montant_fixe']): ?>
            <span class="text-slate-400">(<?= number_format($c['montant_fixe'], 0, ',', ' ') ?> FCFA)</span>
          <?php else: ?>
            <span class="text-slate-400">(montant libre)</span>
          <?php endif; ?>
        </label>
      <?php endforeach; ?>
      <?php if (!$campagnesOuvertes): ?>
        <p class="text-xs text-slate-400">Aucune campagne ouverte actuellement.</p>
      <?php endif; ?>
    </div>
  </div>

  <button type="submit" class="w-full bg-brandgreen hover:bg-brandgreen-600 transition text-white font-bold rounded-lg py-3 text-sm">Enregistrer</button>
</form>
