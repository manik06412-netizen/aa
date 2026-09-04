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
<?php require_once('header.php'); ?>

<?php
error_reporting(0);
session_start();
$error ='';
$success = '';

if(isset($_POST['submit'] ))
{
    $cat_id=$_POST['id'];
  $info=$_POST['info'];
	$mql = "update topbar set content ='$_POST[info]' where id='$_GET[cat_upd]'";
	mysqli_query($con, $mql);
    $success_message =" Updated! Successfully.";
    
	}



?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 " style="margin-top:20px;">
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
        </div>
    </div>
</div>

<section class="content-header">
    <div class="content-header-left">
        <h1>Update Latest Information </h1>
    </div>
    <div class="content-header-right">
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info " style="padding:25px">
                <div class="card-body">
                    <form action='' method='post' enctype='multipart/form-data'>
                        <div class="form-body">
                            <?php $ssql ="select * from topbar where id='$_GET[cat_upd]'";
													$res=mysqli_query($con, $ssql); 
													$row=mysqli_fetch_array($res);
                                                    $_SESSION['f']=$row['fpath'];
                                                    $_SESSION['f1']=$row['icon'];
                                                    ?>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Info*</label>
                                        <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                                        <input type="text" name="info" value="<?php echo $row['content'];  ?>"
                                            class="form-control" placeholder="Update Category Name" required>
                                    </div>

                                </div>
                              
                            </div>
                            <!--/span-->

                        </div>
                        <div class="form-actions text-right">
                            <input type="submit" name="submit" class="btn btn-success" value="Update">
                            <a href="add_info.php" class="btn btn-warning btn-inverse">Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



<?php require_once('footer.php'); ?>