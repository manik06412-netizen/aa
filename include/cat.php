<?php
/**
 * include/cat.php
 * Karuda Computers — usedlaptopstore.in Style "All categories" Carousel Section
 */
if (!isset($con) || !$con) {
    if (class_exists('\\App\\Core\\Database')) {
        $con = \App\Core\Database::getInstance()->getConnection();
    } elseif (file_exists(__DIR__ . '/../dbconnect.php')) {
        require_once __DIR__ . '/../dbconnect.php';
    }
}

// Fetch categories from DB
$categories_list = [];
if (isset($con) && $con) {
    $c_q = mysqli_query($con, "SELECT c_id, c_name, icon, fpath FROM res_category ORDER BY c_id DESC");
    if ($c_q && mysqli_num_rows($c_q) > 0) {
        while ($cr = mysqli_fetch_assoc($c_q)) {
            $categories_list[] = $cr;
        }
    }
}

// Fallback categories if empty
if (empty($categories_list)) {
    $categories_list = [
        ['c_id' => 1, 'c_name' => 'Laptops', 'icon' => 'img/categories/laptops.svg'],
        ['c_id' => 2, 'c_name' => 'Desktops', 'icon' => 'img/categories/desktops.svg'],
        ['c_id' => 3, 'c_name' => 'Components', 'icon' => 'img/categories/components.svg'],
        ['c_id' => 5, 'c_name' => 'Monitors', 'icon' => 'img/categories/monitors.svg'],
        ['c_id' => 6, 'c_name' => 'Networking', 'icon' => 'img/categories/networking.svg'],
        ['c_id' => 7, 'c_name' => 'Printers', 'icon' => 'img/categories/printers.svg'],
        ['c_id' => 8, 'c_name' => 'Gaming', 'icon' => 'img/categories/gaming.svg'],
        ['c_id' => 10, 'c_name' => 'Accessories', 'icon' => 'img/categories/accessories.svg'],
    ];
}

// Icon mappings for categories without images
$icon_map = [
    'Laptops' => 'fa-laptop text-info',
    'Desktops' => 'fa-desktop text-success',
    'Components' => 'fa-microchip text-danger',
    'Monitors' => 'fa-tv text-warning',
    'Networking' => 'fa-network-wired text-primary',
    'Printers' => 'fa-print text-secondary',
    'Gaming' => 'fa-gamepad text-purple',
    'Accessories' => 'fa-keyboard text-primary'
];
?>

<style>
/* ══════════════════════════════════════════════════════════
   USED LAPTOP STORE STYLE ALL CATEGORIES SECTION
══════════════════════════════════════════════════════════ */
.uls-categories-section {
    background: #F8FAFC;
    padding: 16px 0 32px 0;
    position: relative;
}

.uls-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}

.uls-sec-title {
    font-size: 22px;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
    letter-spacing: -0.3px;
}

