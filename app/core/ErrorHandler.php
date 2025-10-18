<?php
declare(strict_types=1);

namespace App\core;

class ErrorHandler
{
    public static function register(): void
    {
        // Convierte errores PHP en excepciones
        set_error_handler(function (int $severity, string $message, string $file, int $line) {
            if (!(error_reporting() & $severity)) {
                return false; // silent
            }
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        // Maneja excepciones no capturadas
        set_exception_handler(function (\Throwable $e) {
            self::renderException($e);
        });
    }

    private static function isApiRequest(): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return str_starts_with(parse_url($uri, PHP_URL_PATH) ?: '/', '/api')
            || str_contains($accept, 'application/json');
    }

    public static function renderException(\Throwable $e): void
    {
        $status = 500;
        $message = 'Error interno del servidor';

        if ($e instanceof HttpException) {
            $status = $e->getStatus();
            $message = $e->getMessage();
        }

        http_response_code($status);

        if (self::isApiRequest()) {
            // Respuesta JSON para API
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => 'error',
                'message' => $message,
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        // Respuesta HTML para web
        try {
            View::render('errors/error', [
                'status'  => $status,
                'message' => $message,
            ]);
        } catch (\Throwable $fallback) {
            // Fallback si no se encuentra la vista dedicada al error
            header('Content-Type: text/html; charset=utf-8');
            echo "<h1>{$status}</h1><p>{$message}</p>";
        }
    }
}
