<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AdminModel;

class AvadminController extends Controller {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            $this->redirect(BASE_URL . 'avadmin/login.php');
            return;
        }

        $adminModel = $this->model('AdminModel');
        $data = [
            'title' => 'UK Admin Dashboard - Karuda Computers',
            'stats' => $adminModel->getUkAdminStats()
        ];
        $this->view('avadmin/dashboard', $data);
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user'])) {
            $this->redirect(BASE_URL . 'avadmin/index.php');
            return;
        }

        $data = ['title' => 'UK Admin Login - Karuda Computers'];
        $this->view('avadmin/login', $data);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['user']);
        $this->redirect(BASE_URL . 'avadmin/login.php');
    }
}
