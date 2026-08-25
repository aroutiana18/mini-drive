<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
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

            $user = $this->userModel->authenticate($email, $password);

            if ($user !== null) {

                $_SESSION['user_id'] = $user['email'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];

                $this->redirect('dashboard');

            } else {

                $this->authView('auth/login', [
                    'error' => 'Adresse email ou mot de passe incorrect.'
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

        /*
         * Les utilisateurs ne créent plus eux-mêmes
         * leur compte LDAP.
         */
        $this->authView('auth/register');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('login');
    }
}