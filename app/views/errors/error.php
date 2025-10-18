<?php
$status  = $status  ?? http_response_code();
$message = $message ?? 'Error';
function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<div class="d-flex justify-content-center align-items-center" style="min-height:70vh">
  <div class="card shadow-sm" style="max-width: 560px; width:100%">
    <div class="card-body text-center">
      <h1 class="display-5 mb-2"><?= e((string)$status) ?></h1>
      <p class="lead mb-4"><?= e($message) ?></p>
      <?php if (!empty($debug)): ?>
        <pre class="text-start bg-light p-3 rounded small"><?= e((string)$debug) ?></pre>
      <?php endif; ?>
      <a href="/" class="btn btn-primary">Ir al inicio</a>
    </div>
  </div>
</div>
