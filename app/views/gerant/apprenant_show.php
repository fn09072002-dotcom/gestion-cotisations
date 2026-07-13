<h1 class="font-display text-2xl font-bold mb-1">Fiche apprenant</h1>
<p class="text-slate-500 mb-6"><?= e($apprenant['nom']) ?></p>

<div class="grid gap-4 max-w-xl">
  <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5">
    <p class="text-sm text-slate-500 mb-1">total payé</p>
    <p class="font-display text-xl sm:text-2xl font-bold text-brandgreen"><?= number_format($totalPaye, 0, ',', ' ') ?> FCFA</p>
  </div>

  <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5">
    <p class="text-sm text-slate-500 mb-2">semaines payées</p>
    <div class="flex items-center gap-3">
      <div class="flex-1 h-2 rounded-full bg-slate-200 overflow-hidden">
        <div class="h-full rounded-full bg-navy" style="width:<?= $totalSemaines > 0 ? round(count($semainesPayees) / $totalSemaines * 100) : 0 ?>%"></div>
      </div>
      <span class="text-sm font-semibold whitespace-nowrap"><?= count($semainesPayees) ?>/<?= (int) $totalSemaines ?></span>
    </div>
  </div>

  <a href="/gerant/paiements/create" class="block w-full text-center bg-brandgreen hover:bg-brandgreen-600 transition text-white font-bold rounded-lg py-3 text-sm">Enregistrer un paiement</a>
</div>
