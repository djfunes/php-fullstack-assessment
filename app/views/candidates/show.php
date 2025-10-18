<?php
function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$cv = $cv ?? [];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Detalle del candidato</h5>
  <a href="/" class="btn btn-outline-secondary btn-sm">← Volver</a>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="mb-2"><strong>Nombre:</strong> <?= e($cv['nombre_completo'] ?? '') ?></div>
        <div class="mb-2"><strong>Correo:</strong> <?= e($cv['correo'] ?? '') ?></div>
        <div class="mb-2"><strong>Teléfono:</strong> <?= e($cv['telefono'] ?? '') ?></div>
        <div class="mb-2"><strong>Departamento:</strong> <?= e($cv['departamento'] ?? '') ?></div>
      </div>
      <div class="col-md-6">
        <div class="mb-2"><strong>Licencia:</strong> <?= e($cv['licencia'] ?? '') ?></div>
        <div class="mb-2"><strong>Vehículo:</strong> <?= e($cv['vehiculo'] ?? '') ?></div>
        <div class="mb-2"><strong>Creado:</strong> 
          <?php 
            if (isset($cv['created_at']) && $cv['created_at'] instanceof \MongoDB\BSON\UTCDateTime) {
              echo e($cv['created_at']->toDateTime()->format('Y-m-d H:i'));
            }
          ?>
        </div>
        <div class="mb-2"><strong>Actualizado:</strong> 
          <?php 
            if (isset($cv['updated_at']) && $cv['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
              echo e($cv['updated_at']->toDateTime()->format('Y-m-d H:i'));
            }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h6 class="card-title">Formación académica</h6>
        <?php if (empty($cv['facademica'])): ?>
          <p class="text-muted mb-0">Sin registros</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Nivel</th>
                  <th>Especialidad</th>
                  <th>Institución</th>
                  <th>Año</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($cv['facademica'] as $fa): ?>
                <tr>
                  <td><?= e($fa['nivel'] ?? '') ?></td>
                  <td><?= e($fa['especialidad'] ?? '') ?></td>
                  <td><?= e($fa['institucion'] ?? '') ?></td>
                  <td><?= e($fa['anio'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h6 class="card-title">Experiencia laboral</h6>
        <?php if (empty($cv['exlaboral'])): ?>
          <p class="text-muted mb-0">Sin registros</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Puesto</th>
                  <th>Empresa</th>
                  <th>Salario</th>
                  <th>Desde</th>
                  <th>Hasta</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($cv['exlaboral'] as $ex): ?>
                <tr>
                  <td><?= e($ex['puesto'] ?? '') ?></td>
                  <td><?= e($ex['empresa'] ?? '') ?></td>
                  <td><?= e($ex['salario'] ?? '') ?></td>
                  <td><?= e($ex['desde'] ?? '') ?></td>
                  <td><?= e($ex['hasta'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
