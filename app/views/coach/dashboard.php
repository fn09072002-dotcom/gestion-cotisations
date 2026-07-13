<div class="flex items-center gap-1.5 text-xs text-ink-soft mb-4">
  <i class="ti ti-eye"></i>Mode lecture seule
</div>

<section class="grid grid-cols-2 gap-2 mb-6">
  <div class="bg-white rounded-xl p-3">
    <p class="text-[11px] text-ink-soft mb-1">Trésorerie totale</p>
    <p class="text-xl font-medium">612 000 F</p>
  </div>
  <div class="bg-white rounded-xl p-3">
    <p class="text-[11px] text-ink-soft mb-1">Recouvrement</p>
    <p class="text-xl font-medium">81%</p>
  </div>
  <div class="bg-white rounded-xl p-3">
    <p class="text-[11px] text-ink-soft mb-1">Campagnes actives</p>
    <p class="text-xl font-medium">2</p>
  </div>
  <div class="bg-white rounded-xl p-3">
    <p class="text-[11px] text-ink-soft mb-1">En retard</p>
    <p class="text-xl font-medium text-danger">5</p>
  </div>
</section>

<section class="mb-6">
  <h2 class="font-medium text-sm mb-2">Recouvrement par semaine</h2>
  <div class="bg-white rounded-xl p-3 flex items-end gap-2 h-24">
    <?php $semaines = ['S1' => 65, 'S2' => 80, 'S3' => 45, 'S4' => 85, 'S5' => 30]; ?>
    <?php foreach ($semaines as $label => $valeur): ?>
    <div class="flex-1 flex flex-col items-center gap-1">
      <div class="w-full rounded-t <?= $valeur < 50 ? 'bg-danger/70' : 'bg-success/70' ?>" style="height: <?= $valeur ?>%;"></div>
      <span class="text-[10px] text-ink-soft"><?= $label ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<section>
  <h2 class="font-medium text-sm mb-2">Journal d'audit</h2>
  <div class="flex flex-col">
    <div class="flex gap-3 py-2 border-t border-ink/10">
      <i class="ti ti-cash text-ink-soft mt-0.5"></i>
      <div>
        <p class="text-sm">Paiement de 5 000 F — Aïssatou D.</p>
        <p class="text-xs text-ink-soft">Il y a 2 heures</p>
      </div>
    </div>
    <div class="flex gap-3 py-2 border-t border-ink/10">
      <i class="ti ti-flag text-ink-soft mt-0.5"></i>
      <div>
        <p class="text-sm">Campagne "Cas social — famille Diop" créée</p>
        <p class="text-xs text-ink-soft">Hier, 16:40</p>
      </div>
    </div>
    <div class="flex gap-3 py-2 border-t border-ink/10">
      <i class="ti ti-alert-triangle text-ink-soft mt-0.5"></i>
      <div>
        <p class="text-sm">5 retards détectés — semaine S5</p>
        <p class="text-xs text-ink-soft">Il y a 3 jours</p>
      </div>
    </div>
  </div>
</section>