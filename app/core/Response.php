<?php
namespace App\core;

class Response {
    public static function json($data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function success($data = null, string $message = 'OK', int $status = 200): void {
        self::json(['status' => 'success', 'message' => $message, 'data' => $data], $status);
    }

    public static function error(string $message = 'Error', int $status = 400, $errors = null): void {
        self::json(['status' => 'error', 'message' => $message, 'errors' => $errors], $status);
    }
}