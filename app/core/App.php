<?php
/**
 * Karuda Computers - Front Controller & Application Router
 * Architecture: Model-View-Controller (MVC)
 */
namespace App\Core;

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    // Map of legacy & custom routes to MVC Controllers & Actions
    protected $routeMap = [
        // Home
        'home' => ['HomeController', 'index'],
        'index' => ['HomeController', 'index'],
        'index.php' => ['HomeController', 'index'],
        
        // Products & Catalog
        'allproducts.php' => ['ProductController', 'index'],
        'allproducts' => ['ProductController', 'index'],
        'products' => ['ProductController', 'index'],
        'product' => ['ProductController', 'index'],
        'details.php' => ['ProductController', 'details'],
        'details' => ['ProductController', 'details'],
        
        // Categories
        'allcategories.php' => ['CategoryController', 'index'],
        'allcategories' => ['CategoryController', 'index'],
        'categories' => ['CategoryController', 'index'],
        'category_list.php' => ['CategoryController', 'index'],
        'category_list' => ['CategoryController', 'index'],
        'ajax_allcat.php' => ['CategoryController', 'filterAjax'],
        'ajax_allcat' => ['CategoryController', 'filterAjax'],
        'ajax_all_categories.php' => ['CategoryController', 'filterAjax'],
        'ajax_all_categories' => ['CategoryController', 'filterAjax'],
        
        // Cart
        'cart.php' => ['CartController', 'index'],
        'cart' => ['CartController', 'index'],
        'shopping_cart.php' => ['CartController', 'index'],
        'shopping_cart' => ['CartController', 'index'],
        'shopping-cart' => ['CartController', 'index'],
        'add1.php' => ['CartController', 'add'],
        'add1' => ['CartController', 'add'],
        'details_related_add.php' => ['CartController', 'add'],
        'details_related_add' => ['CartController', 'add'],
        'cart/add' => ['CartController', 'add'],
        'remove_cart.php' => ['CartController', 'remove'],
        'remove_cart' => ['CartController', 'remove'],
        'shopping_remove.php' => ['CartController', 'remove'],
        'shopping_remove' => ['CartController', 'remove'],
        'card_remove.php' => ['CartController', 'remove'],
        'card_remove' => ['CartController', 'remove'],
        'cart/remove' => ['CartController', 'remove'],
        
        // Deals & Services
        'deals.php' => ['CategoryController', 'index'],
        'deals' => ['CategoryController', 'index'],
        'services.php' => ['PageController', 'about'],
        'services' => ['PageController', 'about'],
        'order_track.php' => ['UserController', 'trackOrder'],
        'userprofile.php' => ['UserController', 'profile'],
        'userprofile' => ['UserController', 'profile'],

        // Wishlist
        'wishlist.php' => ['WishlistController', 'index'],
        'wishlist' => ['WishlistController', 'index'],
        'fav.php' => ['WishlistController', 'toggle'],
        'fav' => ['WishlistController', 'toggle'],
        'watc.php' => ['WishlistController', 'toggle'],
        'watc' => ['WishlistController', 'toggle'],
        'wishlist/toggle' => ['WishlistController', 'toggle'],
        'remove_wishlist.php' => ['WishlistController', 'remove'],
        'wishlist/remove' => ['WishlistController', 'remove'],

        // Checkout & Orders
        'checkout.php' => ['CheckoutController', 'index'],
        'checkout' => ['CheckoutController', 'index'],
        'final_checkout.php' => ['CheckoutController', 'processFinalCheckout'],
        'final_checkout' => ['CheckoutController', 'processFinalCheckout'],
        'save_buyknow.php' => ['CheckoutController', 'saveBuyNow'],
        'save_buyknow' => ['CheckoutController', 'saveBuyNow'],
        'shopping_insert.php' => ['CheckoutController', 'saveCartCheckout'],
        'shopping_insert' => ['CheckoutController', 'saveCartCheckout'],
        'shopping-insert' => ['CheckoutController', 'saveCartCheckout'],
        'myorders.php' => ['UserController', 'myOrders'],
        'myorders' => ['UserController', 'myOrders'],
        'my-orders' => ['UserController', 'myOrders'],
        'orders' => ['UserController', 'myOrders'],
        'track_order.php' => ['UserController', 'trackOrder'],
        'track_order' => ['UserController', 'trackOrder'],
        'track-order' => ['UserController', 'trackOrder'],
        'submit_feedback.php' => ['UserController', 'submitFeedback'],
        'submit_feedback' => ['UserController', 'submitFeedback'],
        'submit_review.php' => ['UserController', 'submitReview'],
        'submit_review' => ['UserController', 'submitReview'],
        'cancel_order.php' => ['UserController', 'cancelOrder'],
        'cancel_order' => ['UserController', 'cancelOrder'],
        'cancel_order1.php' => ['UserController', 'cancelOrder'],
        'upd_status.php' => ['UserController', 'getStatusDetails'],
        'upd_status' => ['UserController', 'getStatusDetails'],
        
        // User & Auth
        'profile.php' => ['UserController', 'profile'],
        'profile' => ['UserController', 'profile'],
        'myprobile.php' => ['UserController', 'profile'],
        'myaccount' => ['UserController', 'profile'],
        'remove.php' => ['UserController', 'removeAccount'],
        'remove_account.php' => ['UserController', 'removeAccount'],
        'remove1.php' => ['UserController', 'removeAccount'],
        'login.php' => ['AuthController', 'login'],
        'login' => ['AuthController', 'login'],
        'login1.php' => ['AuthController', 'loginSubmit'],
        'register.php' => ['AuthController', 'register'],
        'register' => ['AuthController', 'register'],
        'register1.php' => ['AuthController', 'registerSubmit'],
        'email_register.php' => ['AuthController', 'emailRegister'],
        'forgot.php' => ['AuthController', 'forgot'],
        'forgot' => ['AuthController', 'forgot'],
        'logout.php' => ['AuthController', 'logout'],
        'logout' => ['AuthController', 'logout'],
        
        // Static & CMS Pages
        'about.php' => ['PageController', 'about'],
        'about' => ['PageController', 'about'],
        'about-us' => ['PageController', 'about'],
        'contact.php' => ['PageController', 'contact'],
        'contact' => ['PageController', 'contact'],
        'contact-us' => ['PageController', 'contact'],
        'contact1.php' => ['PageController', 'contactSubmit'],
        'faq.php' => ['PageController', 'faq'],
        'faq' => ['PageController', 'faq'],
        'faqs' => ['PageController', 'faq'],
        'feedback.php' => ['PageController', 'feedback'],
        'feedback' => ['PageController', 'feedback'],
        'complaint.php' => ['PageController', 'complaint'],
        'complaint' => ['PageController', 'complaint'],
        'testi.php' => ['PageController', 'testi'],
        'testi' => ['PageController', 'testi'],
        'testimonials' => ['PageController', 'testi'],
        'testimonial' => ['PageController', 'testi'],
        'add_testi.php' => ['PageController', 'addTesti'],
        'convert-price.php' => ['PageController', 'convertPrice'],
        'convert-price' => ['PageController', 'convertPrice'],

        // Search
        'searchresultpage.php' => ['SearchController', 'index'],
        'searchresultpage' => ['SearchController', 'index'],
        'search_results.php' => ['SearchController', 'index'],
        'search_results' => ['SearchController', 'index'],
        'search' => ['SearchController', 'index']
    ];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Check Route Map
        if (!empty($url[0])) {
            $rawRoute = strtolower($url[0]);
            if (isset($this->routeMap[$rawRoute])) {
                $this->controller = $this->routeMap[$rawRoute][0];
                $this->method = $this->routeMap[$rawRoute][1];
                unset($url[0]);
            } else {
                // Check controller directory
                $controllerName = ucfirst($url[0]) . 'Controller';
                if (file_exists(APP_ROOT . '/controllers/' . $controllerName . '.php')) {
                    $this->controller = $controllerName;
                    unset($url[0]);
                }
            }
        }

        // Require Controller File
        $controllerFile = APP_ROOT . '/controllers/' . $this->controller . '.php';
        if (!file_exists($controllerFile)) {
            $this->controller = 'HomeController';
            $controllerFile = APP_ROOT . '/controllers/HomeController.php';
        }
        require_once $controllerFile;

        $fullControllerClass = "\\App\\Controllers\\" . $this->controller;
        if (class_exists($fullControllerClass)) {
            $this->controller = new $fullControllerClass();
        } else {
            require_once APP_ROOT . '/controllers/HomeController.php';
            $this->controller = new \App\Controllers\HomeController();
        }

        // 2. Determine Method if not set by routeMap
        if (!empty($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        if (!method_exists($this->controller, $this->method)) {
            $this->method = 'index';
        }

        // 3. Determine Parameters
        $this->params = $url ? array_values($url) : [];

        // 4. Dispatch Request
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
