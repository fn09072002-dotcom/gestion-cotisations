<h1 class="font-display text-2xl font-bold mb-1">Tableau de bord</h1>
<p class="text-slate-500 mb-6">Vue d'ensemble de la collecte des cotisations.</p>

<div class="grid gap-4">
  <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5">
    <p class="text-sm text-slate-500 mb-1">total caisse</p>
    <p class="font-display text-xl sm:text-2xl font-bold text-brandgreen"><?= number_format($totalCaisse, 0, ',', ' ') ?> FCFA</p>
  </div>

  <div class="grid grid-cols-2 gap-3 sm:gap-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5">
      <p class="text-sm text-slate-500 mb-1">Payés</p>
      <p class="font-display text-xl sm:text-2xl font-bold"><?= (int) $payes ?></p>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5">
      <p class="text-sm text-slate-500 mb-1">Retards</p>
      <p class="font-display text-xl sm:text-2xl font-bold text-danger"><?= (int) $retards ?></p>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5">
    <p class="text-sm text-slate-500 mb-2">Progression globale</p>
    <div class="flex items-center gap-3">
      <div class="flex-1 h-2 rounded-full bg-slate-200 overflow-hidden">
        <div class="h-full rounded-full bg-navy" style="width:<?= (int) $progression ?>%"></div>
      </div>
      <span class="text-sm font-semibold whitespace-nowrap"><?= (int) $progression ?>%</span>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 flex items-center justify-between">
    <span class="text-sm text-slate-500">campagnes ouvertes</span>
    <span class="font-display text-lg font-bold"><?= (int) $campagnesOuvertes ?></span>
  </div>
</div>
