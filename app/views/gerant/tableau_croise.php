<h1 class="font-display text-2xl font-bold mb-1">Tableau des cotisations</h1>
<p class="text-slate-500 mb-6">Avancement hebdomadaire — vue croisée apprenants / semaines.</p>

<div class="bg-white border border-slate-200 rounded-2xl p-5 overflow-x-auto">
  <table class="w-full text-sm min-w-[420px]">
    <thead>
      <tr class="bg-slate-100">
        <th class="text-left py-2 px-2 rounded-l-lg">Nom</th>
        <?php foreach ($semaines as $s): ?>
          <th class="py-2 px-2">S<?= (int) $s ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($lignes as $ligne): ?>
        <tr class="border-b border-slate-100">
          <td class="py-2 font-semibold"><?= e($ligne['apprenant']['nom']) ?></td>
          <?php foreach ($ligne['etats'] as $etat): ?>
            <td class="text-center py-2">
              <?php if ($etat === 'payee'): ?>
                <span class="inline-flex w-7 h-7 rounded-full bg-brandgreen items-center justify-center text-white text-xs">&#10003;</span>
              <?php elseif ($etat === 'retard'): ?>
                <span class="inline-flex w-7 h-7 rounded-full bg-danger items-center justify-center text-white text-xs">&#10005;</span>
              <?php else: ?>
                <span class="inline-flex w-7 h-7 rounded-full border border-slate-300 items-center justify-center text-slate-300 text-xs">&middot;</span>
              <?php endif; ?>
            </td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<p class="text-xs text-slate-400 mt-4">Vert = payée · Rouge = retard (échéance du samedi dépassée) · Gris = semaine à venir.</p>
