<?php
declare(strict_types=1);

namespace App\core;

use App\core\Database;
use MongoDB\BSON\UTCDateTime;

class Auth
{
    /**
     * Verificar usuario autenticado.
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Devuelve los datos del usuario autenticado.
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Middleware: Requiere estar autenticado.
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header("Location: /login");
            exit;
        }
    }

    /**
     * Intenta autenticar al usuario con username/password.
     * Retorna true si es exitoso, false si no.
     */
    public static function attempt(string $username, string $password): bool
    {
        $users = Database::collection('users');
        $user = $users->findOne(['username' => $username]);

        if (!$user) {
            return false;
        }

        $valid = password_verify($password, (string)$user['passwordHash']);
        if (!$valid) {
            return false;
        }

        // Controla lastLogin
        $users->updateOne(
            ['_id' => $user['_id']],
            ['$set' => ['lastLogin' => new UTCDateTime()]]
        );

        // Generamos session
        $_SESSION['user'] = [
            'username' => $user['username'],
            'role'     => $user['role'] ?? 'user',
        ];

        return true;
    }

    /**
     * Destruimos la sesión y redirigimos a login.
     */
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();
        }

        header("Location: /login");
        exit;
    }

    /**
     * Devuelve true si el usuario tiene el rol especificado.
     */
    public static function hasRole(string $role): bool
    {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === $role;
    }
}
