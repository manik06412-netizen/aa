<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
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
    function addCard()
    {
        global $con;
        if (isset($_SESSION['uid'])) {
            $user_id = $_SESSION['uid'];
            $card = mysqli_query($con, "SELECT * FROM card WHERE userid='$user_id' AND status='0'");
            return $card ? mysqli_num_rows($card) : 0;
        }
        return 0;
    }
}

if (!function_exists('getWishlistCount')) {
    function getWishlistCount()
    {
        global $con;
        if (isset($_SESSION['uid'])) {
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
$is_shop = (strpos($current_uri, 'category_list') !== false || strpos($current_uri, 'allproducts') !== false || $current_page == 'category_list.php' || $current_page == 'allproducts.php');
$is_about = ($current_page == 'about.php' || strpos($current_uri, 'about') !== false);
$is_contact = ($current_page == 'contact.php' || strpos($current_uri, 'contact') !== false);
?>

<style>
    /* ══════════════════════════════════════════════════════════════
   KARUDA COMPUTERS — UNIFIED MODERN SINGLE NAVBAR
 ══════════════════════════════════════════════════════════════ */
    /* ── 1. Top Announcement Preheader ── */
    .kc-top-bar {
        background: #002566 !important;
        color: #94A3B8;
        font-size: 12.5px;
        padding: 7px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .kc-top-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kc-top-pulse-dot {
        width: 7px;
        height: 7px;
        background: #10B981;
        border-radius: 50%;
        box-shadow: 0 0 8px #10B981;
        animation: kcPulse 2s infinite;
    }

    @keyframes kcPulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.5;
            transform: scale(1.3);
        }
    }

    .kc-top-right a {
        color: #CBD5E1;
        text-decoration: none;
        margin-left: 16px;
        transition: color 0.2s;
    }

    .kc-top-right a:hover {
        color: #00BCD4;
    }

    .kc-top-divider {
        display: inline-block;
        width: 1px;
        height: 12px;
        background: rgba(255, 255, 255, 0.2);
        margin: 0 14px;
    }

    .kc-top-phone {
        color: #38BDF8;
        font-weight: 600;
    }

    /* ── 2. Unified Modern White Navbar ── */
    .kc-unified-header {
        background: #ffffff !important;
        border-bottom: 1px solid #E2E8F0 !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 1000 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
        transition: all 0.3s ease;
    }

    .kc-navbar-row {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 12px 0 !important;
        gap: 20px !important;
    }

    /* Left: Brand Logo & Text Styling */
    .kc-nav-brand,
    .uls-brand {
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        text-decoration: none !important;
        flex-shrink: 0 !important;
        white-space: nowrap !important;
    }

    .kc-nav-brand img,
    .uls-brand img,
    .uls-brand-logo-img {
        height: 48px !important;
        max-height: 48px !important;
        width: auto !important;
        object-fit: contain !important;
        margin: 0 !important;
        padding: 0 !important;
        filter: drop-shadow(0 2px 6px rgba(0, 112, 243, 0.25)) !important;
    }

    .uls-brand-text-wrap {
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        line-height: 0.9 !important;
        margin: 0 !important;
        padding: 0 !important;
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

    /* Center: Navigation Menu */
    .kc-nav-center-col {
        flex: 1;
        display: flex;
        justify-content: center;
    }

    .kc-nav-menu {
        display: flex !important;
        align-items: center !important;
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
        gap: 12px !important;
    }

    .kc-menu-item {
        position: relative !important;
    }

    .kc-menu-item>a {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        padding: 8px 14px !important;
        font-size: 15px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        text-decoration: none !important;
        position: relative !important;
        transition: all 0.2s ease !important;
        border-radius: 8px !important;
    }

    .kc-menu-item>a:hover {
        color: #0070F3 !important;
        background: #F8FAFC !important;
    }

    .kc-menu-item.active>a {
        color: #0070F3 !important;
        font-weight: 700 !important;
    }

    .kc-menu-item.active>a::after {
        content: '' !important;
        position: absolute !important;
        bottom: -2px !important;
        left: 14px !important;
        right: 14px !important;
        height: 2.5px !important;
        background: #0070F3 !important;
        border-radius: 3px !important;
    }

    .kc-arrow-ico {
        font-size: 10px !important;
        transition: transform 0.2s ease !important;
        color: #94A3B8 !important;
    }

    .kc-menu-item:hover .kc-arrow-ico {
        transform: rotate(180deg) !important;
        color: #0070F3 !important;
    }

    /* Dropdown Menu for Categories */
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

    .kc-has-dropdown:hover .kc-dropdown-menu {
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

    /* Right: Circular Action Buttons */
    .kc-actions-group {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .kc-circle-action-btn {
        width: 42px !important;
        height: 42px !important;
        border-radius: 50% !important;
        background: #ffffff !important;
        border: 1.5px solid #E2E8F0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #334155 !important;
        font-size: 16px !important;
        position: relative !important;
        cursor: pointer !important;
        text-decoration: none !important;
        transition: all 0.22s ease !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02) !important;
    }

    .kc-circle-action-btn:hover {
        border-color: #0070F3 !important;
        color: #0070F3 !important;
        background: #F0F9FF !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(0, 112, 243, 0.15) !important;
    }

    .kc-badge-pill {
        position: absolute !important;
        top: -4px !important;
        right: -4px !important;
        min-width: 18px !important;
        height: 18px !important;
        padding: 0 4px !important;
        border-radius: 20px !important;
        font-size: 10px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 2px solid #ffffff !important;
    }

    .kc-badge-red {
        background: #EF4444 !important;
    }

    .kc-badge-blue {
        background: #0070F3 !important;
    }

    /* ── Interactive Live Search Dropdown Bar ── */
    .kc-search-overlay-bar {
        display: none;
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        right: 0 !important;
        background: #ffffff !important;
        border-bottom: 1px solid #E2E8F0 !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
        padding: 14px 0 !important;
        z-index: 1020 !important;
    }

    .kc-search-overlay-bar.show {
        display: block !important;
        animation: kcSlideDown 0.22s ease-out;
    }

    .kc-search-bar-inner {
        max-width: 760px;
        margin: 0 auto;
        position: relative;
    }

    .kc-search-bar-form {
        display: flex;
        align-items: center;
        background: #F8FAFC;
        border: 1.5px solid #CBD5E1;
        border-radius: 12px;
        padding: 4px 12px;
        transition: all 0.2s ease;
    }

    .kc-search-bar-form:focus-within {
        border-color: #0070F3;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(0, 112, 243, 0.15);
    }

    .kc-search-bar-icon {
        font-size: 17px;
        color: #0070F3;
        margin-right: 10px;
    }

    .kc-search-bar-input {
        flex: 1;
        border: none !important;
        outline: none !important;
        background: transparent !important;
        font-size: 15px;
        font-weight: 500;
        color: #0F172A;
        padding: 8px 0;
    }

    .kc-search-bar-close {
        background: none;
        border: none;
        font-size: 22px;
        line-height: 1;
        color: #94A3B8;
        cursor: pointer;
        padding: 4px 8px;
        transition: color 0.2s;
    }

    .kc-search-bar-close:hover {
        color: #EF4444;
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

    .kc-mobile-drawer {
        position: fixed;
        top: 0;
        left: -340px;
        width: 310px;
        max-width: 88vw;
        height: 100vh;
        background: #ffffff;
        z-index: 999999;
        box-shadow: 12px 0 50px rgba(11, 25, 44, 0.25);
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .kc-mobile-drawer.active {
        transform: translateX(340px);
    }

    .kc-drawer-header {
        background: #ffffff;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #E2E8F0;
    }

    .kc-drawer-close {
        background: #F1F5F9;
        border: none;
        color: #475569;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .kc-drawer-close:hover {
        background: #EF4444;
        color: #ffffff;
    }

    .kc-drawer-user-card {
        background: linear-gradient(135deg, #0B192C 0%, #0D47A1 100%);
        padding: 16px 18px;
        color: #ffffff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .kc-drawer-user-flex {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .kc-drawer-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0070F3, #00BCD4);
        color: #ffffff;
        font-size: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 188, 212, 0.4);
        flex-shrink: 0;
    }

    .kc-drawer-user-name {
        font-size: 14.5px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        line-height: 1.2;
    }

    .kc-drawer-user-sub,
    .kc-drawer-user-status {
        font-size: 11.5px;
        color: #94A3B8;
        margin-top: 2px;
        display: block;
    }

    .kc-drawer-user-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .kc-drawer-btn-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none !important;
        transition: all 0.2s ease;
        flex: 1;
        text-align: center;
    }

    .kc-btn-cyan {
        background: linear-gradient(135deg, #00BCD4 0%, #0070F3 100%);
        color: #ffffff !important;
    }

    .kc-btn-sky {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .kc-btn-red {
        background: rgba(239, 68, 68, 0.2);
        color: #FCA5A5 !important;
        border: 1px solid rgba(239, 68, 68, 0.4);
    }

    .kc-drawer-search-box {
        padding: 12px 18px 8px;
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        position: relative;
    }

    .kc-drawer-search-form {
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1.5px solid #CBD5E1;
        border-radius: 25px;
        padding: 6px 14px;
        transition: border-color 0.2s;
    }

    .kc-drawer-search-form:focus-within {
        border-color: #0070F3;
        box-shadow: 0 0 0 3px rgba(0, 112, 243, 0.15);
    }

    .kc-drawer-search-icon {
        color: #94A3B8;
        font-size: 13px;
        margin-right: 8px;
    }

    .kc-drawer-search-input {
        border: none !important;
        outline: none !important;
        width: 100%;
        font-size: 13px;
        color: #0F172A;
        background: transparent !important;
    }

    .kc-drawer-suggest-dropdown {
        top: calc(100% + 4px) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .kc-drawer-body {
        flex: 1;
        overflow-y: auto;
        padding: 10px 12px;
    }

    .kc-drawer-nav-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 14px;
        color: #1E293B;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.2s ease;
        border-radius: 10px;
        margin-bottom: 3px;
    }

    .kc-drawer-nav-item i.kc-nav-ico {
        width: 24px;
        font-size: 15px;
        color: #64748B;
        margin-right: 8px;
        text-align: center;
    }

    .kc-drawer-nav-item:hover,
    .kc-drawer-nav-item.active {
        background: #EFF6FF;
        color: #0070F3;
    }

    .kc-drawer-nav-item:hover i.kc-nav-ico,
    .kc-drawer-nav-item.active i.kc-nav-ico {
        color: #0070F3;
    }

    .kc-drawer-badge {
        background: #0070F3;
        color: #fff;
        font-size: 10.5px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 12px;
    }

    .kc-drawer-badge-red {
        background: #EF4444;
    }

    .kc-drawer-cat-accordion {
        display: none;
        background: #F8FAFC;
        padding: 6px 8px;
        border-radius: 10px;
        margin: 4px 0 8px 0;
        border: 1px solid #E2E8F0;
    }

    .kc-drawer-cat-accordion.show {
        display: block;
    }

    .kc-drawer-sub-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .kc-drawer-sub-link:hover {
        color: #0070F3;
        background: #FFFFFF;
    }

    .kc-drawer-footer {
        padding: 14px 18px;
        background: #0B192C;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .kc-drawer-contact-strip {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #ffffff;
        font-size: 13px;
        text-decoration: none !important;
    }

    .kc-drawer-contact-strip i {
        color: #00BCD4;
        font-size: 18px;
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

        .kc-menu-item>a {
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

        #kcSearchModalToggle {
            display: none !important;
        }

        .kc-show-mobile-search-icon #kcSearchModalToggle {
            display: inline-flex !important;
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
<div class="kc-top-bar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="kc-top-left">
                <span class="kc-top-pulse-dot"></span>
                <span>Welcome to <strong>Karuda Computers</strong> — Computers & Tech Accessories</span>
            </div>
            <div class="kc-top-right d-none d-md-flex align-items-center">
                <a href="order_track.php"><i class="fa fa-truck-fast mr-1"></i> Track Order</a>
                <a href="contact.php"><i class="fa fa-headset mr-1"></i> Support</a>
                <a href="faq.php"><i class="fa fa-circle-question mr-1"></i> FAQ</a>
                <span class="kc-top-divider"></span>
                <span class="kc-top-phone"><i class="fa fa-phone mr-1"></i> Helpline: <strong>+91 98765
                        43210</strong></span>
            </div>
        </div>
    </div>
</div>

<!-- ── 2. Unified Modern Navbar ── -->
<header class="kc-unified-header" id="kcMainHeader">
    <div class="container">
        <div class="kc-navbar-row">

            <!-- Left: Brand Logo -->
            <div class="kc-nav-brand-col">
                <a href="index.php" class="kc-nav-brand">
                    <img src="img/karuda_eagle_logo.png" alt="Karuda Computers" onerror="this.src='img/logo.png'">
                    <div class="uls-brand-text-wrap">
                        <span class="uls-brand-title-main">KARUDA</span>
                        <span class="uls-brand-title-sub">COMPUTERS</span>
                    </div>
                </a>
            </div>

            <!-- Center: Navigation Menu Links (Clean: Home, Shop, Categories, Contact) -->
            <nav class="kc-nav-center-col d-none d-lg-block">
                <ul class="kc-nav-menu">
                    <li class="kc-menu-item <?php echo $is_home ? 'active' : ''; ?>">
                        <a href="index.php">Home</a>
                    </li>
                    <li
                        class="kc-menu-item <?php echo ($current_page == 'allproducts.php' || $is_shop && $current_page != 'category_list.php') ? 'active' : ''; ?>">
                        <a href="allproducts.php">Shop</a>
                    </li>
                    <li
                        class="kc-menu-item kc-has-dropdown <?php echo ($current_page == 'category_list.php') ? 'active' : ''; ?>">
                        <a href="category_list.php" class="kc-dropdown-toggle">
                            Categories <i class="fa fa-chevron-down kc-arrow-ico"></i>
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
                                <a href="category_list.php?search=Laptops" class="kc-dropdown-item"><i
                                        class="fa fa-laptop text-info"></i> Laptops & Notebooks</a>
                                <a href="category_list.php?search=Desktops" class="kc-dropdown-item"><i
                                        class="fa fa-desktop text-success"></i> Desktops & Workstations</a>
                                <a href="category_list.php?search=Components" class="kc-dropdown-item"><i
                                        class="fa fa-microchip text-danger"></i> Components & GPUs</a>
                                <a href="category_list.php?search=Monitors" class="kc-dropdown-item"><i
                                        class="fa fa-tv text-warning"></i> Gaming Monitors</a>
                                <a href="category_list.php?search=Accessories" class="kc-dropdown-item"><i
                                        class="fa fa-keyboard text-primary"></i> Keyboards & Accessories</a>
                                <a href="category_list.php?search=Gaming" class="kc-dropdown-item"><i
                                        class="fa fa-gamepad text-purple"></i> Gaming Rigs & Peripherals</a>
                            <?php } ?>
                        </div>
                    </li>
                    <li class="kc-menu-item <?php echo $is_contact ? 'active' : ''; ?>">
                        <a href="contact.php">Contact</a>
                    </li>
                </ul>
            </nav>

            <!-- Right: Circular Action Icons (Search, User, Wishlist, Cart) -->
            <div class="kc-nav-actions-col">
                <div class="kc-actions-group">
                    <!-- Search Icon Trigger (Desktop only — hero section has search on mobile) -->
                    <button type="button" class="kc-circle-action-btn d-none d-lg-flex" id="kcSearchModalToggle"
                        title="Search Store">
                        <i class="fa fa-search"></i>
                    </button>

                    <!-- User Profile / Login -->
                    <a href="<?php echo $is_logged_in ? 'userprofile.php' : 'login.php'; ?>"
                        class="kc-circle-action-btn" title="My Account">
                        <i class="fa-regular fa-user"></i>
                    </a>

                    <!-- Wishlist -->
                    <a href="wishlist.php" class="kc-circle-action-btn" title="Wishlist">
                        <i class="fa-regular fa-heart"></i>
                        <span class="kc-badge-pill kc-badge-red"
                            id="wishlistCountBadge"><?php echo $wish_count; ?></span>
                    </a>

                    <!-- Shopping Cart -->
                    <a href="cart.php" class="kc-circle-action-btn" title="Shopping Cart">
                        <i class="fa fa-cart-shopping"></i>
                        <span class="kc-badge-pill kc-badge-blue" id="cartCountBadge"><?php echo $cart_count; ?></span>
                    </a>

                    <!-- Mobile Menu Hamburger Button (Hidden on Desktop) -->
                    <button type="button" class="kc-circle-action-btn kc-mobile-toggle-btn" id="kcMobileNavToggle"
                        aria-label="Open Mobile Menu">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ── Floating / Expandable Quick Search Bar ── -->
    <div class="kc-search-overlay-bar" id="kcSearchOverlayBar">
        <div class="container">
            <div class="kc-search-bar-inner">
                <form action="category_list.php" method="GET" class="kc-search-bar-form" id="kcNavOverlaySearchForm">
                    <i class="fa fa-search kc-search-bar-icon"></i>
                    <input type="text" name="search" id="kcNavOverlaySearchInput" class="kc-search-bar-input"
                        placeholder="Search laptops, desktops, components, processors, accessories..."
                        autocomplete="off">
                    <button type="button" class="kc-search-bar-close" id="kcSearchModalClose"
                        aria-label="Close Search">&times;</button>
                </form>
                <!-- Live Auto-Suggest Floating Dropdown -->
                <div class="kc-suggest-dropdown" id="kcNavOverlaySuggestBox">
                    <div class="kc-suggest-header">
                        <span><i class="fa fa-bolt text-warning"></i> Matching Products</span>
                        <span id="kcNavOverlaySuggestCount" class="text-muted">0 items</span>
                    </div>
                    <div class="kc-suggest-list" id="kcNavOverlaySuggestList"></div>
                    <div class="kc-suggest-footer" id="kcNavOverlaySuggestFooter">
                        <a href="allproducts.php" class="kc-suggest-view-all" id="kcNavOverlaySuggestViewAllBtn">
                            View All Products <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ── 3. Offcanvas Mobile Drawer ── -->
<div class="kc-drawer-backdrop" id="kcDrawerBackdrop"></div>
<div class="kc-mobile-drawer" id="kcMobileDrawer">
    <!-- Drawer Header -->
    <div class="kc-drawer-header">
        <a href="index.php" class="kc-nav-brand uls-brand" style="text-decoration:none;">
            <img src="img/karuda_eagle_logo.png" alt="Karuda Computers" class="uls-brand-logo-img"
                style="height:36px !important;" onerror="this.src='img/logo.png'">
            <div class="uls-brand-text-wrap" style="margin-left:6px;">
                <span class="uls-brand-title-main"
                    style="font-size:18px !important; color:#000000 !important;">KARUDA</span>
                <span class="uls-brand-title-sub"
                    style="font-size:9.5px !important; color:#0070F3 !important; letter-spacing:3.8px !important;">COMPUTERS</span>
            </div>
        </a>
        <button type="button" class="kc-drawer-close" id="kcDrawerCloseBtn" aria-label="Close Menu">&times;</button>
    </div>

    <!-- User Account Banner Card -->
    <div class="kc-drawer-user-card">
        <?php if ($is_logged_in): ?>
            <div class="kc-drawer-user-flex">
                <div class="kc-drawer-avatar">
                    <i class="fa fa-user-check"></i>
                </div>
                <div>
                    <h5 class="kc-drawer-user-name"><?php echo htmlspecialchars($user_display_name); ?></h5>
                    <span class="kc-drawer-user-status"><i class="fa fa-circle-check text-success"></i> Account
                        Active</span>
                </div>
            </div>
            <div class="kc-drawer-user-actions">
                <a href="userprofile.php" class="kc-drawer-btn-pill kc-btn-sky"><i class="fa fa-user-gear mr-1"></i>
                    Profile</a>
                <a href="logout.php" class="kc-drawer-btn-pill kc-btn-red"><i class="fa fa-right-from-bracket mr-1"></i>
                    Logout</a>
            </div>
        <?php else: ?>
            <div class="kc-drawer-user-flex">
                <div class="kc-drawer-avatar">
                    <i class="fa fa-user"></i>
                </div>
                <div>
                    <h5 class="kc-drawer-user-name">Welcome Guest</h5>
                    <span class="kc-drawer-user-sub">Sign in for exclusive member prices</span>
                </div>
            </div>
            <div class="kc-drawer-user-actions">
                <a href="login.php" class="kc-drawer-btn-pill kc-btn-cyan"><i class="fa fa-arrow-right-to-bracket mr-1"></i>
                    Sign In / Register</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Drawer Quick Search Input -->
    <div class="kc-drawer-search-box">
        <form action="category_list.php" method="GET" class="kc-drawer-search-form" id="kcDrawerNavSearchForm">
            <i class="fa fa-search kc-drawer-search-icon"></i>
            <input type="text" name="search" id="kcDrawerNavSearchInput" class="kc-drawer-search-input"
                placeholder="Search products, brands, parts..." autocomplete="off">
        </form>
        <div class="kc-suggest-dropdown kc-drawer-suggest-dropdown" id="kcDrawerNavSuggestBox">
            <div class="kc-suggest-list" id="kcDrawerNavSuggestList"></div>
            <div class="kc-suggest-footer">
                <a href="allproducts.php" class="kc-suggest-view-all" id="kcDrawerNavSuggestViewAllBtn">
                    View All Products <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Drawer Navigation Links -->
    <div class="kc-drawer-body">
        <a href="index.php" class="kc-drawer-nav-item <?php echo $is_home ? 'active' : ''; ?>">
            <span><i class="fa fa-house kc-nav-ico text-primary"></i> Home</span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>

        <a href="allproducts.php"
            class="kc-drawer-nav-item <?php echo ($current_page == 'allproducts.php') ? 'active' : ''; ?>">
            <span><i class="fa fa-store kc-nav-ico text-info"></i> Shop Products</span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>

        <!-- Expandable Categories Accordion -->
        <a href="javascript:void(0);"
            class="kc-drawer-nav-item <?php echo ($current_page == 'category_list.php') ? 'active' : ''; ?>"
            id="kcDrawerCatToggle">
            <span><i class="fa fa-layer-group kc-nav-ico text-success"></i> Categories</span>
            <i class="fa fa-chevron-down text-muted" id="kcDrawerCatArrow"
                style="font-size:11px; transition:transform 0.2s;"></i>
        </a>
        <div class="kc-drawer-cat-accordion" id="kcDrawerCatAccordion">
            <?php
            if (!empty($nav_cats)) {
                foreach ($nav_cats as $cname) {
                    echo '<a href="category_list.php?search=' . urlencode($cname) . '" class="kc-drawer-sub-link"><i class="fa fa-folder-open text-primary" style="font-size:11px;"></i> ' . htmlspecialchars($cname) . '</a>';
                }
            } else {
                ?>
                <a href="category_list.php?search=Laptops" class="kc-drawer-sub-link">
                    <i class="fa fa-laptop text-info"></i> Laptops & Notebooks
                </a>
                <a href="category_list.php?search=Desktops" class="kc-drawer-sub-link">
                    <i class="fa fa-desktop text-success"></i> Desktops & Workstations
                </a>
                <a href="category_list.php?search=Components" class="kc-drawer-sub-link">
                    <i class="fa fa-microchip text-danger"></i> Components & GPUs
                </a>
                <a href="category_list.php?search=Monitors" class="kc-drawer-sub-link">
                    <i class="fa fa-tv text-warning"></i> Gaming Monitors
                </a>
                <a href="category_list.php?search=Accessories" class="kc-drawer-sub-link">
                    <i class="fa fa-keyboard text-primary"></i> Keyboards & Accessories
                </a>
                <a href="category_list.php" class="kc-drawer-sub-link text-primary font-weight-bold">
                    View All Categories <i class="fa fa-arrow-right ml-1"></i>
                </a>
            <?php } ?>
        </div>

        <a href="wishlist.php" class="kc-drawer-nav-item">
            <span><i class="fa-regular fa-heart kc-nav-ico text-danger"></i> My Wishlist</span>
            <span class="kc-drawer-badge kc-drawer-badge-red"><?php echo $wish_count; ?></span>
        </a>

        <a href="cart.php" class="kc-drawer-nav-item">
            <span><i class="fa fa-cart-shopping kc-nav-ico text-primary"></i> Shopping Cart</span>
            <span class="kc-drawer-badge"><?php echo $cart_count; ?></span>
        </a>

        <a href="order_track.php" class="kc-drawer-nav-item">
            <span><i class="fa fa-truck-fast kc-nav-ico text-warning"></i> Track Order</span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>

        <a href="about.php" class="kc-drawer-nav-item <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">
            <span><i class="fa fa-circle-info kc-nav-ico text-purple"></i> About </span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>

        <a href="contact.php"
            class="kc-drawer-nav-item <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">
            <span><i class="fa fa-headset kc-nav-ico text-success"></i> Contact </span>
            <i class="fa fa-chevron-right text-muted" style="font-size:11px;"></i>
        </a>
    </div>

    <!-- Drawer Footer Strip -->
    <div class="kc-drawer-footer">
        <a href="tel:+919876543210" class="kc-drawer-contact-strip">
            <i class="fa fa-headset"></i>
            <div>
                <small style="color:#94A3B8; font-size:10px; display:block; text-transform:uppercase;">Need Assistance?
                    Call</small>
                <strong>+91 98765 43210</strong>
            </div>
        </a>
    </div>
</div>

<script>
    (function () {
        // ── Search Overlay Toggle ──
        const searchToggleBtn = document.getElementById('kcSearchModalToggle');
        const searchOverlay = document.getElementById('kcSearchOverlayBar');
        const searchCloseBtn = document.getElementById('kcSearchModalClose');
        const searchInput = document.getElementById('kcNavOverlaySearchInput');

        if (searchToggleBtn && searchOverlay) {
            searchToggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                searchOverlay.classList.toggle('show');
                if (searchOverlay.classList.contains('show') && searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });
        }

        if (searchCloseBtn && searchOverlay) {
            searchCloseBtn.addEventListener('click', function () {
                searchOverlay.classList.remove('show');
            });
        }

        // ── Mobile Drawer Handlers ──
        const mobileToggle = document.getElementById('kcMobileNavToggle');
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
        if (closeBtn) closeBtn.addEventListener('click', closeMobileDrawer);
        if (backdrop) backdrop.addEventListener('click', closeMobileDrawer);

        // Auto-close drawer when clicking any nav item link
        if (mobileDrawer) {
            const drawerNavLinks = mobileDrawer.querySelectorAll('.kc-drawer-body a:not(#kcDrawerCatToggle)');
            drawerNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileDrawer);
            });
        }

        // ── Drawer Category Accordion Toggle ──
        const drawerCatToggle = document.getElementById('kcDrawerCatToggle');
        const drawerCatAcc = document.getElementById('kcDrawerCatAccordion');
        const drawerCatArrow = document.getElementById('kcDrawerCatArrow');
        if (drawerCatToggle && drawerCatAcc) {
            drawerCatToggle.addEventListener('click', function (e) {
                e.preventDefault();
                drawerCatAcc.classList.toggle('show');
                if (drawerCatArrow) {
                    drawerCatArrow.style.transform = drawerCatAcc.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            });
        }

        // ══════════════════════════════════════════════════════════════
        //  UNIVERSAL LIVE AUTO-SUGGEST ENGINE
        // ══════════════════════════════════════════════════════════════
        function escapeHtml(text) {
            if (!text) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, function (m) { return map[m]; });
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

            input.addEventListener('focus', function () {
                if (this.value.trim().length >= 1 && list.children.length > 0) {
                    dropdown.style.display = 'block';
                }
            });

            input.addEventListener('input', function () {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 1) {
                    dropdown.style.display = 'none';
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`api_search.php?q=${encodeURIComponent(query)}`)
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
                                    <small style="font-size:10px;font-weight:800;color:#64748b;letter-spacing:0.5px;text-transform:uppercase;"><i class="fa fa-layer-group mr-1 text-primary"></i> Categories</small>
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
                                    <small style="font-size:10px;font-weight:800;color:#64748b;letter-spacing:0.5px;text-transform:uppercase;"><i class="fa fa-box mr-1 text-warning"></i> Products</small>
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
            input.addEventListener('keydown', function (e) {
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

            // Close on click outside
            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        }

        setupLiveSearch('#kcNavOverlaySearchInput', '#kcNavOverlaySuggestBox', '#kcNavOverlaySuggestList', '#kcNavOverlaySuggestCount', '#kcNavOverlaySuggestViewAllBtn');
        setupLiveSearch('#kcDrawerNavSearchInput', '#kcDrawerNavSuggestBox', '#kcDrawerNavSuggestList', '#kcDrawerNavSuggestCount', '#kcDrawerNavSuggestViewAllBtn');

        // ── Mobile Scroll Search Toggle Handler ──
        (function () {
            const heroForm = document.getElementById('kcHeroSearchForm') || document.querySelector('.kc-hero-search-section');

            function updateMobileSearchVisibility() {
                if (window.innerWidth > 991) return;
                if (heroForm) {
                    const rect = heroForm.getBoundingClientRect();
                    if (rect.bottom < 60) {
                        document.body.classList.add('kc-show-mobile-search-icon');
                    } else {
                        document.body.classList.remove('kc-show-mobile-search-icon');
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
        })();
    })();
</script>