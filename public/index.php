<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/../vendor/autoload.php';

use App\core\Request;
use App\core\ErrorHandler;

# Cargar .env
$envFile = __DIR__ . '/../.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$k,$v] = array_map('trim', explode('=', $line, 2));
        putenv("$k=$v"); $_ENV[$k] = $v;
    }
}

# Registrar manejadores de errores global
ErrorHandler::register();

$router = require __DIR__ . '/../app/routes/web.php';

# Fallback errores no capturados
$router->dispatch(new Request());
