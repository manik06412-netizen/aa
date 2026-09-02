<?php
if (session_status() === PHP_SESSION_NONE) session_start();
error_reporting(0);
require ('include/header.php');
include ('dbconnect.php');
?>
<style>
/* ── All Products Cyber Tech Theme ── */
#page {
    background-color: #F8FAFC !important;
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

/* Subheader Banner */
.kc-sub-header {
    background: linear-gradient(135deg, #0B192C 0%, #1A365D 100%) !important;
    padding: 24px 0 !important;
    border-bottom: 3px solid #00BCD4 !important;
    color: #ffffff !important;
}
.kc-sub-title {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 800 !important;
    font-size: 24px !important;
    margin: 0 !important;
    letter-spacing: 0.5px !important;
    text-transform: uppercase !important;
    color: #ffffff !important;
}
.kc-sub-desc {
    color: #00BCD4 !important;
    font-size: 13px !important;
    margin: 4px 0 0 0 !important;
    font-weight: 600 !important;
}

/* Sidebar Filters */
#filters_col {
    background: #ffffff !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 14px !important;
    padding: 20px !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important;
    margin-bottom: 25px !important;
}
#filters_col_bt {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 800 !important;
    font-size: 16px !important;
    color: #0B192C !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    text-decoration: none !important;
    padding-bottom: 12px !important;
    border-bottom: 2px solid #F1F5F9 !important;
    margin-bottom: 15px !important;
}
.filter_type {
    margin-bottom: 20px !important;
    padding-bottom: 15px !important;
    border-bottom: 1px solid #F1F5F9 !important;
}
.filter_type:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
.filter_type h6 {
    font-size: 13px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    color: #0F172A !important;
    margin-bottom: 12px !important;
}
.filter_type ul {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}
.filter_type ul li {
    margin-bottom: 8px !important;
}
.container_check {
    font-size: 13px !important;
    color: #475569 !important;
    font-weight: 500 !important;
    cursor: pointer !important;
}
.container_check:hover {
    color: #0070F3 !important;
}
.container_check input:checked ~ .checkmark {
    background-color: #0070F3 !important;
    border-color: #0070F3 !important;
}

/* Product Cards Grid */
.kc-prod-grid-item {
    margin-bottom: 24px !important;
}
.kc-product-card {
    background: #ffffff !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 14px !important;
    overflow: hidden !important;
    height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    position: relative !important;
}
.kc-product-card:hover {
    transform: translateY(-5px) !important;
    border-color: #0070F3 !important;
    box-shadow: 0 14px 30px rgba(0, 112, 243, 0.14) !important;
}
.kc-prod-img-wrap {
    position: relative !important;
    background: #F8FAFC !important;
    padding: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: 200px !important;
    overflow: hidden !important;
}
.kc-prod-img-wrap img {
    max-height: 100% !important;
    max-width: 100% !important;
    object-fit: contain !important;
    transition: transform 0.4s ease !important;
}
.kc-product-card:hover .kc-prod-img-wrap img {
    transform: scale(1.06) !important;
}
.kc-discount-badge {
    position: absolute !important;
    top: 10px !important;
    left: 10px !important;
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    color: #ffffff !important;
    font-size: 10.5px !important;
    font-weight: 800 !important;
    padding: 3px 8px !important;
    border-radius: 6px !important;
    z-index: 2 !important;
    letter-spacing: 0.3px !important;
    box-shadow: 0 2px 8px rgba(0, 188, 212, 0.3) !important;
}
.kc-prod-wish-btn {
    position: absolute !important;
    top: 10px !important;
    right: 10px !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    background: #ffffff !important;
    border: 1px solid #E2E8F0 !important;
    color: #94A3B8 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 2 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
}
.kc-prod-wish-btn:hover, .kc-prod-wish-btn.active {
    background: #FFF1F2 !important;
    border-color: #FECDD3 !important;
    color: #E11D48 !important;
    transform: scale(1.1) !important;
}

