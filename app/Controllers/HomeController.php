<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        } else {
            $this->redirect('login');
        }
    }
}
