<?php
/**
 * Karuda Computers - Base Controller Class
 */
namespace App\Core;

abstract class Controller {
    /**
     * Load Model instance
     */
    public function model($model) {
        $modelClass = "\\App\\Models\\" . $model;
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        $file = APP_ROOT . "/models/" . $model . ".php";
        if (file_exists($file)) {
            require_once $file;
            return new $modelClass();
        }
        throw new \Exception("Model {$model} not found.");
    }

    /**
     * Render View with Data
     */
    public function view($view, $data = []) {
        // Global site and configuration variables
        global $con, $CON_LOGO, $CON_FEVICON, $CON_COPYRIGHTS, $CON_CONTACT_ADDRESS, 
               $CON_CONTACT_EMAIL, $CON_CONTACT_PHONE, $CON_ALTERNATE_NUMBER, $CON_WHATSAPP, 
               $CON_FACEBOOK, $CON_INSTA, $ABOUT_TITLE, $ABOUT_CONTENT, $FAQ_TITLE, 
               $CONTACT_TITLE, $statement, $st, $rates, $countries, $countryOptions;

        // Extract variables for view access
        extract($data);

        // Database and session context for view compatibility
        if (!$con) {
            $db = Database::getInstance();
            $con = $db->getConnection();
        }
        
        $viewFile = APP_ROOT . "/views/" . $view . ".php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new \Exception("View {$view} not found at {$viewFile}");
        }
    }

    /**
     * JSON Response Helper
     */
    public function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * Redirect Helper
     */
    public function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}
