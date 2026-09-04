<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

$active_tab = $_GET['tab'] ?? 'logo';
$message = '';
$error = '';

// 1. Logo Update
if (isset($_POST['update_logo'])) {
    $active_tab = 'logo';
    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['logo_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
            $fnew = 'logo_' . uniqid() . '.' . $ext;
            $dir = __DIR__ . '/../uploads/logo/';
            if (!is_dir($dir)) { mkdir($dir, 0777, true); }
            $store_path = './uploads/logo/' . $fnew;
            if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $dir . $fnew)) {
                $check = mysqli_query($con, "SELECT id FROM logo LIMIT 1");
                if (mysqli_num_rows($check) > 0) {
                    mysqli_query($con, "UPDATE logo SET image = '$store_path'");
                } else {
                    mysqli_query($con, "INSERT INTO logo (image) VALUES ('$store_path')");
                }
                $_SESSION['flash_success'] = 'Website Logo updated successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } else {
                $error = 'Failed to upload logo image.';
            }
        } else {
            $error = 'Only JPG, PNG, WEBP, and SVG images are allowed.';
        }
    } else {
        $error = 'Please select a logo image to upload.';
    }
}

// 2. Favicon Update
if (isset($_POST['update_favicon'])) {
    $active_tab = 'favicon';
    if (isset($_FILES['favicon_image']) && $_FILES['favicon_image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['favicon_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'ico', 'webp'])) {
            $fnew = 'fav_' . uniqid() . '.' . $ext;
            $dir = __DIR__ . '/../uploads/fevicon/';
            if (!is_dir($dir)) { mkdir($dir, 0777, true); }
            $store_path = './uploads/fevicon/' . $fnew;
            if (move_uploaded_file($_FILES['favicon_image']['tmp_name'], $dir . $fnew)) {
                $check = mysqli_query($con, "SELECT id FROM fevicon LIMIT 1");
                if (mysqli_num_rows($check) > 0) {
                    mysqli_query($con, "UPDATE fevicon SET fevicon = '$store_path'");
                } else {
                    mysqli_query($con, "INSERT INTO fevicon (fevicon) VALUES ('$store_path')");
                }
                $_SESSION['flash_success'] = 'Website Favicon updated successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } else {
                $error = 'Failed to upload favicon.';
            }
        } else {
            $error = 'Only JPG, PNG, ICO, and WEBP images are allowed.';
        }
    } else {
        $error = 'Please select a favicon file to upload.';
    }
}

// 3. Contact & Social Update
if (isset($_POST['update_contact'])) {
    $active_tab = 'contact';
    $copyrights = mysqli_real_escape_string($con, $_POST['copyrights'] ?? '');
    $address = mysqli_real_escape_string($con, $_POST['contact_address'] ?? '');
    $email = mysqli_real_escape_string($con, $_POST['contact_email'] ?? '');
    $phone = mysqli_real_escape_string($con, $_POST['contact_phone'] ?? '');
    $alternate = mysqli_real_escape_string($con, $_POST['alternate_number'] ?? '');
    $whatsapp = mysqli_real_escape_string($con, $_POST['whatsapp'] ?? '');
    $facebook = mysqli_real_escape_string($con, $_POST['facebook'] ?? '');
    $instagram = mysqli_real_escape_string($con, $_POST['instagram'] ?? '');
    $map = mysqli_real_escape_string($con, $_POST['contact_map'] ?? '');

    $check = mysqli_query($con, "SELECT id FROM footer_contact LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $id = $row['id'];
        $sql = "UPDATE footer_contact SET 
                copyrights='$copyrights',
                contact_address='$address',
                contact_email='$email',
                contact_phone='$phone',
                alternate_number='$alternate',
                whatsapp='$whatsapp',
                facebook='$facebook',
                instagram='$instagram',
                contact_map='$map'
                WHERE id = '$id'";
    } else {
        $sql = "INSERT INTO footer_contact (copyrights, contact_address, contact_email, contact_phone, alternate_number, whatsapp, facebook, instagram, contact_map)
                VALUES ('$copyrights', '$address', '$email', '$phone', '$alternate', '$whatsapp', '$facebook', '$instagram', '$map')";
    }

    if (mysqli_query($con, $sql)) {
        $_SESSION['flash_success'] = 'Contact and Footer details updated successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
    } else {
        $error = 'Database error: ' . mysqli_error($con);
    }
}

// 4. Topbar Content Add
if (isset($_POST['add_topbar'])) {
    $active_tab = 'topbar';
    $content = mysqli_real_escape_string($con, trim($_POST['topbar_content'] ?? ''));
    $icon = mysqli_real_escape_string($con, trim($_POST['topbar_icon'] ?? 'fa fa-bell'));

    if (!empty($content)) {
        $sql = "INSERT INTO topbar (icon, content) VALUES ('$icon', '$content')";
        if (mysqli_query($con, $sql)) {
            $_SESSION['flash_success'] = 'Topbar announcement text added successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
        } else {
            $error = 'Database error: ' . mysqli_error($con);
        }
    } else {
        $error = 'Topbar content text cannot be empty!';
    }
}

