<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>misCV – Búsqueda</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS (CDN) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
  <div class="container d-flex justify-content-between">
    <span class="navbar-brand">misCV</span>
    <?php if (!empty($_SESSION['user'])): ?>
      <span class="text-white-50">
        <?= htmlspecialchars($_SESSION['user']['username']) ?> (<?= htmlspecialchars($_SESSION['user']['role']) ?>)
        | <a href="/logout" class="link-light text-decoration-none">Cerrar sesión</a>
      </span>
    <?php endif; ?>
  </div>
</nav>

<div class="container">
  <?= $content ?? '' ?>
</div>
</body>
</html>
