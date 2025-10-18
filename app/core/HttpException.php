<?php
declare(strict_types=1);

namespace App\core;

use Exception;

class HttpException extends Exception
{
    private int $status;

    public function __construct(int $status, string $message = '')
    {
        parent::__construct($message ?: self::defaultMessage($status));
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    private static function defaultMessage(int $status): string
    {
        return match ($status) {
            400 => 'Solicitud inválida',
            401 => 'No autorizado',
            403 => 'Prohibido',
            404 => 'Recurso no encontrado',
            405 => 'Método no permitido',
            422 => 'Entidad no procesable',
            500 => 'Error interno del servidor',
            default => 'Error'
        };
    }
}
