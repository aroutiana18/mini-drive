<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // Vue spécifique pour l'authentification
    protected function authView($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("View not found: $viewFile");
        }
        // Utilise le layout auth.php 
        require_once __DIR__ . '/../Views/layouts/auth.php';
    }

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = $this->userModel->findByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $this->redirect('dashboard');
            } else {
                $this->authView('auth/login', ['error' => 'Identifiants invalides']);
            }
        } else {
            $this->authView('auth/login');
        }
    }

    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            if ($password !== $confirm) {
                $this->authView('auth/register', ['error' => 'Les mots de passe ne correspondent pas']);
                return;
            }
            if ($this->userModel->findByUsername($username)) {
                $this->authView('auth/register', ['error' => "Nom d'utilisateur déjà pris"]);
                return;
            }
            if ($this->userModel->findByEmail($email)) {
                $this->authView('auth/register', ['error' => "Email déjà utilisé"]);
                return;
            }
            if ($this->userModel->create($username, $email, $password)) {
                $this->redirect('login');
            } else {
                $this->authView('auth/register', ['error' => "Erreur lors de l'inscription"]);
            }
        } else {
            $this->authView('auth/register');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
}