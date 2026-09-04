<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

$message = '';
$error = '';

if (isset($_POST['submit'])) {
    $k1 = mysqli_real_escape_string($con, trim($_POST['k1'] ?? ''));
    $k2 = mysqli_real_escape_string($con, trim($_POST['k2'] ?? ''));
    $k3 = mysqli_real_escape_string($con, trim($_POST['k3'] ?? ''));
    $link = mysqli_real_escape_string($con, trim($_POST['link'] ?? ''));

    if (empty($k1)) {
        $error = 'Banner Title (Header) is required!';
    } else {
        $fname = $_FILES['images']['name'] ?? '';
        $temp = $_FILES['images']['tmp_name'] ?? '';
        $fsize = $_FILES['images']['size'] ?? 0;
        
        $store = 'Res_img/no_image.png';
        if (!empty($fname)) {
            $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                if ($fsize <= 4194304) { // 4MB
                    $fnew = uniqid() . '.' . $ext;
                    // Ensure banners directory exists
                    if (!is_dir(__DIR__ . '/Res_img/banners')) {
                        mkdir(__DIR__ . '/Res_img/banners', 0777, true);
                    }
                    if (!is_dir(__DIR__ . '/../Res_img/banners')) {
                        mkdir(__DIR__ . '/../Res_img/banners', 0777, true);
                    }
                    $store = "Res_img/banners/" . $fnew;
                    move_uploaded_file($temp, __DIR__ . "/../" . $store);
                    if (file_exists(__DIR__ . "/../" . $store)) {
                        @copy(__DIR__ . "/../" . $store, __DIR__ . "/" . $store);
                    }
                } else {
                    $error = 'Image size should be less than 4MB!';
                }
            } else {
                $error = 'Invalid image format. Allowed formats: JPG, PNG, GIF, WEBP.';
            }
        }

        if (empty($error)) {
            $sql = "INSERT INTO banner (k1, k2, k3, fpath, link) VALUES ('$k1', '$k2', '$k3', '$store', '$link')";
            if (mysqli_query($con, $sql)) {
                $_SESSION['flash_success'] = 'Banner added successfully!';
                header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } else {
                $error = 'Database error: ' . mysqli_error($con);
            }
        }
    }
}

require_once('header.php');
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Banners & Promotional Ads</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <!-- Add Banner Form -->
        <div class="col-md-5">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-ban"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php $message = $_SESSION['flash_success'] ?? ''; unset($_SESSION['flash_success']); ?>
        <?php if (!empty($message)): ?>
                <div class="alert alert-success alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i> <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="box box-info" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 15px;">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: 600; font-size: 16px;">Add New Banner</h3>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="box-body" style="padding-top: 15px;">
                        <div class="form-group">
                            <label>Banner Title (Heading) <span>*</span></label>
                            <input type="text" class="form-control" name="k1" placeholder="e.g. Next-Gen Gaming Laptops" required>
                        </div>

                        <div class="form-group">
                            <label>Subheading</label>
                            <input type="text" class="form-control" name="k2" placeholder="e.g. RTX 40 Series & OLED Displays">
                        </div>

                        <div class="form-group">
                            <label>Description / Tagline</label>
                            <textarea class="form-control" name="k3" rows="2" placeholder="Experience ultra-fast gaming with latest tech..."></textarea>
                        </div>

                        <div class="form-group">
                            <label>Target URL / Link</label>
                            <input type="text" class="form-control" name="link" placeholder="e.g. category_list.php?search=gaming">
                        </div>

                        <div class="form-group">
                            <label>Banner Image <span>*</span></label>
                            <input type="file" class="form-control" name="images" accept="image/*" required>
                        </div>

                        <div style="margin-top: 15px;">
                            <button type="submit" name="submit" class="btn btn-success btn-block" style="border-radius: 6px; font-weight: 600; padding: 10px;">
                                <i class="fa fa-plus"></i> Upload Banner
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Banner List Table -->
        <div class="col-md-7">
            <div class="box box-info" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 15px;">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: 600; font-size: 16px;">All Active Banners</h3>
                </div>
                <div class="box-body table-responsive" style="padding-top: 15px;">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="10">#</th>
                                <th width="80">Image</th>
                                <th>Title / Details</th>
                                <th>Target Link</th>
                                <th width="60">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $query = mysqli_query($con, "SELECT * FROM banner ORDER BY id DESC");
                            if ($query && mysqli_num_rows($query) > 0) {
                                while ($rows = mysqli_fetch_array($query)) {
                                    $i++;
                                    $img_src = !empty($rows['fpath']) ? "../" . $rows['fpath'] : "Res_img/no_image.png";
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td>
                                            <img src="<?php echo htmlspecialchars($img_src); ?>" 
                                                 style="width: 75px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;"
                                                 onerror="this.onerror=null; this.src='Res_img/no_image.png';">
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($rows['k1']); ?></strong>
                                            <?php if (!empty($rows['k2'])): ?>
                                                <br><small style="color: #64748b;"><?php echo htmlspecialchars($rows['k2']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($rows['link'])): ?>
                                                <a href="<?php echo htmlspecialchars($rows['link']); ?>" target="_blank" class="btn btn-default btn-xs">
                                                    <i class="fa fa-external-link"></i> Link
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">None</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-danger btn-xs" data-href="delete_banner.php?cat_del=<?php echo $rows['id']; ?>" data-toggle="modal" data-target="#confirm-delete" title="Delete Banner">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center">No Banners Found!</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
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
                <p>Are you sure you want to delete this banner?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
