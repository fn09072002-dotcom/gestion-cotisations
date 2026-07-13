<h1 class="font-display text-2xl font-bold mb-1">Nouvelle campagne</h1>
<p class="text-slate-500 mb-6">Cotisation ponctuelle selon le type d'événement.</p>

<form action="/gerant/campagnes/create" method="post" class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 max-w-xl">
  <input type="hidden" name="_csrf" value="<?= e($csrfToken) ?>">

  <div>
    <label class="text-sm text-slate-500 mb-1 block">Type d'événement</label>
    <select name="type" id="type-evenement" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm"
      onchange="
        document.getElementById('montant-libre-note').classList.toggle('hidden', this.value!=='deces');
        document.getElementById('cloture-field').classList.toggle('hidden', this.value==='deces');
        document.getElementById('cloture-note').textContent = this.value==='deces' ? 'Clôture automatique 7 jours après création.' : this.value==='anniversaire' ? 'Collecte lors de la dernière semaine du mois.' : 'Définissez librement la date de clôture.';
      ">
      <option value="anniversaire">Anniversaire</option>
      <option value="deces">Cas social / Décès</option>
      <option value="autre">Autre (matériel, sorties...)</option>
    </select>
  </div>

  <div>
    <label class="text-sm text-slate-500 mb-1 block">Titre de la campagne</label>
    <input type="text" name="titre" placeholder="ex. Décès de la maman de Rama" required
      class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm">
  </div>

  <div>
    <label class="text-sm text-slate-500 mb-1 block">Montant (FCFA)</label>
    <input type="number" name="montant" placeholder="ex. 500"
      class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-mono">
    <p id="montant-libre-note" class="hidden text-xs text-slate-400 mt-2">Montant libre pour les cas sociaux : ce champ est ignoré, chaque apprenant donne selon ses moyens.</p>
  </div>

  <div id="cloture-field">
    <label class="text-sm text-slate-500 mb-1 block">Date de clôture</label>
    <input type="date" name="date_cloture" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm">
    <p id="cloture-note" class="text-xs text-slate-400 mt-2">Collecte lors de la dernière semaine du mois.</p>
  </div>

  <button type="submit" class="w-full bg-brandgreen hover:bg-brandgreen-600 transition text-white font-bold rounded-lg py-3 text-sm">Créer la campagne</button>
</form>
