<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AdminModel;

class AdminController extends Controller {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION["adm_id"])) {
            $this->redirect(BASE_URL . 'admin/dashboard.php');
            return;
        }

        $data = ['title' => 'Admin Login - Karuda Computers'];
        $this->view('admin/login', $data);
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION["adm_id"])) {
            $this->redirect(BASE_URL . 'admin/index.php');
            return;
        }

        $adminModel = $this->model('AdminModel');
        $data = [
            'title' => 'Admin Dashboard - Karuda Computers',
            'stats' => $adminModel->getMainAdminStats()
        ];
        $this->view('admin/dashboard', $data);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION["adm_id"]);
        $this->redirect(BASE_URL . 'admin/index.php');
    }
}
