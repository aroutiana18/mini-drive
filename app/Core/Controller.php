<?php
namespace App\Core;

abstract class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("View not found: $viewFile");
        }
        require_once __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . "/$url");
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }
    }
}
