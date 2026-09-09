<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

$active_tab = $_GET['tab'] ?? 'promo_1';
$message = '';
$error = '';

// Handle Promo Banner Update
if (isset($_POST['update_promo'])) {
    $section = mysqli_real_escape_string($con, $_POST['section_name'] ?? 'promo_1');
    $active_tab = $section;
    $badge = mysqli_real_escape_string($con, trim($_POST['badge'] ?? ''));
    $title = mysqli_real_escape_string($con, trim($_POST['title'] ?? ''));
    $description = mysqli_real_escape_string($con, trim($_POST['description'] ?? ''));
    $button_text = mysqli_real_escape_string($con, trim($_POST['button_text'] ?? 'SHOP NOW'));
    $button_link = mysqli_real_escape_string($con, trim($_POST['button_link'] ?? 'allproducts.php'));
    $status = isset($_POST['status']) ? 1 : 0;

    if (empty($title)) {
        $error = 'Banner Title is required!';
    } else {
        $img_sql = "";
        if (isset($_FILES['promo_image']) && $_FILES['promo_image']['error'] === 0) {
            $ext = strtolower(pathinfo($_FILES['promo_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                $dir = __DIR__ . '/../uploads/promo/';
                if (!is_dir($dir)) { mkdir($dir, 0777, true); }
                
                if ($ext !== 'svg') {
                    $webp_file = upload_and_convert_to_webp($_FILES['promo_image'], $dir, 'promo_' . $section . '_');
                    if ($webp_file) {
                        $store_path = 'uploads/promo/' . $webp_file;
                        $img_sql = ", image = '$store_path'";
                    } else {
                        $error = 'Failed to convert banner image to WebP.';
                    }
                } else {
                    $fnew = 'promo_' . $section . '_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES['promo_image']['tmp_name'], $dir . $fnew)) {
                        $store_path = 'uploads/promo/' . $fnew;
                        $img_sql = ", image = '$store_path'";
                    } else {
                        $error = 'Failed to upload banner image.';
                    }
                }
            } else {
                $error = 'Only JPG, PNG, WEBP, and GIF images are allowed.';
            }
        }

        if (empty($error)) {
            $sql = "UPDATE promo_banners SET 
                    badge = '$badge',
                    title = '$title',
                    description = '$description',
                    button_text = '$button_text',
                    button_link = '$button_link',
                    status = '$status'
                    $img_sql
                    WHERE section_name = '$section'";
            if (mysqli_query($con, $sql)) {
                $_SESSION['flash_success'] = 'Promotional Banner (' . ($section === 'promo_1' ? 'Section 1' : 'Section 2') . ') updated successfully!';
                header('Location: promo_settings.php?tab=' . $section);
                exit;
            } else {
                $error = 'Database error: ' . mysqli_error($con);
            }
        }
    }
}

// Fetch current promo banners
$p1_row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM promo_banners WHERE section_name = 'promo_1' LIMIT 1")) ?: [];
$p2_row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM promo_banners WHERE section_name = 'promo_2' LIMIT 1")) ?: [];

require_once('header.php');
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Promotional Banners & Ads Management</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            
            <?php if (!empty($error)): ?>
                <div class="callout callout-danger">
                    <p><i class="icon fa fa-ban"></i> <?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <?php 
            $flash = $_SESSION['flash_success'] ?? '';
            unset($_SESSION['flash_success']);
            if (!empty($flash)): 
            ?>
                <div class="callout callout-success">
                    <p><i class="icon fa fa-check"></i> <?php echo htmlspecialchars($flash); ?></p>
                </div>
            <?php endif; ?>

            <div class="nav-tabs-custom" style="border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.06); overflow:hidden;">
                <ul class="nav nav-tabs">
                    <li class="<?php echo ($active_tab === 'promo_1') ? 'active' : ''; ?>">
                        <a href="#tab_promo_1" data-toggle="tab">
                            <i class="fa fa-fire text-danger"></i> <strong>1. Mid-Page Special Offer Banner (Section 1)</strong>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab === 'promo_2') ? 'active' : ''; ?>">
                        <a href="#tab_promo_2" data-toggle="tab">
                            <i class="fa fa-bullhorn text-primary"></i> <strong>2. Bottom-Page Featured Promo Ad (Section 2)</strong>
                        </a>
                    </li>
                </ul>

                <div class="tab-content" style="padding:25px;">
                    
                    <!-- ═══ TAB 1: PROMO SECTION 1 ═══ -->
                    <div class="tab-pane <?php echo ($active_tab === 'promo_1') ? 'active' : ''; ?>" id="tab_promo_1">
                        <form action="promo_settings.php?tab=promo_1" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <input type="hidden" name="section_name" value="promo_1">

                            <div class="callout callout-info" style="background:#EFF6FF !important; border-color:#0070F3 !important; color:#0055B3 !important; border-radius:8px;">
                                <h4><i class="fa fa-info-circle"></i> Section 1: Mid-Homepage Promo Banner</h4>
                                <p style="font-size:13px; color:#475569; margin:0;">
                                    This banner appears in the middle of the homepage right after Featured Products. You can customize the badge, heading discount, promotional copy, button link, and display image.
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Badge Text</label>
                                <div class="col-sm-6">
                                    <input type="text" name="badge" class="form-control" value="<?php echo htmlspecialchars($p1_row['badge'] ?? 'SPECIAL OFFER'); ?>" placeholder="e.g. SPECIAL OFFER, LIMITED TIME DEAL">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Banner Title *</label>
                                <div class="col-sm-8">
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($p1_row['title'] ?? ''); ?>" required placeholder="e.g. Up to 40% OFF On High Performance Laptops & Accessories">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description Text</label>
                                <div class="col-sm-8">
                                    <textarea name="description" class="form-control" rows="3" placeholder="Description of the offer..."><?php echo htmlspecialchars($p1_row['description'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Button Text</label>
                                <div class="col-sm-4">
                                    <input type="text" name="button_text" class="form-control" value="<?php echo htmlspecialchars($p1_row['button_text'] ?? 'SHOP NOW'); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Button Link URL</label>
                                <div class="col-sm-6">
                                    <input type="text" name="button_link" class="form-control" value="<?php echo htmlspecialchars($p1_row['button_link'] ?? 'allproducts.php'); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Current Image</label>
                                <div class="col-sm-6">
                                    <?php 
                                    $p1_img = !empty($p1_row['image']) ? '../' . ltrim($p1_row['image'], './') : '../img/karuda_hero_laptop.jpg';
                                    ?>
                                    <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:10px; display:inline-block; border-radius:10px;">
                                        <img src="<?php echo htmlspecialchars($p1_img); ?>" alt="Promo 1 Preview" style="max-height:140px; border-radius:8px; object-fit:cover;">
                                    </div>
                                    <p class="text-muted" style="font-size:12px; margin-top:5px;">File: <?php echo htmlspecialchars($p1_row['image'] ?? 'img/karuda_hero_laptop.jpg'); ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Upload New Image</label>
                                <div class="col-sm-6">
                                    <input type="file" name="promo_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Supported formats: JPG, PNG, WEBP (Recommended resolution: 800x500)</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-6">
                                    <label style="font-weight:normal; margin-top:6px;">
                                        <input type="checkbox" name="status" value="1" <?php echo (!empty($p1_row['status'])) ? 'checked' : ''; ?>>
                                        <strong>Active (Show on Homepage)</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <button type="submit" name="update_promo" class="btn btn-success" style="padding:8px 25px; font-weight:700; border-radius:6px;">
                                        <i class="fa fa-save mr-1"></i> Save Section 1 Banner
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ═══ TAB 2: PROMO SECTION 2 ═══ -->
                    <div class="tab-pane <?php echo ($active_tab === 'promo_2') ? 'active' : ''; ?>" id="tab_promo_2">
                        <form action="promo_settings.php?tab=promo_2" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <input type="hidden" name="section_name" value="promo_2">

                            <div class="callout callout-info" style="background:#EFF6FF !important; border-color:#0070F3 !important; color:#0055B3 !important; border-radius:8px;">
                                <h4><i class="fa fa-info-circle"></i> Section 2: Bottom-Homepage Featured Promo Ad</h4>
                                <p style="font-size:13px; color:#475569; margin:0;">
                                    This wide banner is displayed right below the FAQ section before the footer. Perfect for PC build offers, flash sales, or featured campaigns.
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Badge Text</label>
                                <div class="col-sm-6">
                                    <input type="text" name="badge" class="form-control" value="<?php echo htmlspecialchars($p2_row['badge'] ?? 'EXCLUSIVE TECH PROMO'); ?>" placeholder="e.g. EXCLUSIVE TECH PROMO, SPECIAL BUILD OFFER">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Banner Title *</label>
                                <div class="col-sm-8">
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($p2_row['title'] ?? ''); ?>" required placeholder="e.g. BUILD YOUR CUSTOM GAMING PC TODAY">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description Text</label>
                                <div class="col-sm-8">
                                    <textarea name="description" class="form-control" rows="3" placeholder="Description of the offer..."><?php echo htmlspecialchars($p2_row['description'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Button Text</label>
                                <div class="col-sm-4">
                                    <input type="text" name="button_text" class="form-control" value="<?php echo htmlspecialchars($p2_row['button_text'] ?? 'EXPLORE DEALS'); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Button Link URL</label>
                                <div class="col-sm-6">
                                    <input type="text" name="button_link" class="form-control" value="<?php echo htmlspecialchars($p2_row['button_link'] ?? 'allproducts.php'); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Current Image</label>
                                <div class="col-sm-6">
                                    <?php 
                                    $p2_img = !empty($p2_row['image']) ? '../' . ltrim($p2_row['image'], './') : '../img/karuda_hero_gaming_pc.jpg';
                                    ?>
                                    <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:10px; display:inline-block; border-radius:10px;">
                                        <img src="<?php echo htmlspecialchars($p2_img); ?>" alt="Promo 2 Preview" style="max-height:140px; border-radius:8px; object-fit:cover;">
                                    </div>
                                    <p class="text-muted" style="font-size:12px; margin-top:5px;">File: <?php echo htmlspecialchars($p2_row['image'] ?? 'img/karuda_hero_gaming_pc.jpg'); ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Upload New Image</label>
                                <div class="col-sm-6">
                                    <input type="file" name="promo_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Supported formats: JPG, PNG, WEBP (Recommended resolution: 800x500)</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-6">
                                    <label style="font-weight:normal; margin-top:6px;">
                                        <input type="checkbox" name="status" value="1" <?php echo (!empty($p2_row['status'])) ? 'checked' : ''; ?>>
                                        <strong>Active (Show on Homepage)</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <button type="submit" name="update_promo" class="btn btn-success" style="padding:8px 25px; font-weight:700; border-radius:6px;">
                                        <i class="fa fa-save mr-1"></i> Save Section 2 Banner
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>
