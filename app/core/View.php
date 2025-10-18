<?php
namespace App\core;

class View {
    public static function render(string $view, array $data = []): void {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        $layout = __DIR__ . '/../views/layout.php';
        if (!is_file($viewFile)) {
            Response::error('View not found: '.$view, 500);
            return;
        }
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        include $layout;
    }
}