// Fetch current data
$logo_row = mysqli_fetch_assoc(mysqli_query($con, "SELECT image FROM logo ORDER BY id DESC LIMIT 1"));
$current_logo = !empty($logo_row['image']) ? '../' . ltrim($logo_row['image'], './') : '';

$fav_row = mysqli_fetch_assoc(mysqli_query($con, "SELECT fevicon FROM fevicon ORDER BY id DESC LIMIT 1"));
$current_favicon = !empty($fav_row['fevicon']) ? '../' . ltrim($fav_row['fevicon'], './') : '';

$contact = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM footer_contact ORDER BY id DESC LIMIT 1")) ?: [];

require_once('header.php');
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Website & Store Settings</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-ban"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i> <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="nav-tabs-custom" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden;">
                <ul class="nav nav-tabs">
                    <li class="<?php echo ($active_tab === 'logo') ? 'active' : ''; ?>">
                        <a href="#tab_logo" data-toggle="tab"><i class="fa fa-picture-o"></i> <strong>1. Website Logo</strong></a>
                    </li>
                    <li class="<?php echo ($active_tab === 'favicon') ? 'active' : ''; ?>">
                        <a href="#tab_favicon" data-toggle="tab"><i class="fa fa-bookmark-o"></i> <strong>2. Favicon</strong></a>
                    </li>
                    <li class="<?php echo ($active_tab === 'contact') ? 'active' : ''; ?>">
                        <a href="#tab_contact" data-toggle="tab"><i class="fa fa-phone"></i> <strong>3. Contacts & Footer</strong></a>
                    </li>
                    <li class="<?php echo ($active_tab === 'topbar' || $active_tab === 'content-section') ? 'active' : ''; ?>">
                        <a href="#tab_topbar" data-toggle="tab"><i class="fa fa-bullhorn"></i> <strong>4. Topbar Announcement</strong></a>
                    </li>
                </ul>

                <div class="tab-content" style="padding: 25px;">
                    <!-- TAB 1: LOGO -->
                    <div class="tab-pane <?php echo ($active_tab === 'logo') ? 'active' : ''; ?>" id="tab_logo">
                        <form action="web_settings.php?tab=logo" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Current Logo</label>
                                <div class="col-sm-6">
                                    <?php if (!empty($current_logo) && file_exists(__DIR__ . '/' . $current_logo)): ?>
                                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; display: inline-block; border-radius: 8px;">
                                            <img src="<?php echo htmlspecialchars($current_logo); ?>" alt="Current Logo" style="max-height: 80px; max-width: 250px;">
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No logo uploaded yet.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Upload New Logo</label>
                                <div class="col-sm-6">
                                    <input type="file" name="logo_image" class="form-control" accept="image/*" required>
                                    <small class="text-muted">Supported formats: JPG, PNG, SVG, WEBP (Max 2MB)</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <button type="submit" name="update_logo" class="btn btn-success" style="border-radius: 6px; font-weight: 600; padding: 8px 24px;">
                                        <i class="fa fa-upload"></i> Update Logo
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: FAVICON -->
                    <div class="tab-pane <?php echo ($active_tab === 'favicon') ? 'active' : ''; ?>" id="tab_favicon">
                        <form action="web_settings.php?tab=favicon" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Current Favicon</label>
                                <div class="col-sm-6">
                                    <?php if (!empty($current_favicon) && file_exists(__DIR__ . '/' . $current_favicon)): ?>
                                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; display: inline-block; border-radius: 8px;">
                                            <img src="<?php echo htmlspecialchars($current_favicon); ?>" alt="Current Favicon" style="height: 36px; width: 36px;">
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No favicon uploaded yet.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Upload New Favicon</label>
                                <div class="col-sm-6">
                                    <input type="file" name="favicon_image" class="form-control" accept="image/*" required>
                                    <small class="text-muted">Supported formats: ICO, PNG, JPG (Size 32x32 recommended)</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <button type="submit" name="update_favicon" class="btn btn-success" style="border-radius: 6px; font-weight: 600; padding: 8px 24px;">
                                        <i class="fa fa-upload"></i> Update Favicon
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 3: CONTACT & FOOTER -->
                    <div class="tab-pane <?php echo ($active_tab === 'contact') ? 'active' : ''; ?>" id="tab_contact">
                        <form action="web_settings.php?tab=contact" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Copyright Notice</label>
                                <div class="col-sm-6">
                                    <input type="text" name="copyrights" class="form-control" value="<?php echo htmlspecialchars($contact['copyrights'] ?? ''); ?>" placeholder="© 2026 Karuda Computers. All Rights Reserved.">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Physical Address</label>
                                <div class="col-sm-6">
                                    <textarea name="contact_address" class="form-control" rows="3" placeholder="Full store / office address"><?php echo htmlspecialchars($contact['contact_address'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Contact Email</label>
                                <div class="col-sm-6">
                                    <input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($contact['contact_email'] ?? ''); ?>" placeholder="support@Karuda Computers.com">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Primary Phone</label>
                                <div class="col-sm-6">
                                    <input type="text" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars($contact['contact_phone'] ?? ''); ?>" placeholder="+91 9876543210">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Alternate Phone</label>
                                <div class="col-sm-6">
                                    <input type="text" name="alternate_number" class="form-control" value="<?php echo htmlspecialchars($contact['alternate_number'] ?? ''); ?>" placeholder="Secondary phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">WhatsApp Number / Link</label>
                                <div class="col-sm-6">
                                    <input type="text" name="whatsapp" class="form-control" value="<?php echo htmlspecialchars($contact['whatsapp'] ?? ''); ?>" placeholder="+91 9876543210">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Facebook Link</label>
                                <div class="col-sm-6">
                                    <input type="text" name="facebook" class="form-control" value="<?php echo htmlspecialchars($contact['facebook'] ?? ''); ?>" placeholder="https://facebook.com/...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Instagram Link</label>
                                <div class="col-sm-6">
                                    <input type="text" name="instagram" class="form-control" value="<?php echo htmlspecialchars($contact['instagram'] ?? ''); ?>" placeholder="https://instagram.com/...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Google Maps Embed URL</label>
                                <div class="col-sm-6">
                                    <input type="text" name="contact_map" class="form-control" value="<?php echo htmlspecialchars($contact['contact_map'] ?? ''); ?>" placeholder="https://maps.google.com/...">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <button type="submit" name="update_contact" class="btn btn-primary" style="border-radius: 6px; font-weight: 600; padding: 8px 24px;">
                                        <i class="fa fa-save"></i> Save Contact Settings
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 4: TOPBAR ANNOUNCEMENT TEXT (UPLOAD & FETCH) -->
                    <div class="tab-pane <?php echo ($active_tab === 'topbar' || $active_tab === 'content-section') ? 'active' : ''; ?>" id="tab_topbar">
                        <div class="row">
                            <!-- Add Topbar Form -->
                            <div class="col-md-5">
                                <div class="box box-solid" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
                                    <h4 style="font-weight: 600; margin-top: 0; margin-bottom: 15px; color: #1e293b;">
                                        <i class="fa fa-plus-circle text-primary"></i> Add Announcement Text
                                    </h4>
                                    <form action="web_settings.php?tab=topbar" method="post">
                                        <div class="form-group">
                                            <label>Announcement Message <span>*</span></label>
                                            <textarea name="topbar_content" class="form-control" rows="3" placeholder="e.g. Free shipping on all orders over ₹499! Use code: SAVE10" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Icon Class</label>
                                            <input type="text" name="topbar_icon" class="form-control" value="fa fa-bell" placeholder="e.g. fa fa-bell, fa fa-gift, fa fa-truck">
                                            <small class="text-muted">FontAwesome icon classes</small>
                                        </div>
                                        <div style="margin-top: 15px;">
                                            <button type="submit" name="add_topbar" class="btn btn-success btn-block" style="border-radius: 6px; font-weight: 600; padding: 10px;">
                                                <i class="fa fa-upload"></i> Upload Announcement
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Topbar Text List Table (Fetched from topbar table) -->
                            <div class="col-md-7">
                                <h4 style="font-weight: 600; margin-top: 0; margin-bottom: 15px; color: #1e293b;">
                                    <i class="fa fa-list-alt text-info"></i> Active Topbar Announcements
                                </h4>
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th width="10">#</th>
                                                <th>Announcement Text</th>
                                                <th width="80" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ti = 0;
                                            $t_query = mysqli_query($con, "SELECT * FROM topbar ORDER BY id DESC");
                                            if ($t_query && mysqli_num_rows($t_query) > 0) {
                                                while ($t_row = mysqli_fetch_array($t_query)) {
                                                    $ti++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $ti; ?></td>
                                                        <td>
                                                            <?php if (!empty($t_row['icon'])): ?>
                                                                <i class="<?php echo htmlspecialchars($t_row['icon']); ?> text-primary" style="margin-right: 6px;"></i>
                                                            <?php endif; ?>
                                                            <strong><?php echo htmlspecialchars($t_row['content']); ?></strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="#" class="btn btn-danger btn-xs" data-href="delete_image.php?tid=<?php echo $t_row['id']; ?>" data-toggle="modal" data-target="#confirm-delete" title="Delete Announcement">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="3" class="text-center text-muted">No Topbar Announcements Found!</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>