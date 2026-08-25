<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Services\LdapService; 

class AuthController extends Controller
{
    private $userModel;
    private $ldapService; 

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->ldapService = new LdapService(); 
    }

    protected function authView($view, $data = [])
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View not found: $viewFile");
        }

        require_once __DIR__ . '/../Views/layouts/auth.php';
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Authentification via le serveur LDAP OpenLDAP
            $ldapUser = $this->ldapService->authenticate($email, $password);

            if ($ldapUser !== false) {

                $_SESSION['user_id'] = $ldapUser['email'];
                $_SESSION['email'] = $ldapUser['email'];
                $_SESSION['username'] = $ldapUser['cn']; // Récupéré depuis le LDAP (cn)

                $this->redirect('dashboard');

            } else {

                $this->authView('auth/login', [
                    'error' => 'Adresse email ou mot de passe incorrect (LDAP).'
                ]);
            }

        } else {

            $this->authView('auth/login');
        }
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $this->authView('auth/register');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('login');
    }
}