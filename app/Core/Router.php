<?php
namespace App\Core;

class Router {
    private $routes = [
        '' => ['HomeController', 'index'],
        'login' => ['AuthController', 'login'],
        'register' => ['AuthController', 'register'],
        'logout' => ['AuthController', 'logout'],
        'dashboard' => ['FileController', 'index'],
        'create-folder' => ['FileController', 'createFolder'],
        'upload' => ['FileController', 'upload'],
        'upload-folder' => ['FileController', 'uploadFolder'],
        'download' => ['FileController', 'download'],
        'download-multiple' => ['FileController', 'downloadMultiple'],
        'delete' => ['FileController', 'delete'],
        'restore' => ['FileController', 'restore'],
        'permanent-delete' => ['FileController', 'permanentDelete'],
        'trash' => ['FileController', 'trash'],
        'search' => ['FileController', 'search'],
        'share' => ['FileController', 'share'],
        'share-multiple' => ['FileController', 'shareMultiple'],
        'public-share' => ['FileController', 'publicShare'],
    ];

    public function dispatch($url) {
        $url = rtrim($url, '/');
        if ($url === '') {
            $route = $this->routes[''];
        } elseif (isset($this->routes[$url])) {
            $route = $this->routes[$url];
        } else {
            http_response_code(404);
            echo "Page non trouvée (route: $url)";
            return;
        }

        list($controllerName, $method) = $route;
        $controllerClass = "App\\Controllers\\$controllerName";

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "Erreur: La classe $controllerClass n'existe pas";
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "Erreur: La méthode $method n'existe pas dans $controllerClass";
            return;
        }

        $controller->$method();
    }
}