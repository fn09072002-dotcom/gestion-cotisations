<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — Gestion des Cotisations</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: { extend: {
      colors: {
        brand: { DEFAULT:'#40206A' },
        brandgreen: { DEFAULT:'#1E8449', 600:'#196F3D' },
        navy: { DEFAULT:'#241468' },
        danger: { DEFAULT:'#E5473C' },
      },
      fontFamily: { display:['"Poppins"','sans-serif'], body:['Inter','sans-serif'] }
    } }
  }
</script>
</head>
<body class="font-body bg-slate-50 min-h-screen flex items-center justify-center px-6 py-10">
  <div class="w-full max-w-sm">
    <div class="flex items-center gap-2 justify-center mb-8">
      <div class="w-9 h-9 rounded-lg bg-brand flex items-center justify-center font-display font-bold text-white">C</div>
      <span class="font-display text-xl font-bold tracking-tight text-slate-900">Cotise</span>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-7">
      <h1 class="font-display text-xl font-bold text-center mb-1">CONNEXION</h1>
      <p class="text-sm text-slate-500 text-center mb-6">bienvenue&nbsp;! connectez vous à votre compte</p>

      <?php if (!empty($error)): ?>
        <div class="mb-4 rounded-lg bg-danger/10 text-danger text-sm px-4 py-2.5"><?= e($error) ?></div>
      <?php endif; ?>

      <form action="/login" method="post" class="space-y-4">
        <input type="hidden" name="_csrf" value="<?= e($csrfToken) ?>">
        <div>
          <input type="email" name="email" placeholder="email" required
            class="w-full border border-slate-200 bg-slate-50 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-navy">
        </div>
        <div>
          <input type="password" name="mot_de_passe" placeholder="mot de passe" required
            class="w-full border border-slate-200 bg-slate-50 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-navy">
        </div>
        <button type="submit"
          class="w-full bg-brandgreen hover:bg-brandgreen-600 transition text-white font-semibold rounded-lg py-3 text-sm">
          se connecter
        </button>
      </form>
    </div>

    <div class="mt-6 pt-5 border-t border-dashed border-slate-300">
      <p class="text-xs text-slate-400 text-center mb-2">Comptes de démonstration</p>
      <ul class="text-xs text-slate-500 text-center space-y-1">
        <li>Gérant : gerant@cotise.sn / gerant123</li>
        <li>Apprenant : apprenant@cotise.sn / apprenant123</li>
        <li>Coach : coach@cotise.sn / coach123</li>
      </ul>
    </div>
  </div>
</body>
</html>
