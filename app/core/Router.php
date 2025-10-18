<?php
namespace App\core;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, callable $handler): void {
        $this->routes[] = [$method, $path, $handler];
    }

    public function dispatch(Request $req) {
        foreach ($this->routes as [$method, $path, $handler]) {
            $pattern = "@^" . preg_replace('@\{(\w+)\}@', '(?P<$1>[^/]+)', $path) . "$@";
            if ($req->method === $method && preg_match($pattern, $req->path, $m)) {
                return $handler($req, $m);
            }
        }
        // Fallback
        throw new HttpException(404, 'Recurso no encontrado');
    }
}
