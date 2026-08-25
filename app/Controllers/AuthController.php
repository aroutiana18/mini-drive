<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\LdapService;

class AuthController extends Controller
{
    private LdapService $ldapService;

    public function __construct()
    {
        $this->ldapService = new LdapService();
    }

    protected function authView(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View not found: $viewFile");
        }

        require_once __DIR__ . '/../Views/layouts/auth.php';
    }

    public function login(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->authView('auth/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $this->authView('auth/login', [
                'error' => 'Veuillez renseigner votre adresse email et votre mot de passe.'
            ]);
            return;
        }

        $ldapUser = $this->ldapService->authenticate($email, $password);

        if ($ldapUser === false) {
            $this->authView('auth/login', [
                'error' => 'Adresse email ou mot de passe incorrect.'
            ]);
            return;
        }

        session_regenerate_id(true);

        $_SESSION['user_email'] = $ldapUser['email'];
        $_SESSION['email'] = $ldapUser['email'];
        $_SESSION['username'] = $ldapUser['cn'];

        $this->redirect('dashboard');
    }

    public function register(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
            return;
        }

        $this->authView('auth/register');
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        $this->redirect('login');
    }
}