.uls-view-all-link {
    color: #334155;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}
.uls-view-all-link i {
    color: #0070F3;
    font-size: 15px;
    transition: transform 0.2s ease;
}
.uls-view-all-link:hover {
    color: #0070F3;
}
.uls-view-all-link:hover i {
    transform: translateX(3px);
}

/* Category Track Container */
.uls-cat-track-wrap {
    position: relative;
}

.uls-cat-track {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 6px 2px 14px 2px;
}
@media (max-width: 991px) {
    .uls-cat-track {
        justify-content: flex-start;
    }
}
.uls-cat-track::-webkit-scrollbar {
    display: none;
}

/* Square White Category Box (usedlaptopstore.in style) */
.uls-cat-card {
    flex: 0 0 115px;
    width: 115px;
    height: 115px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px 8px;
    text-decoration: none !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
    position: relative;
}
.uls-cat-card:hover {
    transform: translateY(-4px);
    border-color: #0070F3;
    box-shadow: 0 10px 25px rgba(0, 112, 243, 0.15);
    background: #FFFFFF;
}

.uls-cat-img-box {
    width: 58px;
    height: 58px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 6px;
}
.uls-cat-img {
    max-width: 52px;
    max-height: 52px;
    object-fit: contain;
    transition: transform 0.2s ease;
}
.uls-cat-card:hover .uls-cat-img {
    transform: scale(1.08);
}

.uls-cat-icon {
    font-size: 28px;
    transition: transform 0.2s ease;
}
.uls-cat-card:hover .uls-cat-icon {
    transform: scale(1.12);
}

.uls-cat-name {
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    text-align: center;
    margin: 0;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
}
.uls-cat-card:hover .uls-cat-name {
    color: #0070F3;
}

/* Scroll Arrow Button */
.uls-cat-scroll-next {
    position: absolute;
    right: -14px;
    top: 50%;
    transform: translateY(-60%);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    color: #334155;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s ease;
}
.uls-cat-scroll-next:hover {
    background: #0070F3;
    color: #ffffff;
    border-color: #0070F3;
    box-shadow: 0 6px 16px rgba(0, 112, 243, 0.3);
}

@media (max-width: 576px) {
    .uls-sec-title { font-size: 18px; }
    .uls-cat-card { flex: 0 0 95px; width: 95px; height: 95px; border-radius: 14px; }
    .uls-cat-img-box { width: 44px; height: 44px; margin-bottom: 4px; }
    .uls-cat-img { max-width: 40px; max-height: 40px; }
    .uls-cat-icon { font-size: 22px; }
    .uls-cat-name { font-size: 11px; }
}
</style>

<!-- ══════════════════════════════════════════════════════════
     ALL CATEGORIES SECTION (usedlaptopstore.in Style)
══════════════════════════════════════════════════════════ -->
<section class="uls-categories-section">
    <div class="container">
        
        <!-- Section Header -->
        <div class="uls-section-header">
            <h3 class="uls-sec-title">All categories</h3>
            <a href="category_list.php" class="uls-view-all-link">
                <span>View all</span>
                <i class="fa-solid fa-circle-chevron-right"></i>
            </a>
        </div>

        <!-- Category Horizontal Track -->
        <div class="uls-cat-track-wrap">
            <div class="uls-cat-track" id="ulsCatTrack">
                <?php foreach ($categories_list as $cat): 
                    $cname = $cat['c_name'];
                    $img_file = !empty($cat['icon']) ? $cat['icon'] : (!empty($cat['fpath']) ? $cat['fpath'] : '');
                    $has_img = !empty($img_file) && file_exists(__DIR__ . '/../' . $img_file);
                    $icon_cls = $icon_map[$cname] ?? 'fa-folder-open text-primary';
                ?>
                    <a href="category_list.php?search=<?php echo urlencode($cname); ?>" class="uls-cat-card">
                        <div class="uls-cat-img-box">
                            <?php if ($has_img): ?>
                                <img src="<?php echo htmlspecialchars($img_file); ?>" alt="<?php echo htmlspecialchars($cname); ?>" class="uls-cat-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <i class="fa <?php echo $icon_cls; ?> uls-cat-icon" style="display:none;"></i>
                            <?php else: ?>
                                <i class="fa <?php echo $icon_cls; ?> uls-cat-icon"></i>
                            <?php endif; ?>
                        </div>
                        <p class="uls-cat-name"><?php echo htmlspecialchars($cname); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Scroll Right Next Button -->
            <button type="button" class="uls-cat-scroll-next" id="ulsCatScrollNext" aria-label="Scroll Categories Right">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>

    </div>
</section>

<script>
(function() {
    const track = document.getElementById('ulsCatTrack');
    const btnNext = document.getElementById('ulsCatScrollNext');
    if (track && btnNext) {
        btnNext.addEventListener('click', function() {
            track.scrollBy({ left: 300, behavior: 'smooth' });
        });
    }
})();
</script>