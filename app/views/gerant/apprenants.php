<h1 class="font-display text-2xl font-bold mb-1">Liste des apprenants</h1>
<p class="text-slate-500 mb-6">Gestion (CRUD) de la classe.</p>

<form action="/gerant/apprenants" method="get" class="flex gap-3 mb-4 max-w-xl">
  <input type="text" name="q" value="<?= e($q) ?>" placeholder="Rechercher un apprenant"
    class="flex-1 border border-slate-200 bg-white rounded-lg px-4 py-2.5 text-sm">
  <button type="submit" class="px-4 rounded-lg border border-slate-200 text-sm font-medium">Chercher</button>
</form>

<form action="/gerant/apprenants" method="post" class="flex gap-3 mb-6 max-w-xl">
  <input type="hidden" name="_csrf" value="<?= e($csrfToken) ?>">
  <input type="text" name="nom" placeholder="Nom du nouvel apprenant" required
    class="flex-1 border border-slate-200 bg-white rounded-lg px-4 py-2.5 text-sm">
  <button type="submit" class="w-11 h-11 rounded-lg bg-brandgreen text-white text-xl font-bold flex-shrink-0">+</button>
</form>

<div class="space-y-3 max-w-xl">
  <?php foreach ($apprenants as $a): ?>
    <a href="/gerant/apprenants/show?id=<?= (int) $a['id'] ?>"
       class="block bg-white border border-slate-200 hover:border-navy rounded-xl px-5 py-4 text-sm font-medium transition"><?= e($a['nom']) ?></a>
  <?php endforeach; ?>
  <?php if (!$apprenants): ?>
    <p class="text-sm text-slate-400">Aucun apprenant trouvé.</p>
  <?php endif; ?>
</div>
