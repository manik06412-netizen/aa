<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

$error_message   = '';
$success_message = '';

/* ── ADD NEW PROMOTIONAL SLIDER AD ── */
if (isset($_POST['add_slider'])) {
    $store = 'Res_img/no_image.png';

    if (!empty($_FILES['banner_img']['name']) && $_FILES['banner_img']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['banner_img']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            $error_message = 'Invalid image format.';
        } elseif ($_FILES['banner_img']['size'] > 4194304) {
            $error_message = 'Image must be under 4MB.';
        } else {
            $fnew = uniqid() . '.' . $ext;
            foreach ([__DIR__ . '/Res_img/sliders', __DIR__ . '/../Res_img/sliders'] as $dir) {
                if (!is_dir($dir)) mkdir($dir, 0777, true);
            }
            $store = 'Res_img/sliders/' . $fnew;
            move_uploaded_file($_FILES['banner_img']['tmp_name'], __DIR__ . '/../' . $store);
            @copy(__DIR__ . '/../' . $store, __DIR__ . '/' . $store);
            
            $sql = "INSERT INTO slider (banner_img) VALUES ('$store')";
            if (mysqli_query($con, $sql)) {
                $_SESSION['flash_success'] = 'Promotional Slider Ad added successfully!';
                header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } else {
                $error_message = 'DB error: ' . mysqli_error($con);
            }
        }
    } else {
        $error_message = 'Please upload an image.';
    }
}

/* ── DELETE SLIDER ── */
if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $del_id = (int)$_GET['del'];
    $res = mysqli_query($con, "SELECT banner_img FROM slider WHERE id=$del_id");
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $fp = __DIR__ . '/../' . $row['banner_img'];
        if (file_exists($fp) && strpos($row['banner_img'], 'no_image') === false) @unlink($fp);
        mysqli_query($con, "DELETE FROM slider WHERE id=$del_id");
        $_SESSION['flash_success'] = 'Slider ad deleted successfully.';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
    }
}

require_once('header.php');
?>
<!-- ══════════════════════════════════════════════════════════
     KARUDA COMPUTERS — SLIDERS (PROMO ADS) MANAGEMENT
══════════════════════════════════════════════════════════ -->
<section class="content-header">
    <h1>
        <i class="fa fa-picture-o text-primary"></i> Promotional Sliders
        <small>Manage image-only ad banners</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sliders</li>
    </ol>
</section>

<section class="content">
    <?php
    if (!empty($_SESSION['flash_success'])) {
        echo '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fa fa-check"></i> ' . $_SESSION['flash_success'] . '</div>';
        unset($_SESSION['flash_success']);
    }
    if (!empty($error_message)) {
        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fa fa-ban"></i> ' . $error_message . '</div>';
    }
    ?>

    <div class="row">
        <div class="col-xs-12">
            
            <!-- Quick Add Button -->
            <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addSliderModal" style="border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <i class="fa fa-plus-circle"></i> Add New Promotional Ad
                    </button>
                </div>
            </div>

            <!-- Sliders Table -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Active Promotional Ads</h3>
                    <span class="text-muted" style="font-size:12px; margin-left:10px;">These images appear in the standalone ad section on the frontend.</span>
                </div>
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="40">#</th>
                                <th>Ad Image</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $q = mysqli_query($con, "SELECT * FROM slider ORDER BY id DESC");
                        $i = 0;
                        if (!$q || mysqli_num_rows($q) == 0) {
                            echo '<tr><td colspan="3" class="text-center">No promotional sliders found. Click "Add New Promotional Ad" to add one!</td></tr>';
                        } else {
                            while ($r = mysqli_fetch_assoc($q)) {
                                $i++;
                                $img = !empty($r['banner_img']) ? '../' . $r['banner_img'] : 'Res_img/no_image.png';
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td>
                                <a href="<?= $img ?>" data-fancybox="gallery">
                                    <img src="<?= $img ?>" style="max-height:80px; max-width:250px; border-radius:6px; object-fit:cover; border: 1px solid #ddd;" onerror="this.src='Res_img/no_image.png';">
                                </a>
                            </td>
                            <td>
                                <a href="?del=<?= $r['id'] ?>"
                                    onclick="return confirm('Delete this promotional ad?')"
                                    class="btn btn-danger btn-xs" title="Delete">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Add Slider Modal -->
<div class="modal fade" id="addSliderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-image"></i> Add New Promotional Ad</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Promotional Ad Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="banner_img" accept="image/*" required>
                        <small class="text-muted" style="display:block; margin-top:5px;">Upload a clean, high-quality image (JPG, PNG). Max 4MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_slider" class="btn btn-success">
                        <i class="fa fa-upload"></i> Upload Ad
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable({
            "order": [],
            "columnDefs": [
                { "orderable": false, "targets": [1, 2] }
            ],
            "language": {
                "emptyTable": "No promotional ads found!",
                "zeroRecords": "No records found!"
            }
        });
    }
});
</script>

<?php require_once('footer.php'); ?>