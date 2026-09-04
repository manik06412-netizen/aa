<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}
?>
<?php 
session_start();
require_once('header.php'); 
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Slider Details';
echo "<script>var sessionTitle = '$title';</script>";
?>
<style>
	.new_btn{
	background-color:#FF851B !important;
	color:white !important;
	border:1px solid #FF851B !important;
	}
</style>
<?php
if (isset($_POST['form1'])) {
    $valid = 1;
    $error_message = '';
    $success_message = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $path = $_FILES['photo']['name'];
        $path_tmp = $_FILES['photo']['tmp_name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_name = basename($path, '.' . $ext);

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $valid = 0;
            $error_message .= 'You must upload a jpg, jpeg, gif, or png file.<br>';
        }

        if ($valid) {
            $fnew = uniqid() . '.' . $ext;
            $store = "Res_img/dishes/" . basename($fnew);

            if (move_uploaded_file($path_tmp, $store)) {

                $mql = "INSERT INTO slider (banner_img) VALUES ('$store')";
                if (mysqli_query($con, $mql)) {
                    $success_message = 'New Slider Added Successfully.';
                } else {
                    $error_message = 'Error adding slider to the database: ' . mysqli_error($con);
                }

                // mysqli_close($con);
            } else {
                $error_message = 'Error moving the uploaded file.';
            }
        }
    } else {
        $valid = 0;
        $error_message .= 'You must select a photo.<br>';
    }
}


?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Manage Sliders</h1>
    </div>
    <div class="content-header-right">
    <button class="btn btn-primary btn-xs" id="export_table">CSV</button>
	<button class="btn btn-primary btn-xs" id="print_table">Print</button>
    <button class="btn btn-primary btn-xs" id="download_pdf">PDF</button>
        <a href="#" data-toggle="modal" data-target="#confirm-delete" class="btn new_btn btn-sm0">
            Add New</a>
    </div>
</section>


<section class="content">

    <div class="row">
        <div class="col-md-12">

            <?php if($error_message): ?>
            <div class="callout callout-danger">
                <p>
                    <?php echo $error_message; ?>
                </p>
            </div>
            <?php endif; ?>

            <?php if($success_message): ?>
            <div class="callout callout-success">
                <p><?php echo $success_message; ?></p>
            </div>
            <?php endif; ?>
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body table-responsive">
                        <table id="example1" class="table text-center table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID#</th>
                                    <th>Slider Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                            $sql = "SELECT * FROM slider order by id desc";
                            $query = mysqli_query($con, $sql);
							$i = 0;
                            if (!mysqli_num_rows($query) > 0) {
                                echo '<td colspan="7"><center>No Categories-Data!</center></td>';
                            } else {
                                while ($rows = mysqli_fetch_array($query)) { $i++; ?>
                                <tr>
                                    <td><?=$i; ?> </td>
                                    <td><img src="<?=$rows['banner_img'] ?>" width="150px" height="90px" alt=""></td>
                                    <td>
                                        <a href="#" onclick="Open_model(<?=$rows['id'] ?>)"
                                            data-href="slider-delete.php?slider_del=<?=$rows['id'] ?>"
                                            data-toggle="modal" data-target="#confirm-delete_1"
                                            class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                            <i class="fa fa-trash-o" style="font-size:16px"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php  }
                            }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

</section>

<div class="modal fade" id="confirm-delete_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Slider?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="hidden" id="value_return">
                <a id="confirm-delete-btn" onclick="Submit_btn()" class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<script>
function Open_model(id) {
    document.getElementById("value_return").value = id;
}

function Submit_btn() {
    let id = document.getElementById("value_return").value
    location.href = "slider-delete.php?slider_del=" + id;
}
</script>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form class="form-horizontal" action="sliders.php" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Add new Slider</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Photo <span>*</span></label>
                        <div class="col-sm-9" style="padding-top:5px">
                            <input type="file" class="form-control" accept=".jpg, .jpeg, .png, .gif" name="photo">
                            <small class="mt-2">(Only jpg, jpeg, gif and png are allowed)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success btn-ok" name="form1" type="submit">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>






<?php require_once('footer.php'); ?>