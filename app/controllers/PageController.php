<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ContentModel;
use App\Models\UserModel;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Mailer;

class PageController extends Controller {
    public function about() {
        UserModel::getOrCreateSessionUser();
        $contentModel = $this->model('ContentModel');
        $page = $contentModel->getPage('about');

        $pageTitle = ($page && !empty($page['about_title'])) ? $page['about_title'] : (($page && !empty($page['page_title'])) ? $page['page_title'] : 'About Us');
        $data = [
            'title' => $pageTitle . ' - Karuda Computers',
            'page' => $page,
            'ABOUT_TITLE' => $pageTitle,
            'ABOUT_CONTENT' => ($page && !empty($page['about_content'])) ? $page['about_content'] : ''
        ];
        $this->view('pages/about', $data);
    }

    public function contact() {
        UserModel::getOrCreateSessionUser();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->contactSubmit();
            return;
        }
        $data = [
            'title' => 'Contact Us - Karuda Computers'
        ];
        $this->view('pages/contact', $data);
    }

    public function contactSubmit() {
        if (isset($_POST['csrf_token']) && !Csrf::validate()) {
            echo "<script>alert('Invalid security token.');window.location.href='" . BASE_URL . "contact.php';</script>";
            exit;
        }

        $db = Database::getInstance();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        $date = date('d-m-Y');
        $userId = $_SESSION['uid'] ?? 'Guest';

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
                  (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        if (empty($name) || empty($email) || empty($comment)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields (Name, Email, Message).']);
                exit;
            }
            $_SESSION['flash_toast'] = [
                'title' => 'Missing Fields',
                'message' => 'Please fill in all required fields.',
                'type' => 'error'
            ];
            header('Location: ' . BASE_URL . 'contact.php');
            exit;
        }

        // 1. Insert into comment table (Viewed by Admin in avadmin/contact.php)
        $db->execute("INSERT INTO comment (userid, name, email, mobile, comment, date) VALUES (?, ?, ?, ?, ?, ?)", 
                     [$userId, $name, $email, $mobile, $comment, $date]);

        // 2. Also insert into enquiry table
        $db->execute("INSERT INTO enquiry (name, mob, prd, dat) VALUES (?, ?, ?, ?)",
                     [$name, $mobile, $comment, $date]);

        // 3. Dispatch Email Notifications to Admin & Customer
        Mailer::sendContactNotification($name, $email, $mobile, $comment);

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Thank you for contacting Karuda Computers! Your inquiry has been submitted and our team will get back to you soon.'
            ]);
            exit;
        }

        $_SESSION['flash_toast'] = [
            'title' => 'Inquiry Submitted! 🎉',
            'message' => 'Thank you for contacting Karuda Computers! Your inquiry has been submitted and our team will get back to you soon.',
            'type' => 'success'
        ];
        header('Location: ' . BASE_URL . 'contact.php');
        exit;
    }

    public function faq() {
        UserModel::getOrCreateSessionUser();
        $contentModel = $this->model('ContentModel');
        $data = [
            'title' => 'Frequently Asked Questions - Karuda Computers',
            'faqs' => $contentModel->getFaqs(20)
        ];
        $this->view('pages/faq', $data);
    }

    public function feedback() {
        UserModel::getOrCreateSessionUser();
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }

        $data = [
            'title' => 'Feedback - Karuda Computers'
        ];
        $this->view('pages/feedback', $data);
    }

    public function complaint() {
        UserModel::getOrCreateSessionUser();
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }

        $data = [
            'title' => 'Grievance / Complaint - Karuda Computers'
        ];
        $this->view('pages/complaint', $data);
    }

    public function testi() {
        UserModel::getOrCreateSessionUser();
        $contentModel = $this->model('ContentModel');
        $data = [
            'title' => 'Testimonials - Karuda Computers',
            'testimonials' => $contentModel->getTestimonials()
        ];
        $this->view('pages/testi', $data);
    }

    public function addTesti() {
        if (isset($_POST['csrf_token']) && !Csrf::validate()) {
            echo "<script>alert('Invalid security token.');window.location.href='" . BASE_URL . "testi.php';</script>";
            exit;
        }

        $db = Database::getInstance();
        $name = trim($_POST['name'] ?? '');
        $designation = trim($_POST['designation'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $result = $db->execute("INSERT INTO testi (name, designation, message) VALUES (?, ?, ?)", 
                              [$name, $designation, $message]);
        
        if ($result) {
            echo "<script>alert('Testimonial inserted successfully!');window.location.href='" . BASE_URL . "testi.php';</script>";
        } else {
            echo "<script>alert('Failed to insert testimonial.');window.location.href='" . BASE_URL . "testi.php';</script>";
        }
        exit;
    }

    public function convertPrice() {
        if (isset($_POST['select_currency']) && $_POST['select_currency'] == 'true') {
            $_SESSION['selectedCurrency'] = $_POST['currency'];
            if (!empty($_SESSION['selectedCurrency'])) {
                echo "Sess_active";
                exit;
            }
        }
    }
}