/* Card Body */
.kc-prod-body {
    padding: 16px !important;
    display: flex !important;
    flex-direction: column !important;
    flex-grow: 1 !important;
}
.kc-prod-cat {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    color: #00BCD4 !important;
    margin-bottom: 4px !important;
    letter-spacing: 0.5px !important;
}
.kc-prod-title {
    font-size: 14px !important;
    font-weight: 700 !important;
    line-height: 1.4 !important;
    color: #0F172A !important;
    margin: 0 0 8px 0 !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
    min-height: 38px !important;
    transition: color 0.2s ease !important;
}
.kc-prod-title a {
    color: inherit !important;
    text-decoration: none !important;
}
.kc-prod-title a:hover {
    color: #0070F3 !important;
}

/* Ratings */
.kc-prod-stars {
    color: #F59E0B !important;
    font-size: 12px !important;
    margin-bottom: 10px !important;
    display: flex !important;
    align-items: center !important;
    gap: 2px !important;
}
.kc-prod-stars span {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #94A3B8 !important;
    margin-left: 4px !important;
}

/* Price Box */
.kc-prod-price-box {
    margin-top: auto !important;
    display: flex !important;
    align-items: baseline !important;
    gap: 8px !important;
    margin-bottom: 12px !important;
}
.kc-curr-price {
    font-size: 19px !important;
    font-weight: 800 !important;
    color: #0D47A1 !important;
    font-family: 'Outfit', sans-serif !important;
}
.kc-old-price {
    font-size: 13px !important;
    color: #94A3B8 !important;
    text-decoration: line-through !important;
}

/* CTA Button */
.kc-prod-btn {
    background: linear-gradient(135deg, #0D47A1, #0070F3) !important;
    color: #ffffff !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    padding: 9px 0 !important;
    text-align: center !important;
    display: block !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    border: none !important;
    width: 100% !important;
}
.kc-prod-btn:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(0, 112, 243, 0.35) !important;
    transform: translateY(-1px) !important;
    text-decoration: none !important;
}

/* Nice Select override */
.custom-select-form .nice-select {
    border-radius: 8px !important;
    border: 1.5px solid #E2E8F0 !important;
    height: 38px !important;
    line-height: 36px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #0F172A !important;
}

@media (max-width: 767px) {
    .kc-prod-grid-item {
        flex: 0 0 50% !important;
        max-width: 50% !important;
        width: 50% !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-bottom: 12px !important;
    }
    .kc-product-card {
        border-radius: 12px !important;
    }
    .kc-prod-img-wrap {
        height: 130px !important;
        padding: 10px 6px !important;
    }
    .kc-prod-body {
        padding: 10px 8px !important;
    }
    .kc-prod-cat {
        font-size: 9px !important;
        margin-bottom: 2px !important;
    }
    .kc-prod-title {
        font-size: 12px !important;
        min-height: 32px !important;
        margin-bottom: 4px !important;
        line-height: 1.3 !important;
    }
    .kc-prod-stars {
        font-size: 9.5px !important;
        margin-bottom: 6px !important;
        gap: 1px !important;
    }
    .kc-prod-price-box {
        margin-bottom: 8px !important;
        gap: 4px !important;
        flex-wrap: wrap !important;
    }
    .kc-curr-price {
        font-size: 14px !important;
    }
    .kc-old-price {
        font-size: 10.5px !important;
    }
    .kc-prod-btn {
        font-size: 11px !important;
        padding: 6px 4px !important;
        border-radius: 6px !important;
    }
    .kc-discount-badge {
        font-size: 9px !important;
        padding: 2px 5px !important;
        top: 6px !important;
        left: 6px !important;
    }
    .kc-prod-wish-btn {
        width: 26px !important;
        height: 26px !important;
        font-size: 11px !important;
        top: 6px !important;
        right: 6px !important;
    }
}
@media (max-width: 420px) {
    .kc-prod-grid-item {
        padding-left: 3px !important;
        padding-right: 3px !important;
    }
    .kc-prod-img-wrap {
        height: 115px !important;
    }
    .kc-prod-title {
        font-size: 11px !important;
        min-height: 28px !important;
    }
    .kc-curr-price {
        font-size: 12.5px !important;
    }
}
</style>

<?php
$_SESSION['selectedCurrency'] = '₹';

