<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($con) || !$con) {
    if (class_exists('\\App\\Core\\Database')) {
        $con = \App\Core\Database::getInstance()->getConnection();
    } elseif (file_exists(__DIR__ . '/../dbconnect.php')) {
        require_once __DIR__ . '/../dbconnect.php';
    }
}

if (empty($_SESSION["selectedCurrency"])) {
    $_SESSION["selectedCurrency"] = "₹";
}
$_SESSION['default_price_'] = '₹';

if (!function_exists('addCard')) {
    function addCard(){
        global $con;
        if(isset($_SESSION['uid'])){
            $user_id = $_SESSION['uid'];
            $card = mysqli_query($con, "SELECT * FROM card WHERE userid='$user_id' AND status='0'");
            return $card ? mysqli_num_rows($card) : 0;
        }
        return 0;
    }
}

if (!function_exists('getWishlistCount')) {
    function getWishlistCount(){
        global $con;
        if(isset($_SESSION['uid'])){
            $user_id = $_SESSION['uid'];
            $w = mysqli_query($con, "SELECT * FROM watch_list WHERE userid='$user_id'");
            return $w ? mysqli_num_rows($w) : 0;
        }
        return 0;
    }
}

$cart_count = addCard();
$wish_count = getWishlistCount();
$is_logged_in = !empty($_SESSION['uname']) || (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true);
$user_display_name = $is_logged_in ? $_SESSION['uname'] : 'Login / Register';
?>
<script>
window.IS_USER_LOGGED_IN = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
</script>
<?php
$current_page = basename($_SERVER['SCRIPT_NAME']);
$current_uri = $_SERVER['REQUEST_URI'] ?? '';
$is_shop = ($current_page == 'allproducts.php' || strpos($current_uri, 'allproducts') !== false);
$is_categories = ($current_page == 'category_list.php' || $current_page == 'allcategories.php' || strpos($current_uri, 'category_list') !== false);
$is_about = ($current_page == 'about.php' || strpos($current_uri, 'about') !== false);
$is_contact = ($current_page == 'contact.php' || strpos($current_uri, 'contact') !== false);
$is_track = ($current_page == 'track_order.php' || strpos($current_uri, 'track_order') !== false);
$is_faq = ($current_page == 'faq.php' || strpos($current_uri, 'faq') !== false);
$is_home = ($current_page == 'index.php' && !$is_shop && !$is_categories && !$is_about && !$is_contact && !$is_track && !$is_faq);
?>

<style>
/* ══════════════════════════════════════════════════════════════
   USED LAPTOP STORE (INFOCOM SYSTEMS) STYLE NAVBAR FOR KARUDA
 ══════════════════════════════════════════════════════════════ */
.kc-top-bar {
    display: none !important;
}

