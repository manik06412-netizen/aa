<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Core\Csrf;

class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->loginSubmit();
            return;
        }
        $data = ['title' => 'Login - Karuda Computers'];
        $this->view('auth/login', $data);
    }

    public function loginSubmit() {
        // Validate CSRF if provided
        if (isset($_POST['csrf_token']) && !Csrf::validate()) {
            echo "<script>alert('Invalid security token. Please refresh and try again.');window.location.href='" . BASE_URL . "login.php';</script>";
            exit;
        }

        $email = $_POST['uemail'] ?? '';
        $password = $_POST['upwd'] ?? '';
        
        $userModel = $this->model('UserModel');
        $user = $userModel->login($email, $password);

        if ($user) {
            $_SESSION['uid'] = $user['user_id'];
            $_SESSION['uname'] = trim($user['fname'] . ' ' . $user['lname']);
            $_SESSION['uemail'] = $user['email'];
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_login'] = 1;
            $_SESSION['is_guest'] = false;

            $redirect = $_GET['redirect'] ?? ($_POST['redirect'] ?? ($_SESSION['redirect_after_login'] ?? 'index.php'));
            unset($_SESSION['redirect_after_login']);
            $this->redirect(BASE_URL . $redirect);
            return;
        } else {
            echo "<script>alert('Invalid Email or Password');window.location.href='" . BASE_URL . "login.php';</script>";
            exit;
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->registerSubmit();
            return;
        }
        $data = ['title' => 'Register - Karuda Computers'];
        $this->view('auth/register', $data);
    }

    public function registerSubmit() {
        // Validate CSRF if provided
        if (isset($_POST['csrf_token']) && !Csrf::validate()) {
            echo "<script>alert('Invalid security token. Please refresh and try again.');window.location.href='" . BASE_URL . "register.php';</script>";
            exit;
        }

        $userModel = $this->model('UserModel');
        $result = $userModel->register($_POST);
        
        if ($result['success']) {
            $_SESSION['uid'] = $result['uid'];
            $_SESSION['uname'] = trim(($result['fname'] ?? '') . ' ' . ($result['lname'] ?? ''));
            $_SESSION['uemail'] = $result['email'] ?? '';
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_login'] = 1;
            $_SESSION['is_guest'] = false;

            $redirect = $_GET['redirect'] ?? ($_POST['redirect'] ?? ($_SESSION['redirect_after_login'] ?? 'index.php'));
            unset($_SESSION['redirect_after_login']);
            echo "<script>alert('Registration Successful!');window.location.href='" . BASE_URL . "{$redirect}';</script>";
            exit;
        } else {
            $msg = addslashes($result['message'] ?? 'Registration failed.');
            echo "<script>alert('{$msg}');window.location.href='" . BASE_URL . "register.php';</script>";
            exit;
        }
    }

    public function emailRegister() {
        $userModel = $this->model('UserModel');
        $fname = trim($_POST['first_name'] ?? '');
        $lname = trim($_POST['last_name'] ?? '');
        $fullname = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $idNum = trim($_POST['id_num'] ?? '');

        $check = $userModel->fetchOnePrepared("SELECT * FROM user WHERE email = ? LIMIT 1", [$email]);
        if ($check) {
            $_SESSION['uid'] = $check['user_id'];
            $_SESSION['uname'] = trim($check['fname'] . ' ' . $check['lname']);
            $_SESSION['uemail'] = $check['email'];
        } else {
            $userId = 'USR' . rand(1000, 9999) . time();
            $date = date('d/m/Y');
            $bcrypt = password_hash($idNum ?: bin2hex(random_bytes(8)), PASSWORD_BCRYPT);
            $userModel->execute(
                "INSERT INTO user (user_id, fname, lname, email, pwd, mobile, date, status) VALUES (?, ?, ?, ?, ?, '', ?, '1')",
                [$userId, $fname, $lname, $email, $bcrypt, $date]
            );
            $_SESSION['uid'] = $userId;
            $_SESSION['uname'] = $fullname ?: trim($fname . ' ' . $lname);
            $_SESSION['uemail'] = $email;
        }
        $this->redirect(BASE_URL . 'index.php');
    }

    public function forgot() {
        $data = ['title' => 'Forgot Password - Karuda Computers'];
        $this->view('auth/login', $data);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['uid']);
        unset($_SESSION['uname']);
        unset($_SESSION['uemail']);
        session_destroy();
        $this->redirect(BASE_URL . 'index.php');
    }
}