$resolveProductImg = function($img) {
    if (empty($img)) return 'img/karuda_logo.png';
    $clean = preg_replace('#/+#', '/', ltrim($img, './'));
    if (file_exists($clean)) return $clean;
    if (file_exists('avadmin/' . $clean)) return 'avadmin/' . $clean;
    if (file_exists('admin/' . $clean)) return 'admin/' . $clean;
    return $clean;
};

function Max_Min($con, $CONDITION) {
    $price = mysqli_query($con, "SELECT $CONDITION(oprice) as price_od FROM price");
    if ($price && $row = mysqli_fetch_array($price)) {
        return $row['price_od'];
    }
    return 100000;
}

$fetcate = mysqli_query($con, "SELECT DISTINCT d.* FROM dishes d JOIN price p ON d.rs_id = p.pcode WHERE d.status='1'");
if (!$fetcate || mysqli_num_rows($fetcate) == 0) {
    $fetcate = mysqli_query($con, "SELECT * FROM dishes WHERE status='1'");
}
$num_of = $fetcate ? mysqli_num_rows($fetcate) : 0;
?>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <!-- Subheader Banner (Unified Style Matching Contact.php) -->
        <div class="sub_header_in">
            <div class="container text-center">
                <h1>All Products</h1>
                <p>Explore our full catalog of high-performance laptops, custom gaming rigs, and components</p>
            </div>
        </div>

        <!-- Main Product Listing Content -->
        <div class="container-fluid px-lg-5 px-3 py-4">
            <div class="row">
                
                <!-- Sidebar Filters -->
                <aside class="col-lg-3 col-md-4 col-12" id="sidebar">
                    <div id="filters_col">
                        <a data-toggle="collapse" href="#collapseFilters" aria-expanded="true" aria-controls="collapseFilters" id="filters_col_bt">
                            <span><i class="fa fa-filter text-primary mr-2"></i> Filters</span>
                            <i class="fa fa-chevron-down" style="font-size:12px;"></i>
                        </a>
                        <div class="collapse show" id="collapseFilters">
                            
                            <!-- Category Filter -->
                            <div class="filter_type">
                                <h6>Category</h6>
                                <ul>
                                    <?php 
                                    $sql = "SELECT * FROM `res_category` ORDER BY `c_name` ASC";
                                    $result1 = $con->query($sql);
                                    if ($result1 && $result1->num_rows > 0) {
                                        while ($row1 = $result1->fetch_assoc()) { ?>
                                    <li>
                                        <label class="container_check"><?= htmlspecialchars($row1['c_name']); ?>
                                            <?php $isChecked = (isset($selectedCat) && $selectedCat == $row1['c_id']) ? 'checked' : ''; ?>
                                            <input onclick="Category()" name="category" value="<?= htmlspecialchars($row1['c_id']); ?>" type="checkbox" <?= $isChecked; ?>>
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    <?php } } ?>
                                </ul>
                            </div>

                            <!-- Price Range Filter -->
                            <div class="filter_type">
                                <h6>Price Range (₹)</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" oninput="Category()" id="starting_price" class="form-control form-control-sm" placeholder="Min" value="1" min="0">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" oninput="Category()" id="ending_price" class="form-control form-control-sm" placeholder="Max" value="<?= Max_Min($con, 'MAX'); ?>" min="0" max="<?= Max_Min($con, 'MAX'); ?>">
                                    </div>
                                    <input type="hidden" id="else_price_range" value="<?= Max_Min($con, 'MAX'); ?>">
                                </div>
                            </div>

                            <!-- Star Rating Filter -->
                            <div class="filter_type">
                                <h6>Customer Rating</h6>
                                <ul>
                                    <?php for ($r = 5; $r >= 1; $r--) { ?>
                                    <li>
                                        <label class="container_check">
                                            <?php for ($st = 1; $st <= 5; $st++) { ?>
                                                <i class="fa fa-star" style="color:<?= ($st <= $r) ? '#F59E0B' : '#E2E8F0'; ?>; font-size:12px;"></i>
                                            <?php } ?>
                                            <span style="font-size:12px; color:#64748B; margin-left:4px;">(<?= $r; ?>★ & up)</span>
                                            <input type="checkbox" onclick="Category()" name="starRatings" value="<?= $r; ?>">
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>

                        </div>
                    </div>
                </aside>

                <!-- Product Cards Grid -->
                <div class="col-lg-9 col-md-8 col-12">
                    
                    <!-- Top Sort & Count Bar -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-2 border-bottom">
                        <div>
                            <h5 style="color:#0D47A1; font-weight:800; font-size:16px; margin:0;">
                                <i class="fa fa-cubes text-info mr-1"></i> <span id="total_of"><?= $num_of; ?></span> Products Available
                            </h5>
                        </div>
                        <div class="custom-select-form" style="min-width: 180px;">
                            <select class="wide" onchange="Category()" name="country" id="short_by">
                                <option value="" disabled selected>Sort by</option>
                                <option value="1">Price: Low to High</option>
                                <option value="2">Price: High to Low</option>
                                <option value="3">Rating: High to Low</option>
                                <option value="4">Popular: Best Selling</option>
                                <option value="5">Newest Arrivals</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Output Grid -->
                    <div class="row" id="response_for_the_table">
                        <?php
                        if ($fetcate && mysqli_num_rows($fetcate) > 0) {
                            while ($rocat = mysqli_fetch_array($fetcate)) {
                                $ct_img = $rocat['img'];
                                $ct_name = $rocat['dish_name'];
                                $ct_nid = $rocat['rs_id'];
                                $ct_category = $rocat['category'];
                                $ct_catid = $rocat['cateid'];
                                $defaultRating = !empty($rocat['ratings']) ? (int)$rocat['ratings'] : 5;

                                $cat_name = !empty($ct_category) ? $ct_category : 'Hardware';
                                if (!empty($ct_catid)) {
                                    $ctt = mysqli_query($con, "SELECT c_name FROM res_category WHERE c_id='$ct_catid'");
                                    if ($ctt && $crow = mysqli_fetch_assoc($ctt)) {
                                        $cat_name = $crow['c_name'];
                                    }
                                }

                                $gbb = mysqli_query($con, "SELECT * FROM price WHERE pcode='$ct_nid' ORDER BY pp ASC LIMIT 1");
                                if ($gbb && $gb13 = mysqli_fetch_array($gbb)) {
                                    $currentp = $gb13['pp'];
                                    $oldp = $gb13['oprice'];
                                    $discount_per = !empty($gb13['discount']) ? $gb13['discount'] : (($oldp > $currentp && $oldp > 0) ? round((($oldp - $currentp) / $oldp) * 100) : 0);
                                } else {
                                    $currentp = 0;
                                    $oldp = 0;
                                    $discount_per = 0;
                                }

                                $img_src = $resolveProductImg($ct_img);

                                $syl = 0;
                                if (!empty($_SESSION['uid'])) {
                                    $ud1 = $_SESSION['uid'];
                                    $zqswl = mysqli_query($con, "SELECT * FROM watch_list WHERE userid='$ud1' AND pr_id='$ct_nid'");
                                    if ($zqswl && mysqli_num_rows($zqswl) > 0) $syl = 1;
                                }
                        ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-6 kc-prod-grid-item">
                            <div class="kc-product-card">
                                
                                <!-- Image & Badges -->
                                <div class="kc-prod-img-wrap">
                                    <?php if ($discount_per > 0) { ?>
                                    <span class="kc-discount-badge"><?= $discount_per; ?>% OFF</span>
                                    <?php } ?>
                                    
                                    <button type="button" class="kc-prod-wish-btn <?= ($syl == 1) ? 'active' : ''; ?>" 
                                            onclick="toggle_Wishlist(<?= $ct_nid; ?>, '<?= htmlspecialchars($ct_img); ?>', '<?= htmlspecialchars($ct_name); ?>')" 
                                            id="heart-icon1-<?= $ct_nid; ?>" title="Add to Wishlist">
                                        <i class="fa <?= ($syl == 1) ? 'fa-heart' : 'fa-heart-o'; ?>"></i>
                                    </button>

                                    <a href="details.php?id=<?= htmlspecialchars($ct_nid); ?>" class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <img src="<?= htmlspecialchars($img_src); ?>" alt="<?= htmlspecialchars($ct_name); ?>" onerror="this.src='img/karuda_logo.png'">
                                    </a>
                                </div>

                                <!-- Body -->
                                <div class="kc-prod-body">
                                    <span class="kc-prod-cat"><?= htmlspecialchars($cat_name); ?></span>
                                    <h4 class="kc-prod-title">
                                        <a href="details.php?id=<?= htmlspecialchars($ct_nid); ?>"><?= htmlspecialchars($ct_name); ?></a>
                                    </h4>

                                    <!-- Rating -->
                                    <div class="kc-prod-stars">
                                        <?php
                                        for ($st = 1; $st <= 5; $st++) {
                                            if ($st <= $defaultRating) {
                                                echo '<i class="fa fa-star"></i>';
                                            } else {
                                                echo '<i class="fa fa-star-o" style="color:#CBD5E1;"></i>';
                                            }
                                        }
                                        ?>
                                        <span>(<?= $defaultRating; ?>.0)</span>
                                    </div>

                                    <!-- Pricing -->
                                    <div class="kc-prod-price-box">
                                        <span class="kc-curr-price">₹ <?= number_format($currentp); ?></span>
                                        <?php if ($oldp > $currentp) { ?>
                                        <span class="kc-old-price">₹ <?= number_format($oldp); ?></span>
                                        <?php } ?>
                                    </div>

                                    <!-- CTA -->
                                    <a href="details.php?id=<?= htmlspecialchars($ct_nid); ?>" class="kc-prod-btn">
                                        View Details
                                    </a>
                                </div>

                            </div>
                        </div>
                        <?php 
                            }
                        } else { 
                        ?>
                        <div class="col-12 text-center py-5">
                            <i class="fa fa-box-open" style="font-size:48px; color:#CBD5E1; margin-bottom:12px;"></i>
                            <h5 style="color:#64748B;">No products found matching your filter criteria.</h5>
                        </div>
                        <?php } ?>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
</body>

<script>
function Category() {
    let get_category = document.querySelectorAll('[name="category"]:checked');
    let starting_price_1 = document.getElementById("starting_price");
    let ending_price = document.getElementById("ending_price").value ?? 0;
    let loading_spinner = document.getElementById("loading_spinner");
    let short_by = document.getElementById("short_by").value;
    let else_price_range = document.getElementById("else_price_range").value;

    let starting_price = starting_price_1.value ?? 0;
    if (starting_price_1.value.length <= 0) {
        starting_price = 1;
    }
    if (ending_price == 0) {
        ending_price = else_price_range;
    }

    let category_list = { "category": [] };
    let Price_list = { 'starting_price': starting_price, 'ending_price': ending_price };

    if (get_category.length > 0) {
        get_category.forEach((cat) => {
            category_list.category.push(cat.value);
        });
    }

    let get_starRatings = document.querySelectorAll('[name="starRatings"]:checked');
    let starRatings_list = { "starRatings": [] };
    if (get_starRatings.length > 0) {
        get_starRatings.forEach((star) => {
            starRatings_list.starRatings.push(star.value);
        });
    }

    let category_list_encoded = encodeURIComponent(JSON.stringify(category_list));
    let starRatings_list_encoded = encodeURIComponent(JSON.stringify(starRatings_list));
    let encodedPriceList = encodeURIComponent(JSON.stringify(Price_list));

    let products_list = document.getElementById("response_for_the_table");
    let xhr = new XMLHttpRequest();

    loading_spinner.style.display = "block";
    xhr.open("GET", "category_list.php?ajax=1&fetch_category=" + category_list_encoded + "&ratings_list=" +
        starRatings_list_encoded + "&price_range=" + encodedPriceList + "&short_by=" + short_by, true);
    xhr.onload = function() {
        loading_spinner.style.display = "none";
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                let response_part = JSON.parse(this.responseText);
                if (response_part && response_part.result) {
                    products_list.innerHTML = response_part.result;
                    document.getElementById('total_of').textContent = response_part.total_products;
                }
            } catch(e) {}
        }
    };
    xhr.onerror = function() {
        loading_spinner.style.display = "none";
    };
    xhr.send();
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php include('include/ad_banners.php'); ?>
<?php include('include/trust_banner.php'); ?>
<?php include('include/footer.php'); ?>
<?php include('include/sign_footer.php'); ?>
</html>