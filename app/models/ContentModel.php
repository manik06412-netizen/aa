<?php
namespace App\Models;

use App\Core\Model;

class ContentModel extends Model {
    /**
     * Get Hero Sliders
     */
    public function getSliders($limit = 10) {
        $banners = $this->fetchAllPrepared("SELECT id, banner_img as fpath, 'slider' as source FROM slider ORDER BY id DESC LIMIT " . (int)$limit);
        return $banners ?: [];
    }

    /**
     * Get Promotional Ad Banners (from slider table)
     */
    public function getAdBanners($limit = 5) {
        $ads = $this->fetchAllPrepared("SELECT id, fpath, k1, k2, k3, link, 'banner' as source FROM banner ORDER BY id DESC LIMIT " . (int)$limit);
        return $ads ?: [];
    }

    /**
     * Get FAQs
     */
    public function getFaqs($limit = 10) {
        $sql = "SELECT * FROM tbl_faq ORDER BY faq_id DESC LIMIT " . (int)$limit;
        return $this->fetchAllPrepared($sql);
    }

    /**
     * Get Testimonials
     */
    public function getTestimonials($limit = 10) {
        $sql = "SELECT * FROM testi ORDER BY id DESC LIMIT " . (int)$limit;
        return $this->fetchAllPrepared($sql);
    }

    /**
     * Get Static Page Content
     */
    public function getPage($pageName = null) {
        $sql = "SELECT * FROM tbl_page LIMIT 1";
        return $this->fetchOnePrepared($sql);
    }

    /**
     * Get Social Links
     */
    public function getSocialLinks() {
        $sql = "SELECT * FROM tbl_social";
        return $this->fetchAllPrepared($sql);
    }
}
