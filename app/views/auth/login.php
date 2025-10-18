<div class="d-flex justify-content-center align-items-center" style="min-height:80vh">
  <div class="card shadow-sm" style="width: 24rem;">
    <div class="card-body">
      <h5 class="card-title text-center mb-4">Iniciar sesión</h5>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="/login">
        <div class="mb-3">
          <label class="form-label">Usuario</label>
          <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>
    </div>
  </div>
</div>
