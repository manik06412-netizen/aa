<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\UserModel;
use App\Core\Database;

class CategoryController extends Controller {
    public function index($cat = null) {
        UserModel::getOrCreateSessionUser();
        if ($cat === null && isset($_GET['cat'])) {
            $cat = $_GET['cat'];
        }
        $subcate = isset($_GET['subcate']) ? $_GET['subcate'] : null;

        $categoryModel = $this->model('CategoryModel');
        $productModel = $this->model('ProductModel');

        $categories = $categoryModel->getAllCategories();
        $products = $productModel->getActiveProducts(null, $cat, $subcate);

        $selectedCategory = $cat ? $categoryModel->getCategory($cat) : null;

        $data = [
            'title' => ($selectedCategory ? $selectedCategory['c_name'] : 'All Categories') . ' - Karuda Computers',
            'categories' => $categories,
            'products' => $products,
            'selectedCat' => $cat,
            'selectedSub' => $subcate,
            'categoryInfo' => $selectedCategory
        ];

        $this->view('categories/allcategories', $data);
    }

    /**
     * AJAX Filter Endpoint for Category & Product Catalog Filtering
     */
    public function filterAjax() {
        $db = Database::getInstance();
        $con = $db->getConnection();

        // 1. Decode Filter Inputs
        $rawCat = isset($_GET['fetch_category']) ? $_GET['fetch_category'] : '';
        $catData = json_decode($rawCat, true);
        $selectedCats = isset($catData['category']) ? (array)$catData['category'] : [];

        $rawRatings = isset($_GET['ratings_list']) ? $_GET['ratings_list'] : '';
        $ratingData = json_decode($rawRatings, true);
        $selectedRatings = isset($ratingData['starRatings']) ? (array)$ratingData['starRatings'] : [];

        $rawPrice = isset($_GET['price_range']) ? $_GET['price_range'] : '';
        $priceData = json_decode($rawPrice, true);
        
        $minPrice = isset($priceData['starting_price']) ? (float)$priceData['starting_price'] : 0;
        $maxPrice = isset($priceData['ending_price']) ? (float)$priceData['ending_price'] : 0;
        if (isset($priceData['price']) && is_array($priceData['price'])) {
            $selectedPrices = $priceData['price'];
        } else {
            $selectedPrices = [];
        }

        $sortBy = isset($_GET['short_by']) ? (int)$_GET['short_by'] : 0;

        // 2. Build Query
        $whereConditions = ["d.status = '1'"];

        // Category Filter
        if (!empty($selectedCats)) {
            $escapedCats = [];
            foreach ($selectedCats as $c) {
                if (!empty($c)) {
                    $escapedCats[] = "'" . mysqli_real_escape_string($con, trim($c)) . "'";
                }
            }
            if (!empty($escapedCats)) {
                $catIn = implode(',', $escapedCats);
                $whereConditions[] = "(d.cateid IN ($catIn) OR d.category IN ($catIn) OR c.c_name IN ($catIn) OR c.c_id IN ($catIn))";
            }
        }

        // Rating Filter
        if (!empty($selectedRatings)) {
            $escapedRatings = [];
            foreach ($selectedRatings as $r) {
                $escapedRatings[] = (int)$r;
            }
            if (!empty($escapedRatings)) {
                $ratIn = implode(',', $escapedRatings);
                $whereConditions[] = "(FLOOR(d.ratings) IN ($ratIn) OR d.ratings IN ($ratIn))";
            }
        }

        $whereSql = implode(' AND ', $whereConditions);

        // Sorting
        $orderBy = "d.d_id DESC";
        if ($sortBy === 1) {
            $orderBy = "min_price ASC";
        } elseif ($sortBy === 2) {
            $orderBy = "min_price DESC";
        } elseif ($sortBy === 3) {
            $orderBy = "d.ratings DESC";
        }

        $sql = "SELECT d.*, 
                       COALESCE(MIN(p.pp), 0) AS min_price, 
                       COALESCE(MAX(p.oprice), 0) AS max_oprice,
                       COALESCE(SUM(p.total_stock), 0) AS total_stock,
                       c.c_name AS category_name
                FROM dishes d
                LEFT JOIN price p ON p.pcode = d.rs_id
                LEFT JOIN res_category c ON (c.c_id = d.cateid OR c.c_name = d.category)
                WHERE $whereSql
                GROUP BY d.d_id
                ORDER BY $orderBy";

        $res = mysqli_query($con, $sql);
        $totalProducts = 0;
        $html = '';

        if ($res && mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $p_id = !empty($row['rs_id']) ? (int)$row['rs_id'] : (int)$row['d_id'];
                $p_name = htmlspecialchars($row['dish_name']);
                $imgKey = !empty($row['img']) ? $row['img'] : 'img/products/hp_laptop.jpg';
                $p_img = htmlspecialchars(ProductModel::resolveImage($imgKey));
                $catName = !empty($row['category_name']) ? htmlspecialchars($row['category_name']) : (!empty($row['category']) ? htmlspecialchars($row['category']) : 'Hardware');
                
                $price = (float)$row['min_price'];
                $oprice = (float)$row['max_oprice'];
                if ($price <= 0) $price = 1500;
                if ($oprice <= $price) $oprice = $price * 1.25;

                // Price Filter
                if ($minPrice > 0 && $price < $minPrice) continue;
                if ($maxPrice > 0 && $price > $maxPrice) continue;

                if (!empty($selectedPrices)) {
                    $matchPrice = false;
                    foreach ($selectedPrices as $range) {
                        if (strpos($range, '-') !== false) {
                            list($minP, $maxP) = explode('-', $range);
                            if ($price >= (float)$minP && $price <= (float)$maxP) {
                                $matchPrice = true;
                                break;
                            }
                        } elseif (strpos($range, '>') !== false || strpos($range, '+') !== false) {
                            $minP = (float)str_replace(['>', '+'], '', $range);
                            if ($price >= $minP) {
                                $matchPrice = true;
                                break;
                            }
                        }
                    }
                    if (!$matchPrice) continue;
                }

                $discount = round((($oprice - $price) / $oprice) * 100);
                if ($discount <= 0) $discount = 12;
                $rating = !empty($row['ratings']) ? (int)$row['ratings'] : 5;
                $detailUrl = "details.php?id=" . $p_id;

                $totalProducts++;

                $html .= '<div class="col-6 col-sm-6 col-md-4 col-lg-3 kc-prod-grid-item mb-3 px-1">';
                $html .= '  <div class="kc-prod-card h-100">';
                $html .= '    <span class="kc-discount-badge">-' . $discount . '%</span>';
                $html .= '    <div class="kc-prod-img-box">';
                $html .= '      <a href="' . $detailUrl . '">';
                $html .= '        <img src="' . $p_img . '" alt="' . $p_name . '" onerror="this.src=\'img/products/hp_laptop.jpg\'">';
                $html .= '      </a>';
                $html .= '    </div>';
                $html .= '    <div class="mb-1">';
                $html .= '      <span class="badge badge-light text-primary" style="font-size:11px;font-weight:600;">' . $catName . '</span>';
                $html .= '    </div>';
                $html .= '    <h3 class="kc-prod-title">';
                $html .= '      <a href="' . $detailUrl . '">' . $p_name . '</a>';
                $html .= '    </h3>';
                $html .= '    <div class="kc-prod-rating">';
                for ($st = 1; $st <= 5; $st++) {
                    $html .= ($st <= $rating) ? '<i class="fa fa-star text-warning"></i>' : '<i class="fa fa-star-o text-muted"></i>';
                }
                $html .= '      <span class="text-muted ml-1" style="font-size:11px;">(' . $rating . '.0)</span>';
                $html .= '    </div>';
                $html .= '    <div class="kc-prod-price-box">';
                $html .= '      <span class="kc-price-curr">₹' . number_format($price, 2) . '</span>';
                if ($oprice > $price) {
                    $html .= '  <span class="kc-price-old">₹' . number_format($oprice, 2) . '</span>';
                }
                $html .= '    </div>';
                $html .= '    <div class="kc-card-actions mt-auto">';
                $html .= '      <button type="button" class="kc-btn-cart-action" onclick="quickAddToCart(' . $p_id . ', this)">';
                $html .= '        <i class="fa fa-cart-plus"></i> Add to Cart';
                $html .= '      </button>';
                $html .= '    </div>';
                $html .= '  </div>';
                $html .= '</div>';
            }
        }

        if ($totalProducts === 0) {
            $html = '<div class="col-12 text-center py-5">
                        <div class="p-5" style="background:#ffffff; border:1.5px dashed #CBD5E1; border-radius:16px;">
                            <i class="fa fa-microchip text-muted mb-3" style="font-size:48px;"></i>
                            <h4 style="font-family:\'Outfit\',sans-serif; font-weight:700; color:#0F172A;">No Matching Products Found</h4>
                            <p class="text-muted" style="font-size:14px;">Try clearing some filters or selecting another category.</p>
                            <a href="category_list.php" class="btn btn-primary mt-2" style="border-radius:8px; font-weight:700; background:linear-gradient(135deg,#0D47A1,#0070F3);">Reset Filters</a>
                        </div>
                    </div>';
        }

        $this->json([
            'result' => $html,
            'total_products' => $totalProducts
        ]);
    }
}
