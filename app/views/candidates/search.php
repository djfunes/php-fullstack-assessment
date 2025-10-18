<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h5 class="card-title mb-3">Búsqueda de candidatos</h5>

    <form method="post" action="/buscar" class="row g-3" id="searchForm" autocomplete="off">
        <div class="col-md-3">
            <label class="form-label">Nivel académico</label>
            <input name="nivel" class="form-control" placeholder="Universitario, Técnico..." value="<?= htmlspecialchars($filters['nivel'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Especialidad / carrera</label>
            <input name="especialidad" class="form-control" placeholder="Administración de Empresas" value="<?= htmlspecialchars($filters['especialidad'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Departamento</label>
            <input name="departamento" class="form-control" placeholder="San Salvador" value="<?= htmlspecialchars($filters['departamento'] ?? '') ?>">
        </div>
        <div class="col-md-1">
            <label class="form-label">Licencia</label>
            <select name="licencia" class="form-select">
            <option value="">--</option>
            <option value="SI" <?= (($filters['licencia'] ?? '')==='SI')?'selected':'' ?>>SI</option>
            <option value="NO" <?= (($filters['licencia'] ?? '')==='NO')?'selected':'' ?>>NO</option>
            </select>
        </div>
        <div class="col-md-1">
            <label class="form-label">Vehículo</label>
            <select name="vehiculo" class="form-select">
            <option value="">--</option>
            <option value="SI" <?= (($filters['vehiculo'] ?? '')==='SI')?'selected':'' ?>>SI</option>
            <option value="NO" <?= (($filters['vehiculo'] ?? '')==='NO')?'selected':'' ?>>NO</option>
            </select>
        </div>
        <div class="col-md-1">
            <label class="form-label">Salario máx.</label>
            <input name="salarioMax" class="form-control" type="number" min="0" step="1" value="<?= htmlspecialchars($filters['salarioMax'] ?? '') ?>">
        </div>

        <div class="col-md-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <button type="button" class="btn btn-outline-secondary" id="clearBtn">Limpiar B&uacute;squeda</button>
        </div>
    </form>

    <script>
    document.getElementById('clearBtn')?.addEventListener('click', () => {
    const form = document.getElementById('searchForm');

    // 1) Limpiar todos los inputs/selects
    form.reset();

    // Forzar selects al primer elemento por si el navegador “recuerda” estado
    form.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

    // 2) Enviar clear=1 para que el backend devuelva 0 resultados
    let hidden = form.querySelector('input[name="clear"]');
    if (!hidden) {
        hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'clear';
        form.appendChild(hidden);
    }
    hidden.value = '1';

    // 3) Enviar el formulario vacío
    form.submit();
    });
    </script>
  </div>
</div>

<?php if (isset($results)): ?>
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="mb-0">Resultados</h6>
    <span class="badge bg-secondary"><?= (int)($count ?? 0) ?> encontrados</span>
  </div>
  <div class="table-responsive">
    <table class="table table-sm table-striped align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Teléfono</th>
          <th>Departamento</th>
          <th>Licencia</th>
          <th>Vehículo</th>
          <th>Salario (último)</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($results as $i => $row): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= htmlspecialchars($row['nombre_completo'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['correo'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['telefono'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['departamento'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['licencia'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['vehiculo'] ?? '') ?></td>
          <td>
            <?php
              $ultimo = $row['exlaboral'][count($row['exlaboral']??[])-1]['salario'] ?? '';
              echo htmlspecialchars((string)$ultimo);
            ?>
          </td>
          <td class="text-end">
            <?php $id = (string)($row['_id'] ?? ''); ?>
            <a href="/cv/<?= htmlspecialchars($id) ?>" class="btn btn-sm btn-primary">
                Ver detalles
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
