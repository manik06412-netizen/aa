<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ContentModel;
use App\Models\UserModel;

class HomeController extends Controller {
    public function index() {
        // Ensure user session
        UserModel::getOrCreateSessionUser();

        $productModel = $this->model('ProductModel');
        $categoryModel = $this->model('CategoryModel');
        $contentModel = $this->model('ContentModel');

        $data = [
            'title' => 'Karuda Computers - Computers & Tech Accessories',
            'sliders' => $contentModel->getSliders(),
            'categories' => $categoryModel->getAllCategories(),
            'products' => $productModel->getActiveProducts(12),
            'faqs' => $contentModel->getFaqs(6),
            'ad_banners' => $contentModel->getAdBanners(5)
        ];

        $this->view('home/index', $data);
    }
}
