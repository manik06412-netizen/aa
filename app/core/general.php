<?php
/**
 * Karuda Computers - General Site Configuration & Meta Info Loader
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = \App\Core\Database::getInstance();
$con = $db->getConnection();

// Initialize variables
$CON_LOGO = "img/karuda_logo.png";
$CON_FEVICON = "img/karuda_logo.png";
$CON_COPYRIGHTSLOGO = $CON_CONTACT_ADDRESS = $CON_CONTACT_EMAIL = $CON_CONTACT_PHONE = $CON_ALTERNATE_NUMBER = $CON_WHATSAPP = $CON_FACEBOOK = $CON_INSTA = $CON_ABOUT = "";
$CON_TITLE = 'Karuda Computers — Premium Computers, Laptops & Accessories';
$ABOUT_TITLE = 'About Us';
$ABOUT_CONTENT = '';
$FAQ_TITLE = 'FAQ';
$CONTACT_TITLE = 'Contact Us';

if (!function_exists('resolve_image_url')) {
    function resolve_image_url($dbPath, $fallback = 'img/alter_img.jpg') {
        if (empty($dbPath)) {
            return (defined('BASE_URL') ? BASE_URL : '') . $fallback;
        }

        $clean = preg_replace('#/+#', '/', ltrim(trim($dbPath), './'));
        $root = dirname(__DIR__, 2);
        $base = defined('BASE_URL') ? BASE_URL : '';

        $candidates = [
            'avadmin/' . $clean,
            'admin/' . $clean,
            'avadmin/res_img/dishes/' . basename($clean),
            'avadmin/Res_img/dishes/' . basename($clean),
            'avadmin/Res_img/' . basename($clean),
            'avadmin/res_img/' . basename($clean),
            'admin/Res_img/dishes/' . basename($clean),
            'admin/res_img/dishes/' . basename($clean),
            'admin/Res_img/' . basename($clean),
            'admin/res_img/' . basename($clean),
            'admin/uploads/' . basename($clean),
            'admin/uploads/logo/' . basename($clean),
            'img/' . basename($clean),
            $clean
        ];

        foreach ($candidates as $rel) {
            $full = $root . '/' . $rel;
            if (file_exists($full) && !is_dir($full)) {
                return $base . $rel;
            }
        }

        return $base . (strpos($clean, 'admin') === 0 || strpos($clean, 'img') === 0 ? $clean : 'avadmin/' . $clean);
    }
}

// Query for logo
$query_logo = "SELECT * FROM logo LIMIT 1";
$result_logo = mysqli_query($con, $query_logo);
if ($result_logo && ($row_logo = mysqli_fetch_assoc($result_logo))) {
    if (isset($row_logo['image'])) {
        $CON_LOGO = resolve_image_url($row_logo['image']);
    }
}

// Query for fevicon
$query_fevicon = "SELECT * FROM fevicon LIMIT 1";
$result_fevicon = mysqli_query($con, $query_fevicon);
if ($result_fevicon && ($row_fevicon = mysqli_fetch_assoc($result_fevicon))) {
    if (isset($row_fevicon['fevicon'])) {
        $cleanFavicon = ltrim($row_fevicon['fevicon'], './');
        $CON_FEVICON = (defined('BASE_URL') ? BASE_URL : '') . 'admin/' . $cleanFavicon;
    }
}

// Query for footer_contact
$query_footer_contact = "SELECT * FROM footer_contact LIMIT 1";
$result_footer_contact = mysqli_query($con, $query_footer_contact);
if ($result_footer_contact && ($row_footer_contact = mysqli_fetch_assoc($result_footer_contact))) {
    $CON_COPYRIGHTS = $row_footer_contact['copyrights'] ?? '';
    $CON_CONTACT_ADDRESS = $row_footer_contact['contact_address'] ?? '';
    $CON_CONTACT_EMAIL = $row_footer_contact['contact_email'] ?? '';
    $CON_CONTACT_PHONE = $row_footer_contact['contact_phone'] ?? '';
    $CON_ALTERNATE_NUMBER = $row_footer_contact['alternate_number'] ?? '';
    $CON_WHATSAPP = $row_footer_contact['whatsapp'] ?? '';
    $CON_FACEBOOK = $row_footer_contact['facebook'] ?? '';
    $CON_INSTA = $row_footer_contact['instagram'] ?? '';
}

// Query for about_us
$query_about_us = "SELECT * FROM tbl_page LIMIT 1";
$result_about_us = mysqli_query($con, $query_about_us);
if ($result_about_us && ($row_about_us = mysqli_fetch_assoc($result_about_us))) {
    $ABOUT_TITLE = $row_about_us['about_title'] ?? 'About Us';
    $ABOUT_CONTENT = $row_about_us['about_content'] ?? '';
    $FAQ_TITLE = $row_about_us['faq_title'] ?? 'FAQ';
    $CONTACT_TITLE = $row_about_us['contact_title'] ?? 'Contact Us';
}

// Query for social links
$statement = "SELECT * FROM tbl_social";
$st = mysqli_query($con, $statement);
