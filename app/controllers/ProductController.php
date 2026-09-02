<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\UserModel;

class ProductController extends Controller {
    public function index() {
        UserModel::getOrCreateSessionUser();
        $productModel = $this->model('ProductModel');
        
        $cat = isset($_GET['cat']) ? $_GET['cat'] : null;
        $subcate = isset($_GET['subcate']) ? $_GET['subcate'] : null;
        
        $data = [
            'title' => 'All Products - Karuda Computers',
            'products' => $productModel->getActiveProducts(null, $cat, $subcate),
            'selectedCat' => $cat
        ];
        $this->view('products/allproducts', $data);
    }

    public function details($id = null) {
        UserModel::getOrCreateSessionUser();
        if ($id === null && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if ($id !== null) {
            $_GET['id'] = $id;
        }

        $productModel = $this->model('ProductModel');
        $product = $productModel->getProductById($id);

        if (!$product) {
            $this->redirect(BASE_URL . 'allproducts.php');
            return;
        }

        $data = [
            'title' => $product['dish_name'] . ' - Karuda Computers',
            'product' => $product
        ];

        $this->view('products/details', $data);
    }
}
