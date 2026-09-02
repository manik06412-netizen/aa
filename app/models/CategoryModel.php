<?php
namespace App\Models;

use App\Core\Model;

class CategoryModel extends Model {
    /**
     * Get All Categories
     */
    public function getAllCategories() {
        return $this->fetchAllPrepared("SELECT * FROM res_category ORDER BY c_name ASC");
    }

    /**
     * Get Category by ID or Name
     */
    public function getCategory($idOrName) {
        $val = (string)$idOrName;
        if (is_numeric($val)) {
            return $this->fetchOnePrepared("SELECT * FROM res_category WHERE c_id = ? LIMIT 1", [$val]);
        }
        return $this->fetchOnePrepared("SELECT * FROM res_category WHERE c_name = ? LIMIT 1", [$val]);
    }

    /**
     * Get Subcategories by Category Name
     */
    public function getSubcategories($categoryName) {
        return $this->fetchAllPrepared("SELECT DISTINCT subcate FROM dishes WHERE category = ? AND subcate != ''", [$categoryName]);
    }
}