.kc-unified-header.uls-header {
    background: #ffffff !important;
    position: sticky !important;
    top: 0 !important;
    width: 100% !important;
    z-index: 1000 !important;
    border-bottom: 1px solid #E5E7EB !important;
    padding: 0 !important;
    margin: 0 !important;
    transition: all 0.25s ease;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.kc-unified-header.uls-header.kc-is-sticky {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    z-index: 99999 !important;
    background: #ffffff !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
    animation: ulsStickySlideDown 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes ulsStickySlideDown {
    from { transform: translateY(-100%); }
    to   { transform: translateY(0); }
}

/* ── Row 1: Main Header (Logo | Wide Center Search | Action Items) ── */
.uls-header-top {
    background: #ffffff;
    padding: 10px 0;
    border-bottom: 1px solid #F1F5F9;
}
.uls-top-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

/* Brand Logo (Matching User Uploaded Image) */
.uls-brand {
    display: inline-flex !important;
    align-items: center !important;
    gap: 10px !important;
    text-decoration: none !important;
    flex-shrink: 0 !important;
    white-space: nowrap !important;
}
.uls-brand img, .uls-brand-logo-img {
    height: 48px !important;
    max-height: 48px !important;
    width: auto !important;
    object-fit: contain !important;
    margin: 0 !important;
    padding: 0 !important;
    filter: drop-shadow(0 2px 6px rgba(0, 112, 243, 0.25));
}
.uls-brand-text-wrap {
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    line-height: 0.9 !important;
    margin: 0 !important;
    padding-left: 0px !important;
}
.uls-brand-title-main {
    font-family: 'Poppins', 'Montserrat', 'Inter', sans-serif !important;
    font-size: 26px !important;
    font-weight: 900 !important;
    color: #000000 !important;
    letter-spacing: 0.5px !important;
    text-transform: uppercase !important;
    margin: 0 !important;
    padding: 0 !important;
    line-height: 1 !important;
}
.uls-brand-title-sub {
    font-family: 'Poppins', 'Montserrat', 'Inter', sans-serif !important;
    font-size: 13px !important;
    font-weight: 900 !important;
    color: #0070F3 !important;
    letter-spacing: 5.2px !important;
    text-transform: uppercase !important;
    margin-top: 2px !important;
    padding: 0 !important;
    line-height: 1 !important;
}

/* Center Search Bar (Wide, Light Gray Fill, Rounded) */
.uls-search-col {
    flex: 1;
    max-width: 580px;
}
/* On Homepage: Hide top search by default, show smoothly on scroll past hero search */
.uls-search-col.kc-home-nav-search {
    opacity: 0;
    visibility: hidden;
    transform: translateY(-6px);
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.uls-search-col.kc-home-nav-search.kc-show-top-search {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}
.uls-search-wrap {
    position: relative;
    width: 100%;
}
.uls-search-form {
    display: flex;
    align-items: center;
    background: #FFFFFF;
    border: 1.5px solid #38BDF8 !important; /* Light Cyan Border */
    border-radius: 25px;
    padding: 6px 14px;
    box-shadow: 0 4px 14px rgba(56, 189, 248, 0.12);
    transition: all 0.2s ease;
}
.uls-search-form:focus-within {
    background: #ffffff;
    border-color: #00BCD4 !important;
    box-shadow: 0 0 0 3.5px rgba(0, 188, 212, 0.20) !important;
}
.uls-mobile-submit-btn {
    background: linear-gradient(135deg, #00BCD4, #0070F3) !important;
    color: #ffffff !important;
    border: none !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 13px !important;
    cursor: pointer !important;
    margin-left: 6px !important;
    flex-shrink: 0 !important;
    box-shadow: 0 3px 10px rgba(0, 188, 212, 0.35) !important;
    transition: transform 0.2s ease !important;
}
.uls-mobile-submit-btn:hover {
    transform: scale(1.08) !important;
}
.uls-search-icon {
    font-size: 14px;
    color: #64748B;
    margin-right: 12px;
    flex-shrink: 0;
}
.uls-search-input {
    flex: 1;
    border: none !important;
    outline: none !important;
    background: transparent !important;
    font-size: 14px;
    font-weight: 500;
    color: #0F172A;
    padding: 0 !important;
    margin: 0 !important;
}
.uls-search-input::placeholder {
    color: #94A3B8;
    font-weight: 400;
    font-size: 13.5px;
}

/* Right Actions: Categories | Wishlist | Cart | Account */
.uls-actions-col {
    flex-shrink: 0;
}
.uls-actions-group {
    display: flex;
    align-items: center;
    gap: 22px;
}
.uls-action-link {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #1E293B !important;
    text-decoration: none !important;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.15s ease;
    cursor: pointer;
    background: none;
    border: none;
    padding: 4px 0;
}
.uls-action-link:hover {
    color: #003B95 !important;
}
.uls-action-icon {
    font-size: 17px;
    color: #1E293B;
    transition: color 0.15s ease;
}
.uls-action-link:hover .uls-action-icon {
    color: #003B95;
}
.uls-badge-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
}
.uls-badge {
    position: absolute;
    top: -8px;
    right: -10px;
    background: #003B95;
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    min-width: 17px;
    height: 17px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 1.5px solid #ffffff;
}
.uls-badge-red {
    background: #EF4444;
}

/* Mobile Search Bar Trigger & Expandable Box */
.kc-mobile-search-trigger {
    display: none !important;
}

@media (max-width: 991px) {
    .kc-mobile-search-trigger {
        display: none !important;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #F1F5F9;
        color: #0070F3 !important;
        border: 1px solid #E2E8F0;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        opacity: 0;
        transform: scale(0.85);
        pointer-events: none;
        margin-right: 2px;
    }
    
    .kc-show-mobile-search-icon .kc-mobile-search-trigger {
        display: inline-flex !important;
        opacity: 1;
        transform: scale(1);
        pointer-events: auto;
    }
    
    .kc-mobile-search-trigger:hover, .kc-mobile-search-trigger.active {
        background: #0070F3 !important;
        color: #ffffff !important;
        border-color: #0070F3 !important;
        box-shadow: 0 4px 12px rgba(0, 112, 243, 0.35);
    }
    
    .uls-mobile-search-wrap {
        display: none;
        margin-top: 10px;
        position: relative;
        padding-bottom: 4px;
    }
    
    .uls-mobile-search-wrap.active {
        display: block !important;
        animation: kcSlideDownSearch 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
}

@keyframes kcSlideDownSearch {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ── Row 2: Nav Strip ── */
.uls-header-nav {
    background: linear-gradient(90deg, #002566 0%, #003B95 50%, #002566 100%) !important;
    padding: 0 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.12);
    border-bottom: 2px solid #002566;
    box-shadow: 0 4px 14px rgba(0, 37, 102, 0.35);
}
.uls-nav-row {
    display: flex;
    align-items: center;
    justify-content: center;
}
.uls-nav-links-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.uls-nav-item {
    position: relative;
    display: flex;
    align-items: center;
}
.uls-nav-item:not(:last-child)::after {
    content: '/';
    margin-left: 16px;
    color: rgba(255, 255, 255, 0.3);
    font-size: 15px;
    font-weight: 300;
}
.uls-nav-item > a {
    color: #FFFFFF !important;
    text-decoration: none !important;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    padding: 14px 14px;
    position: relative;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.uls-nav-item > a:hover {
    color: #93C5FD !important;
    background: rgba(255, 255, 255, 0.08);
}
.uls-nav-item.active > a {
    color: #FFFFFF !important;
    font-weight: 800;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 6px;
}
.uls-nav-item.active > a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 8px;
    right: 8px;
    height: 3px;
    background: #60A5FA;
    border-radius: 2px 2px 0 0;
    box-shadow: 0 0 10px rgba(96, 165, 250, 0.9);
}

/* Dropdown on Categories */
.kc-dropdown-menu {
    position: absolute !important;
    top: calc(100% + 8px) !important;
    left: 0 !important;
    min-width: 250px !important;
    background: #ffffff !important;
    border-radius: 14px !important;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12), 0 0 0 1px #E2E8F0 !important;
    padding: 8px !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transform: translateY(8px) !important;
    transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    z-index: 1050 !important;
}
.uls-has-dropdown:hover .kc-dropdown-menu {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
}
.kc-dropdown-item {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 10px 14px !important;
    border-radius: 8px !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    color: #1E293B !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
}
.kc-dropdown-item:hover {
    background: #F0F9FF !important;
    color: #0070F3 !important;
    padding-left: 18px !important;
}

/* Hamburger button on right of Row 2 */
.uls-hamburger-btn {
    background: none;
    border: none;
    color: #1E293B;
    font-size: 19px;
    cursor: pointer;
    padding: 4px 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.15s ease;
}
.uls-hamburger-btn:hover {
    background: #F1F5F9;
    color: #0070F3;
}

/* ── Live Auto-Suggest Floating Dropdown ── */
.kc-suggest-dropdown {
    position: absolute !important;
    top: calc(100% + 8px) !important;
    left: 0 !important;
    right: 0 !important;
    background: #ffffff !important;
    border-radius: 12px !important;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.08) !important;
    overflow: hidden !important;
    z-index: 999999 !important;
    display: none;
    text-align: left !important;
    animation: ulsSlideDown 0.2s ease-out;
}
@keyframes ulsSlideDown {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}
.kc-suggest-header {
    background: #F8FAFC;
    padding: 10px 18px;
    font-size: 12px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.kc-suggest-list {
    max-height: 380px;
    overflow-y: auto;
    padding: 6px 0;
    margin: 0;
    list-style: none;
}
.kc-suggest-item {
    display: flex;
    align-items: center;
    padding: 10px 18px;
    gap: 14px;
    text-decoration: none !important;
    color: #1E293B;
    border-bottom: 1px solid #F1F5F9;
    transition: all 0.15s ease;
}
.kc-suggest-item:last-child {
    border-bottom: none;
}
.kc-suggest-item:hover, .kc-suggest-item.selected {
    background: #F0F9FF;
    color: #0070F3;
    padding-left: 22px;
}
.kc-suggest-img {
    width: 46px;
    height: 46px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #E2E8F0;
    background: #F8FAFC;
    flex-shrink: 0;
}
.kc-suggest-details {
    flex: 1;
    min-width: 0;
}
.kc-suggest-title {
    font-size: 14px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 3px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.kc-suggest-cat {
    font-size: 12px;
    color: #64748B;
    margin: 0;
}
.kc-suggest-price-box {
    text-align: right;
    flex-shrink: 0;
}
.kc-suggest-price {
    font-size: 14.5px;
    font-weight: 800;
    color: #0070F3;
}
.kc-suggest-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 12px;
    background: #DCFCE7;
    color: #166534;
    margin-top: 3px;
}
.kc-suggest-footer {
    background: #F8FAFC;
    padding: 11px 18px;
    border-top: 1px solid #E2E8F0;
    text-align: center;
}
.kc-suggest-view-all {
    color: #0070F3;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.kc-suggest-view-all:hover {
    color: #0056B3;
    text-decoration: underline !important;
}


/* ══════════════════════════════════════════════════════════════
   RESPONSIVE — MOBILE & TABLET
 ══════════════════════════════════════════════════════════════ */

/* Tablet (768px - 991px) */
@media (max-width: 991px) {
    .uls-search-col { display: none !important; }
    .uls-mobile-search-wrap { display: none !important; } /* Hero section has full search already */
    .uls-header-nav { display: none !important; }
    .uls-actions-group { gap: 14px; }
    .uls-header-top { padding: 10px 0; }
    .uls-brand img, .uls-brand-logo-img { height: 42px !important; max-height: 42px !important; }
    .uls-brand-title-main { font-size: 22px !important; }
    .uls-brand-title-sub { font-size: 11px !important; letter-spacing: 4.2px !important; }
    .uls-action-link span { font-size: 12.5px; }
}

/* Mobile (< 767px) */
@media (max-width: 767px) {
    .uls-top-row { flex-wrap: nowrap; gap: 8px; justify-content: space-between; }
    .uls-brand-col { flex: 1; min-width: 0; }
    .uls-brand { gap: 6px !important; }
    .uls-brand img, .uls-brand-logo-img { height: 36px !important; max-height: 36px !important; }
    .uls-brand-title-main { font-size: 19px !important; letter-spacing: 0.3px !important; }
    .uls-brand-title-sub { font-size: 9.5px !important; letter-spacing: 3.5px !important; }
    .uls-actions-col { flex-shrink: 0; }
    .uls-actions-group { gap: 10px; }
    .uls-action-link span { display: none; }           /* Hide text labels, show only icons */
    .uls-action-icon { font-size: 20px; }              /* Touch friendly icons */
    .uls-mobile-search-wrap { display: none !important; } /* Hero section has search already */
    .kc-suggest-dropdown { border-radius: 0 0 12px 12px !important; }
    /* Hide search submit button icon on mobile */
    .kc-hero-search-btn { width: 42px; padding: 10px; border-radius: 50%; }
    .kc-hero-search-btn span { display: none !important; }
}

/* Small Mobile (< 480px) */
@media (max-width: 480px) {
    .uls-header-top { padding: 8px 0; }
    .uls-brand img, .uls-brand-logo-img { height: 35px !important; max-height: 35px !important; }
    .uls-brand-title-main { font-size: 16px !important; }
    .uls-brand-title-sub { font-size: 8.5px !important; }
    .uls-actions-group { gap: 8px; }
    .uls-action-icon { font-size: 18px; }
    .uls-badge { min-width: 15px; height: 15px; font-size: 9px; top: -6px; right: -8px; padding: 0 3px; }
}

/* Very Small Mobile (< 360px) */
@media (max-width: 360px) {
    .uls-brand img { max-height: 32px; }
    .uls-actions-group { gap: 6px; }
    .uls-action-icon { font-size: 17px; }
}


/* ── Offcanvas Mobile Drawer ── */
.kc-drawer-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(11, 25, 44, 0.65);
    backdrop-filter: blur(4px);
    z-index: 999998;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}
.kc-drawer-backdrop.active {
    opacity: 1;
    visibility: visible;
}

/* Mobile Offcanvas Drawer */
.kc-mobile-drawer {
    position: fixed;
    top: 0;
    left: -320px;
    width: 300px;
    max-width: 85vw;
    height: 100vh;
    background: #ffffff;
    z-index: 999999;
    box-shadow: 10px 0 40px rgba(0, 0, 0, 0.3);
    transition: transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.kc-mobile-drawer.active {
    transform: translateX(320px);
}

/* Drawer Header */
.kc-drawer-header {
    background: linear-gradient(135deg, #002566 0%, #003B95 100%);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid #003B95;
}
.kc-drawer-header img {
    max-height: 34px;
}
.kc-drawer-close {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

/* User Account Strip in Drawer */
.kc-drawer-user {
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.kc-drawer-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.kc-drawer-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #003B95, #002566);
    color: #ffffff;
    font-size: 18px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
}
.kc-drawer-user-name {
    font-size: 14px;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
    line-height: 1.2;
}
.kc-drawer-user-sub {
    font-size: 12px;
    color: #64748B;
    margin: 0;
}
.kc-drawer-login-btn {
    background: linear-gradient(135deg, #003B95, #002566);
    color: #ffffff !important;
    border-radius: 8px;
    padding: 7px 14px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-block;
}

/* Drawer Body Navigation */
.kc-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 15px 0;
}
.kc-drawer-nav-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    color: #1E293B;
    font-size: 14.5px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}
.kc-drawer-nav-item i.kc-nav-ico {
    width: 24px;
    font-size: 16px;
    color: #64748B;
    margin-right: 8px;
}
.kc-drawer-nav-item:hover, .kc-drawer-nav-item.active {
    background: #EFF6FF;
    color: #0070F3;
    border-left-color: #00BCD4;
}
.kc-drawer-nav-item:hover i.kc-nav-ico, .kc-drawer-nav-item.active i.kc-nav-ico {
    color: #0070F3;
}
.kc-drawer-badge {
    background: #0070F3;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 12px;
}
.kc-drawer-badge-red {
    background: #E11D48;
}

/* Categories Accordion inside Drawer */
.kc-drawer-cat-accordion {
    display: none;
    background: #F8FAFC;
    padding: 8px 0;
    border-top: 1px solid #E2E8F0;
    border-bottom: 1px solid #E2E8F0;
}
.kc-drawer-cat-accordion.show {
    display: block;
}
.kc-drawer-sub-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 20px 9px 40px;
    color: #475569;
    font-size: 13.5px;
    font-weight: 500;
    text-decoration: none !important;
}
.kc-drawer-sub-link:hover {
    color: #0070F3;
    background: #F1F5F9;
}

.kc-mobile-toggle-btn {
    display: none !important;
}

@media (min-width: 992px) {
    .kc-mobile-toggle-btn {
        display: none !important;
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .kc-nav-menu {
        gap: 8px !important;
    }
    .kc-menu-item > a {
        padding: 6px 10px !important;
        font-size: 14px !important;
    }
    .kc-nav-brand img {
        max-height: 38px !important;
    }
    .kc-circle-action-btn {
        width: 38px !important;
        height: 38px !important;
        font-size: 14.5px !important;
    }
}

@media (max-width: 991px) {
    .kc-navbar-row {
        padding: 10px 0 !important;
    }
    .kc-nav-brand img {
        max-height: 36px !important;
    }
    .kc-mobile-toggle-btn {
        display: inline-flex !important;
    }
    .kc-circle-action-btn {
        width: 36px !important;
        height: 36px !important;
        font-size: 14px !important;
    }
    .kc-actions-group {
        gap: 6px !important;
    }
}

@media (max-width: 576px) {
    .kc-top-bar {
        font-size: 11.5px !important;
        padding: 5px 0 !important;
    }
    .kc-navbar-row {
        padding: 8px 0 !important;
        gap: 10px !important;
    }
    .kc-nav-brand img {
        max-height: 30px !important;
    }
    .kc-circle-action-btn {
        width: 34px !important;
        height: 34px !important;
        font-size: 13px !important;
    }
    .kc-actions-group {
        gap: 5px !important;
    }
    .kc-badge-pill {
        min-width: 16px !important;
        height: 16px !important;
        font-size: 9px !important;
        top: -3px !important;
        right: -3px !important;
    }
}

@media (max-width: 380px) {
    .kc-nav-brand img {
        max-height: 26px !important;
    }
    .kc-circle-action-btn {
        width: 30px !important;
        height: 30px !important;
        font-size: 12px !important;
    }
    .kc-actions-group {
        gap: 3px !important;
    }
}
</style>

<!-- ── 1. Top Announcement Bar (Preheader) ── -->
<!-- ── Unified Modern Header (usedlaptopstore.in Style) ── -->
<header class="kc-unified-header uls-header" id="kcMainHeader">
    <!-- Row 1: Main Header (Logo | Wide Center Search | Action Items) -->
    <div class="uls-header-top">
        <div class="container">
            <div class="uls-top-row">
                
                <!-- Left: Brand Logo & Title -->
                <div class="uls-brand-col">
                    <a href="index.php" class="uls-brand">
                        <img src="img/karuda_eagle_logo.png" alt="Karuda Computers" class="uls-brand-logo-img" onerror="this.src='img/logo.png'">
                        <div class="uls-brand-text-wrap">
                            <span class="uls-brand-title-main">KARUDA</span>
                            <span class="uls-brand-title-sub">COMPUTERS</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Wide Search Bar with Live Auto-Suggest -->
                <div class="uls-search-col d-none d-lg-block <?php echo $is_home ? 'kc-home-nav-search' : ''; ?>">
                    <div class="uls-search-wrap">
                        <form action="category_list.php" method="GET" class="uls-search-form" id="ulsSearchForm" autocomplete="off">
                            <i class="fa fa-search uls-search-icon"></i>
                            <input type="text" name="search" id="ulsSearchInput" class="uls-search-input" placeholder="Search for products..." autocomplete="off">
                        </form>
                        <!-- Live Auto-Suggest Floating Dropdown -->
                        <div class="kc-suggest-dropdown" id="ulsSuggestBox">
                            <div class="kc-suggest-header">
                                <span><i class="fa fa-bolt text-warning"></i> Matching Products</span>
                                <span id="ulsSuggestCount" class="text-muted">0 items</span>
                            </div>
                            <div class="kc-suggest-list" id="ulsSuggestList"></div>
                            <div class="kc-suggest-footer" id="ulsSuggestFooter">
                                <a href="allproducts.php" class="kc-suggest-view-all" id="ulsSuggestViewAllBtn">
                                    View All Products <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Action Links (Wishlist, Cart, Account) -->
                <div class="uls-actions-col">
                    <div class="uls-actions-group">

                        <!-- Wishlist -->
                        <a href="wishlist.php" class="uls-action-link d-none d-md-inline-flex" title="Wishlist">
                            <div class="uls-badge-wrap">
                                <i class="fa-regular fa-heart uls-action-icon"></i>
                                <?php if ($wish_count > 0): ?>
                                    <span class="uls-badge uls-badge-red" id="wishlistCountBadge"><?php echo $wish_count; ?></span>
                                <?php endif; ?>
                            </div>
                            <span>Wishlist</span>
                        </a>

                        <!-- Cart -->
                        <a href="cart.php" class="uls-action-link" title="Shopping Cart">
                            <div class="uls-badge-wrap">
                                <i class="fa-solid fa-bag-shopping uls-action-icon"></i>
                                <?php if ($cart_count > 0): ?>
                                    <span class="uls-badge" id="cartCountBadge"><?php echo $cart_count; ?></span>
                                <?php endif; ?>
                            </div>
                            <span>Cart</span>
                        </a>

                        <!-- Account / User Profile -->
                        <a href="<?php echo $is_logged_in ? 'userprofile.php' : 'login.php'; ?>" class="uls-action-link" title="My Account">
                            <i class="fa-regular fa-user uls-action-icon"></i>
                            <span><?php echo $is_logged_in ? htmlspecialchars($user_display_name) : 'Account'; ?></span>
                        </a>

                        <!-- Mobile Scroll Search Icon Button -->
                        <button type="button" class="kc-mobile-search-trigger d-lg-none" id="kcMobileSearchToggleBtn" title="Search Store" aria-label="Search Store">
                            <i class="fa fa-search"></i>
                        </button>

                        <!-- Mobile Hamburger Button -->
                        <button type="button" class="uls-hamburger-btn d-lg-none" id="kcMobileNavToggle" aria-label="Open Menu">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Mobile Full-Width Search Bar (Visible on mobile screens below logo) -->
            <div class="uls-mobile-search-wrap d-lg-none">
                <form action="category_list.php" method="GET" class="uls-search-form" id="ulsMobileSearchForm" autocomplete="off">
                    <i class="fa fa-search uls-search-icon"></i>
                    <input type="text" name="search" id="ulsMobileSearchInput" class="uls-search-input" placeholder="Search for products..." autocomplete="off">
                    <button type="submit" class="uls-mobile-submit-btn" aria-label="Search">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
                <!-- Mobile Live Auto-Suggest Floating Dropdown -->
                <div class="kc-suggest-dropdown" id="ulsMobileSuggestBox">
                    <div class="kc-suggest-header">
                        <span><i class="fa fa-bolt text-warning"></i> Matching Products</span>
                        <span id="ulsMobileSuggestCount" class="text-muted">0 items</span>
                    </div>
                    <div class="kc-suggest-list" id="ulsMobileSuggestList"></div>
                    <div class="kc-suggest-footer" id="ulsMobileSuggestFooter">
                        <a href="allproducts.php" class="kc-suggest-view-all" id="ulsMobileSuggestViewAllBtn">
                            View All Products <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Row 2: Centered Navigation Links & Hamburger (usedlaptopstore.in style) -->
    <div class="uls-header-nav d-none d-lg-block">
        <div class="container">
            <div class="uls-nav-row">
                <ul class="uls-nav-links-wrap">
                    <li class="uls-nav-item <?php echo $is_home ? 'active' : ''; ?>">
                        <a href="index.php"><i class="fa-solid fa-house" style="font-size:12px;"></i> Home</a>
                    </li>
                    <li class="uls-nav-item <?php echo $is_shop ? 'active' : ''; ?>">
                        <a href="allproducts.php">Shop</a>
                    </li>
                    <li class="uls-nav-item uls-has-dropdown <?php echo $is_categories ? 'active' : ''; ?>">
                        <a href="category_list.php" class="uls-dropdown-toggle">
                            Categories <i class="fa fa-chevron-down" style="font-size:10px; margin-left:4px; color:#94A3B8;"></i>
                        </a>
                        <div class="kc-dropdown-menu">
                            <?php
                            $nav_cats = [];
                            if (isset($con) && $con) {
                                $nc_q = mysqli_query($con, "SELECT c_id, c_name FROM res_category ORDER BY c_id DESC LIMIT 6");
                                if ($nc_q && mysqli_num_rows($nc_q) > 0) {
                                    while ($ncr = mysqli_fetch_assoc($nc_q)) {
                                        $nav_cats[] = $ncr['c_name'];
                                    }
                                }
                            }
                            if (!empty($nav_cats)) {
                                foreach ($nav_cats as $cname) {
                                    echo '<a href="category_list.php?search=' . urlencode($cname) . '" class="kc-dropdown-item"><i class="fa fa-layer-group text-primary"></i> ' . htmlspecialchars($cname) . '</a>';
                                }
                            } else {
                            ?>
                            <a href="category_list.php?search=Laptops" class="kc-dropdown-item"><i class="fa fa-laptop text-info"></i> Laptops & Notebooks</a>
                            <a href="category_list.php?search=Desktops" class="kc-dropdown-item"><i class="fa fa-desktop text-success"></i> Desktops & Workstations</a>
                            <a href="category_list.php?search=Components" class="kc-dropdown-item"><i class="fa fa-microchip text-danger"></i> Components & GPUs</a>
                            <a href="category_list.php?search=Monitors" class="kc-dropdown-item"><i class="fa fa-tv text-warning"></i> Gaming Monitors</a>
                            <a href="category_list.php?search=Accessories" class="kc-dropdown-item"><i class="fa fa-keyboard text-primary"></i> Keyboards & Accessories</a>
                            <a href="category_list.php?search=Gaming" class="kc-dropdown-item"><i class="fa fa-gamepad text-purple"></i> Gaming Rigs & Peripherals</a>
                            <?php } ?>
                        </div>
                    </li>

                    <li class="uls-nav-item <?php echo $is_about ? 'active' : ''; ?>">
                        <a href="about.php">About</a>
                    </li>
                    <li class="uls-nav-item <?php echo $is_contact ? 'active' : ''; ?>">
                        <a href="contact.php">Contact</a>
                    </li>
                    <li class="uls-nav-item <?php echo (strpos($current_uri, 'track_order') !== false) ? 'active' : ''; ?>">
                        <a href="track_order.php">Track Order</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- Placeholder to prevent layout shift when header becomes fixed sticky -->
<div class="kc-header-placeholder" id="kcHeaderPlaceholder" style="display:none; width:100%;"></div>

<!-- ── 3. Offcanvas Mobile Drawer ── -->
<div class="kc-drawer-backdrop" id="kcDrawerBackdrop"></div>
<div class="kc-mobile-drawer" id="kcMobileDrawer">
    <!-- Drawer Header -->
    <div class="kc-drawer-header">
        <a href="index.php" class="uls-brand">
            <img src="img/karuda_eagle_logo.png" alt="Karuda Computers" class="uls-brand-logo-img" style="max-height:36px;" onerror="this.src='img/logo.png'">
            <div class="uls-brand-text-wrap">
                <span class="uls-brand-title-main text-white" style="font-size:16px;">KARUDA</span>
                <span class="uls-brand-title-sub" style="color:#00BCD4; font-size:9px;">COMPUTERS</span>
            </div>
        </a>
        <button type="button" class="kc-drawer-close" id="kcDrawerCloseBtn" aria-label="Close Menu">&times;</button>
    </div>

    <!-- User Account Status -->
    <div class="kc-drawer-user">
        <?php if ($is_logged_in): ?>
            <div class="kc-drawer-user-info">
                <div class="kc-drawer-avatar">
                    <i class="fa fa-user"></i>
                </div>
                <div>
                    <h5 class="kc-drawer-user-name"><?php echo htmlspecialchars($user_display_name); ?></h5>
                    <p class="kc-drawer-user-sub"><a href="userprofile.php" style="color:#0070F3; text-decoration:none;">View Profile</a></p>
                </div>
            </div>
            <a href="logout.php" class="btn btn-sm btn-outline-danger" style="font-size:11px; padding:4px 8px; font-weight:700;">Logout</a>
        <?php else: ?>
            <div class="kc-drawer-user-info">
                <div class="kc-drawer-avatar">
                    <i class="fa fa-user"></i>
                </div>
                <div>
                    <h5 class="kc-drawer-user-name">Welcome Guest</h5>
                    <p class="kc-drawer-user-sub">Sign in for best deals</p>
                </div>
            </div>
            <a href="login.php" class="kc-drawer-login-btn">Sign In</a>
        <?php endif; ?>
    </div>

    <!-- Drawer Navigation Links (Matching Desktop Navbar) -->
    <div class="kc-drawer-body">
        <a href="index.php" class="kc-drawer-nav-item <?php echo $is_home ? 'active' : ''; ?>">
            <span><i class="fa fa-house kc-nav-ico text-primary"></i> Home</span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>
        
        <a href="allproducts.php" class="kc-drawer-nav-item <?php echo ($current_page == 'allproducts.php') ? 'active' : ''; ?>">
            <span><i class="fa fa-store kc-nav-ico text-info"></i> Shop</span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>

        <!-- Expandable Categories Accordion -->
        <a href="javascript:void(0);" class="kc-drawer-nav-item <?php echo ($current_page == 'category_list.php') ? 'active' : ''; ?>" id="kcDrawerCatToggle">
            <span><i class="fa fa-layer-group kc-nav-ico text-success"></i> Categories</span>
            <i class="fa fa-chevron-down text-muted" id="kcDrawerCatArrow" style="font-size:11px; transition:transform 0.2s;"></i>
        </a>
        <div class="kc-drawer-cat-accordion" id="kcDrawerCatAccordion">
            <?php
            if (!empty($nav_cats)) {
                foreach ($nav_cats as $cname) {
                    echo '<a href="category_list.php?search=' . urlencode($cname) . '" class="kc-drawer-sub-link"><i class="fa fa-chevron-right mr-2 text-primary" style="font-size:10px;"></i> ' . htmlspecialchars($cname) . '</a>';
                }
            } else {
            ?>
            <a href="category_list.php?search=Laptops" class="kc-drawer-sub-link">
                <i class="fa fa-laptop text-info mr-2"></i> Laptops & Notebooks
            </a>
            <a href="category_list.php?search=Desktops" class="kc-drawer-sub-link">
                <i class="fa fa-desktop text-success mr-2"></i> Desktops & Workstations
            </a>
            <a href="category_list.php?search=Components" class="kc-drawer-sub-link">
                <i class="fa fa-microchip text-danger mr-2"></i> Components & GPUs
            </a>
            <a href="category_list.php?search=Monitors" class="kc-drawer-sub-link">
                <i class="fa fa-tv text-warning mr-2"></i> Gaming Monitors
            </a>
            <a href="category_list.php?search=Accessories" class="kc-drawer-sub-link">
                <i class="fa fa-keyboard text-primary mr-2"></i> Keyboards & Accessories
            </a>
            <a href="category_list.php" class="kc-drawer-sub-link font-weight-bold text-primary">
                View All Categories <i class="fa fa-arrow-right ml-1"></i>
            </a>
            <?php } ?>
        </div>

        <a href="about.php" class="kc-drawer-nav-item <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">
            <span><i class="fa fa-circle-info kc-nav-ico text-purple"></i> About Us</span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>

        <a href="contact.php" class="kc-drawer-nav-item <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">
            <span><i class="fa fa-envelope kc-nav-ico text-warning"></i> Contact Us</span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>

        <a href="order_track.php" class="kc-drawer-nav-item">
            <span><i class="fa fa-truck-fast kc-nav-ico text-primary"></i> Track Order</span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>
    </div>
</div>

<script>
(function() {
    // ── Search Overlay Toggle ──
    const searchToggleBtn = document.getElementById('kcSearchModalToggle');
    const searchOverlay = document.getElementById('kcSearchOverlayBar');
    const searchCloseBtn = document.getElementById('kcSearchModalClose');
    const searchInput = document.getElementById('kcNavOverlaySearchInput');

    if (searchToggleBtn && searchOverlay) {
        searchToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchOverlay.classList.toggle('show');
            if (searchOverlay.classList.contains('show') && searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        });
    }

    if (searchCloseBtn && searchOverlay) {
        searchCloseBtn.addEventListener('click', function() {
            searchOverlay.classList.remove('show');
        });
    }

    // ── Mobile & Desktop Drawer Handlers ──
    const mobileToggle = document.getElementById('kcMobileNavToggle');
    const desktopToggle = document.getElementById('ulsDesktopNavToggle');
    const mobileDrawer = document.getElementById('kcMobileDrawer');
    const backdrop = document.getElementById('kcDrawerBackdrop');
    const closeBtn = document.getElementById('kcDrawerCloseBtn');

    function openMobileDrawer() {
        if (mobileDrawer && backdrop) {
            mobileDrawer.classList.add('active');
            backdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMobileDrawer() {
        if (mobileDrawer && backdrop) {
            mobileDrawer.classList.remove('active');
            backdrop.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (mobileToggle) mobileToggle.addEventListener('click', openMobileDrawer);
    if (desktopToggle) desktopToggle.addEventListener('click', openMobileDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeMobileDrawer);
    if (backdrop) backdrop.addEventListener('click', closeMobileDrawer);

    // Auto-close drawer when clicking any nav item link
    if (mobileDrawer) {
        const drawerNavLinks = mobileDrawer.querySelectorAll('.kc-drawer-body a:not(#kcDrawerCatToggle)');
        drawerNavLinks.forEach(link => {
            link.addEventListener('click', closeMobileDrawer);
        });
    }

    // Categories Accordion Toggle Inside Mobile Drawer
    const catToggle = document.getElementById('kcDrawerCatToggle');
    const catAccordion = document.getElementById('kcDrawerCatAccordion');
    const catArrow = document.getElementById('kcDrawerCatArrow');
    if (catToggle && catAccordion) {
        catToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (catAccordion.style.display === 'block') {
                catAccordion.style.display = 'none';
                if (catArrow) catArrow.style.transform = 'rotate(0deg)';
            } else {
                catAccordion.style.display = 'block';
                if (catArrow) catArrow.style.transform = 'rotate(180deg)';
            }
        });
    }

    // ══════════════════════════════════════════════════════════════
    //  SMART STICKY NAVBAR
    // ══════════════════════════════════════════════════════════════
    const mainHeader = document.getElementById('kcMainHeader');
    const headerPlaceholder = document.getElementById('kcHeaderPlaceholder');

    let isSticky = false;
    let ticking = false;

    function updateStickyNav() {
        const scrollY = window.pageYOffset || document.documentElement.scrollTop;
        const triggerThreshold = 15;

        if (scrollY > triggerThreshold) {
            if (!isSticky) {
                isSticky = true;
                if (headerPlaceholder && mainHeader) {
                    headerPlaceholder.style.height = mainHeader.offsetHeight + 'px';
                    headerPlaceholder.style.display = 'block';
                }
                mainHeader.classList.add('kc-is-sticky');
            }
        } else {
            if (isSticky) {
                isSticky = false;
                mainHeader.classList.remove('kc-is-sticky');
                if (headerPlaceholder) {
                    headerPlaceholder.style.display = 'none';
                }
            }
        }

        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(updateStickyNav);
            ticking = true;
        }
    }, { passive: true });

    // Initial trigger
    updateStickyNav();

    // Input sync between hero banner search input and in-navbar search input
    function initSearchSync() {
        const heroInput = document.getElementById('kcHeroSearchInput');
        const ulsInput = document.getElementById('ulsSearchInput');
        const ulsMobileInput = document.getElementById('ulsMobileSearchInput');
        if (heroInput && ulsInput && !heroInput.dataset.syncInit) {
            heroInput.dataset.syncInit = 'true';
            heroInput.addEventListener('input', function() {
                if (ulsInput.value !== this.value) {
                    ulsInput.value = this.value;
                    ulsInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
                if (ulsMobileInput && ulsMobileInput.value !== this.value) {
                    ulsMobileInput.value = this.value;
                }
            });
            ulsInput.addEventListener('input', function() {
                if (heroInput.value !== this.value) {
                    heroInput.value = this.value;
                }
                if (ulsMobileInput && ulsMobileInput.value !== this.value) {
                    ulsMobileInput.value = this.value;
                }
            });
            if (ulsMobileInput) {
                ulsMobileInput.addEventListener('input', function() {
                    if (heroInput && heroInput.value !== this.value) heroInput.value = this.value;
                    if (ulsInput && ulsInput.value !== this.value) ulsInput.value = this.value;
                });
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initSearchSync();
            updateStickyNav();
        });
    } else {
        initSearchSync();
    }
    window.addEventListener('load', function() {
        initSearchSync();
        updateStickyNav();
    });

    // ══════════════════════════════════════════════════════════════
    //  UNIVERSAL LIVE AUTO-SUGGEST ENGINE
    // ══════════════════════════════════════════════════════════════
    var _kcApiBase = (function() {
        var loc = window.location;
        var path = loc.pathname;
        var m = path.match(/^(\/[^\/]+\/)/); // e.g. /karudacom/
        return m ? loc.protocol + '//' + loc.host + m[1] : loc.protocol + '//' + loc.host + '/';
    })();

    function resolveApiUrl(q) {
        return _kcApiBase + 'api_search.php?q=' + encodeURIComponent(q);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function highlightMatch(text, query) {
        if (!query || !text) return escapeHtml(text);
        const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const regex = new RegExp(`(${escapedQuery})`, 'gi');
        return escapeHtml(text).replace(regex, '<strong style="color:#0070F3;background:rgba(0,112,243,0.1);padding:0 2px;border-radius:3px;">$1</strong>');
    }

    function setupLiveSearch(inputSelector, dropdownSelector, listSelector, countSelector, viewAllSelector) {
        const input = document.querySelector(inputSelector);
        const dropdown = document.querySelector(dropdownSelector);
        const list = document.querySelector(listSelector);
        const count = document.querySelector(countSelector);
        const viewAll = document.querySelector(viewAllSelector);

        if (!input || !dropdown || !list) return;

        let debounceTimer = null;
        let selectedIndex = -1;

        input.addEventListener('focus', function() {
            if (this.value.trim().length >= 1 && list.children.length > 0) {
                dropdown.style.display = 'block';
            }
        });

        input.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 1) {
                dropdown.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(resolveApiUrl(query))
                    .then(res => res.json())
                    .then(data => {
                        if ((data.results && data.results.length > 0) || (data.categories && data.categories.length > 0)) {
                            if (count) count.textContent = `${data.count} items`;
                            if (viewAll) {
                                viewAll.href = `category_list.php?search=${encodeURIComponent(query)}`;
                                viewAll.innerHTML = `View All ${data.count} Results for "${escapeHtml(query)}" <i class="fa fa-arrow-right"></i>`;
                            }

                            let html = '';

                            // 1. Categories
                            if (data.categories && data.categories.length > 0) {
                                html += `<div style="background:#f8fafc;padding:6px 14px 4px;border-bottom:1px solid #e2e8f0;">
                                    <small style="font-size:10.5px;font-weight:800;color:#64748b;letter-spacing:0.5px;text-transform:uppercase;"><i class="fa fa-layer-group mr-1 text-primary"></i> Categories</small>
                                </div>
                                <div style="display:flex;flex-wrap:wrap;gap:5px;padding:6px 14px 8px;border-bottom:1px solid #e2e8f0;">`;
                                data.categories.forEach(cat => {
                                    html += `<a href="${cat.url}" style="display:inline-flex;align-items:center;gap:4px;background:#EFF6FF;color:#0070F3;border:1px solid #DBEAFE;border-radius:14px;padding:3px 10px;font-size:11.5px;font-weight:700;text-decoration:none;transition:all 0.15s ease;">
                                        <i class="fa fa-folder-open" style="font-size:10px;"></i> ${escapeHtml(cat.name)}
                                    </a>`;
                                });
                                html += `</div>`;
                            }

                            // 2. Product Results
                            if (data.results && data.results.length > 0) {
                                html += `<div style="background:#f8fafc;padding:6px 14px 4px;">
                                    <small style="font-size:10.5px;font-weight:800;color:#64748b;letter-spacing:0.5px;text-transform:uppercase;"><i class="fa fa-box mr-1 text-warning"></i> Products</small>
                                </div>`;

                                data.results.forEach((item, index) => {
                                    const priceHtml = item.old_price 
                                        ? `<span class="kc-suggest-price">₹${item.price}</span> <small class="text-muted" style="text-decoration:line-through;font-size:10.5px;">₹${item.old_price}</small>` 
                                        : `<span class="kc-suggest-price">₹${item.price}</span>`;

                                    html += `
                                        <a href="${item.url}" class="kc-suggest-item" data-index="${index}">
                                            <img src="${item.image}" alt="${escapeHtml(item.name)}" class="kc-suggest-img" onerror="this.src='img/karuda_logo.png'">
                                            <div class="kc-suggest-details">
                                                <h5 class="kc-suggest-title">${highlightMatch(item.name, query)}</h5>
                                                <p class="kc-suggest-cat"><i class="fa fa-tag text-muted"></i> ${escapeHtml(item.category || 'Hardware')}</p>
                                            </div>
                                            <div class="kc-suggest-price-box">
                                                ${priceHtml}
                                                <br>
                                                <span class="kc-suggest-badge">${item.in_stock ? 'In Stock' : 'Pre-Order'}</span>
                                            </div>
                                        </a>
                                    `;
                                });
                            }

                            list.innerHTML = html;
                            dropdown.style.display = 'block';
                            selectedIndex = -1;
                        } else {
                            list.innerHTML = `
                                <div style="padding:20px 16px;text-align:center;color:#64748b;">
                                    <i class="fa fa-search" style="font-size:20px;margin-bottom:6px;color:#94a3b8;"></i>
                                    <p style="margin:0;font-size:13px;">No products found for "<strong>${escapeHtml(query)}</strong>"</p>
                                    <small style="color:#94a3b8;font-size:11px;">Check spelling or search by brand</small>
                                </div>
                            `;
                            if (count) count.textContent = '0 items';
                            if (viewAll) {
                                viewAll.href = 'allproducts.php';
                                viewAll.innerHTML = `Browse All Products <i class="fa fa-arrow-right"></i>`;
                            }
                            dropdown.style.display = 'block';
                        }
                    })
                    .catch(err => console.error('Live Search error:', err));
            }, 200);
        });

        // Keyboard Navigation
        input.addEventListener('keydown', function(e) {
            const items = list.querySelectorAll('.kc-suggest-item');
            if (!items.length || dropdown.style.display === 'none') return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % items.length;
                updateSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                updateSelection(items);
            } else if (e.key === 'Enter') {
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    e.preventDefault();
                    window.location.href = items[selectedIndex].href;
                }
            } else if (e.key === 'Escape') {
                dropdown.style.display = 'none';
            }
        });

        function updateSelection(items) {
            items.forEach((item, idx) => {
                if (idx === selectedIndex) {
                    item.classList.add('selected');
                    item.scrollIntoView({ block: 'nearest' });
                } else {
                    item.classList.remove('selected');
                }
            });
        }

        // Re-open dropdown on focus if query already typed
        input.addEventListener('focus', function() {
            if (this.value.trim().length > 0 && list.innerHTML.trim() !== '') {
                dropdown.style.display = 'block';
            }
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }

    setupLiveSearch('#ulsSearchInput', '#ulsSuggestBox', '#ulsSuggestList', '#ulsSuggestCount', '#ulsSuggestViewAllBtn');
    setupLiveSearch('#ulsMobileSearchInput', '#ulsMobileSuggestBox', '#ulsMobileSuggestList', '#ulsMobileSuggestCount', '#ulsMobileSuggestViewAllBtn');
    setupLiveSearch('#kcDrawerNavSearchInput', '#kcDrawerNavSuggestBox', '#kcDrawerNavSuggestList', '#kcDrawerNavSuggestCount', '#kcDrawerNavSuggestViewAllBtn');

    // ── Mobile Scroll Search Toggle Handler ──
    (function() {
        const toggleBtn = document.getElementById('kcMobileSearchToggleBtn');
        const mobileWrap = document.querySelector('.uls-mobile-search-wrap');
        const mobileInput = document.getElementById('ulsMobileSearchInput');
        const heroForm = document.getElementById('kcHeroSearchForm') || document.querySelector('.kc-hero-search-section');

        function updateMobileSearchVisibility() {
            if (window.innerWidth > 991) return;
            if (heroForm) {
                const rect = heroForm.getBoundingClientRect();
                if (rect.bottom < 60) {
                    document.body.classList.add('kc-show-mobile-search-icon');
                } else {
                    document.body.classList.remove('kc-show-mobile-search-icon');
                    if (mobileWrap && mobileWrap.classList.contains('active')) {
                        mobileWrap.classList.remove('active');
                        if (toggleBtn) toggleBtn.classList.remove('active');
                    }
                }
            } else {
                if (window.scrollY > 80) {
                    document.body.classList.add('kc-show-mobile-search-icon');
                } else {
                    document.body.classList.remove('kc-show-mobile-search-icon');
                }
            }
        }

        window.addEventListener('scroll', updateMobileSearchVisibility, { passive: true });
        window.addEventListener('resize', updateMobileSearchVisibility);
        updateMobileSearchVisibility();

        if (toggleBtn && mobileWrap) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const isOpening = !mobileWrap.classList.contains('active');
                mobileWrap.classList.toggle('active', isOpening);
                toggleBtn.classList.toggle('active', isOpening);
                if (isOpening && mobileInput) {
                    setTimeout(() => mobileInput.focus(), 150);
                }
            });
        }
    })();
})();
</script>