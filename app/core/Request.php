<?php
namespace App\core;

class Request {
    public string $method;
    public string $path;
    public array $query;
    public array $headers;
    public ?array $json;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $this->query  = $_GET ?? [];
        $this->headers = function_exists('getallheaders') ? (getallheaders() ?: []) : [];
        $raw = file_get_contents('php://input') ?: '';
        $this->json = is_array($j = json_decode($raw, true)) ? $j : null;
    }
}