<?php
/** @var array|null $currentUser */
/** @var string $content */
$role = $currentUser['role'] ?? null;

$tabsByRole = [
    'gerant' => [
        ['/gerant/dashboard', 'Dashboard'],
        ['/gerant/paiements/create', 'Nouveau paiement'],
        ['/gerant/apprenants', 'Apprenants'],
        ['/gerant/campagnes', 'Campagnes'],
        ['/gerant/tableau-croise', 'Tableau croisé'],
        ['/gerant/historique', 'Historique'],
    ],
    'apprenant' => [
        ['/apprenant/dashboard', 'Dashboard'],
        ['/apprenant/mes-semaines', 'Mes semaines'],
        ['/apprenant/campagnes', 'Campagnes'],
        ['/apprenant/historique', 'Historique'],
    ],
    'coach' => [
        ['/coach/dashboard', 'Dashboard'],
        ['/coach/audit', "Tableau d'audit"],
    ],
];
$tabs = $tabsByRole[$role] ?? [];
$currentPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion des Cotisations</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: { extend: {
      screens: { 'xs': '420px' },
      colors: {
        brand: { DEFAULT:'#40206A', 700:'#2F1750', 50:'#F4EEFA' },
        brandgreen: { DEFAULT:'#1E8449', 600:'#196F3D', 50:'#EAF7EE' },
        navy: { DEFAULT:'#241468' },
        danger: { DEFAULT:'#E5473C', soft:'#F1A19A' },
        badge: { bg:'#C8ECD2', text:'#1A7A3D', pendingbg:'#FFE1B3', pendingtext:'#96591A' }
      },
      fontFamily: { display:['"Poppins"','sans-serif'], body:['Inter','sans-serif'] }
    } }
  }
</script>
</head>
<body class="font-body text-slate-900 bg-slate-50 min-h-screen overflow-x-hidden">

<header class="sticky top-0 z-10 border-b border-slate-200 bg-white/95 backdrop-blur">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-2">
    <div class="flex items-center gap-2 min-w-0">
      <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-brand flex items-center justify-center font-display font-bold text-white shrink-0">C</div>
      <span class="font-display text-lg sm:text-xl font-bold tracking-tight text-slate-900 truncate">Cotise</span>
    </div>
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
      <?php if ($currentUser): ?>
        <span class="text-xs sm:text-sm text-slate-500 hidden md:inline"><?= e($currentUser['nom']) ?> &middot; <?= e(ucfirst($role)) ?></span>
        <a href="/logout" class="text-xs sm:text-sm text-danger hover:text-danger-soft transition whitespace-nowrap">Déconnexion</a>
      <?php endif; ?>
    </div>
  </div>
</header>

<?php if ($tabs): ?>
<nav class="max-w-5xl mx-auto px-4 sm:px-6 flex items-center gap-4 sm:gap-6 border-b border-slate-200 overflow-x-auto bg-white [-webkit-overflow-scrolling:touch]">
  <?php foreach ($tabs as [$href, $label]): ?>
    <?php $active = str_starts_with($currentPath, $href); ?>
    <a href="<?= e($href) ?>" class="py-3 text-xs sm:text-sm font-semibold whitespace-nowrap shrink-0 border-b-2 <?= $active ? 'border-brand text-brand' : 'border-transparent text-slate-500 hover:text-slate-700' ?>"><?= e($label) ?></a>
  <?php endforeach; ?>
</nav>
<?php endif; ?>

<main class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
<?php if (!empty($flash)): ?>
  <div class="mb-6 rounded-xl px-4 py-3 text-sm font-medium <?= $flash['type'] === 'error' ? 'bg-danger/10 text-danger' : 'bg-brandgreen-50 text-brandgreen-600' ?>">
    <?= e($flash['message']) ?>
  </div>
<?php endif; ?>

<?= $content ?>

</main>

</body>
</html>
