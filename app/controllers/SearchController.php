<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\UserModel;

class SearchController extends Controller {
    public function index() {
        UserModel::getOrCreateSessionUser();
        $query = isset($_GET['s']) ? $_GET['s'] : (isset($_GET['query']) ? $_GET['query'] : '');
        $productModel = $this->model('ProductModel');
        $products = !empty($query) ? $productModel->searchProducts($query) : [];

        $data = [
            'title' => 'Search: ' . htmlspecialchars($query) . ' - Karuda Computers',
            'query' => $query,
            'products' => $products
        ];

        $this->view('products/search_results', $data);
    }
}
