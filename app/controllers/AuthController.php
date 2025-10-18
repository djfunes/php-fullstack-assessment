<?php
namespace App\controllers;

use App\core\Database;
use App\core\Request;
use App\core\Response;
use App\core\View;
use MongoDB\BSON\UTCDateTime;

class AuthController {
    private \MongoDB\Collection $col;

    public function __construct() {
        $this->col = Database::collection('users');
    }

    public function showLogin(Request $req) {
        // Si ya hay sesión, redirige a /
        if (isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }
        return View::render('auth/login');
    }

    public function login(Request $req) {
        $data = $_POST ?: ($req->json ?? []);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($username === '' || $password === '') {
            return View::render('auth/login', ['error' => 'Usuario y contraseña requeridos.']);
        }

        $user = $this->col->findOne(['username' => $username]);
        if (!$user || !password_verify($password, (string)$user['passwordHash'])) {
            return View::render('auth/login', ['error' => 'Credenciales inválidas.']);
        }

        // Actualiza lastLogin
        $this->col->updateOne(['_id' => $user['_id']], [
            '$set' => ['lastLogin' => new UTCDateTime()]
        ]);

        $_SESSION['user'] = [
            'username' => $user['username'],
            'role' => $user['role']
        ];

        header("Location: /");
        exit;
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
        exit;
    }
}
