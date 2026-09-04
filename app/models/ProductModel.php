<?php
namespace App\Models;

use App\Core\Model;

class ProductModel extends Model {
    /**
     * Get All Active Products with Pricing
     */
    public function getActiveProducts($limit = null, $category = null, $subcate = null) {
        $sql = "SELECT DISTINCT d.*, p.pp, p.oprice, p.discount, p.id as price_id 
                FROM dishes d 
                JOIN price p ON d.rs_id = p.pcode 
                WHERE d.status = '1'";
        $params = [];

        if (!empty($category) && $category !== 'All') {
            $catTrim = trim($category);
            if (is_numeric($catTrim)) {
                $sql .= " AND (d.cateid = ? OR d.category IN (SELECT c_name FROM res_category WHERE c_id = ?))";
                $params[] = $catTrim;
                $params[] = $catTrim;
            } else {
                $sql .= " AND (TRIM(d.category) = ? OR d.cateid IN (SELECT c_id FROM res_category WHERE c_name = ?))";
                $params[] = $catTrim;
                $params[] = $catTrim;
            }
        }

        if (!empty($subcate)) {
            $subTrim = trim($subcate);
            $sql .= " AND (d.subcate = ? OR d.dish_name LIKE ?)";
            $params[] = $subTrim;
            $params[] = '%' . $subTrim . '%';
        }

        $sql .= " ORDER BY d.d_id DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return $this->fetchAllPrepared($sql, $params);
    }

    /**
     * Get Product By rs_id
     */
    public function getProductById($id) {
        $idStr = (string)$id;
        $product = $this->fetchOnePrepared("SELECT * FROM dishes WHERE rs_id = ? LIMIT 1", [$idStr]);

        if ($product) {
            // Fetch Prices
            $priceSql = "SELECT * FROM price WHERE pcode = ? ORDER BY pp ASC";
            $product['prices'] = $this->fetchAllPrepared($priceSql, [$idStr]);

            // Fetch Category Name
            $catId = $product['cateid'];
            $catRow = $this->fetchOnePrepared("SELECT c_name FROM res_category WHERE c_id = ? LIMIT 1", [$catId]);
            $product['category_name'] = $catRow ? $catRow['c_name'] : $product['category'];

            // Fetch Reviews
            $revSql = "SELECT * FROM cust_reviews WHERE cid = ? ORDER BY uid DESC";
            $product['reviews'] = $this->fetchAllPrepared($revSql, [$idStr]);

            // Calculate Rating
            $avgRow = $this->fetchOnePrepared("SELECT AVG(uratings) as avg_rating, COUNT(*) as total_reviews FROM cust_reviews WHERE cid = ?", [$idStr]);
            $product['avg_rating'] = ($avgRow && $avgRow['avg_rating'] > 0) ? round($avgRow['avg_rating'], 1) : (float)$product['ratings'];
            $product['total_reviews'] = $avgRow ? (int)$avgRow['total_reviews'] : 0;
        }

        return $product;
    }

    /**
     * Search Products
     */
    public function searchProducts($keyword) {
        $term = '%' . trim($keyword) . '%';
        $sql = "SELECT DISTINCT d.*, p.pp, p.oprice, p.discount 
                FROM dishes d 
                JOIN price p ON d.rs_id = p.pcode 
                WHERE d.status = '1' AND (d.dish_name LIKE ? OR d.category LIKE ? OR d.s_desc LIKE ?)
                ORDER BY d.d_id DESC";
        return $this->fetchAllPrepared($sql, [$term, $term, $term]);
    }

    /**
     * Resolve Image Path
     */
    public static function resolveImage($path) {
        if (function_exists('resolve_image_url')) {
            return resolve_image_url($path);
        }
        if (empty($path)) {
            return 'img/products/prod_laptop_1.jpg';
        }
        $clean = preg_replace('#/+#', '/', ltrim(trim($path), './'));
        if (file_exists(__DIR__ . '/../../' . $clean)) {
            return $clean;
        }
        if (file_exists(__DIR__ . '/../../avadmin/' . $clean)) {
            return 'avadmin/' . $clean;
        }
        if (file_exists(__DIR__ . '/../../admin/' . $clean)) {
            return 'admin/' . $clean;
        }
        return $clean;
    }
}
