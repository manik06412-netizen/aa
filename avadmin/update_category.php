<?php require_once('header.php'); ?>

<?php
error_reporting(0);
session_start();
$error ='';
$success = '';

if(isset($_POST['submit'] ))
{
    if(empty($_POST['c_name']))
		{
            $error_message = "field Required!";

		}
	else
	{
		
    $fname = $_FILES['images']['name'];
$temp = $_FILES['images']['tmp_name'];
$fsize = $_FILES['images']['size'];
$extension = pathinfo($fname, PATHINFO_EXTENSION); 
$fnew = uniqid() . '.' . $extension;

$allowed_extensions = array('jpg', 'jpeg', 'png','svg');  

if (!empty($fname)) {
    $store = "Res_img/dishes/" . basename($fnew);

    if (in_array($extension, $allowed_extensions)) {
        if (move_uploaded_file($temp, $store)) {
            
        } else {
        }
    } else {
        $error_message = "Invalid file type. Allowed file types are jpg, jpeg,svg and png.";
    }
} else {
    $store = $_SESSION['f'];
    
}
$fname = $_FILES['images1']['name'];
$temp = $_FILES['images1']['tmp_name'];
$fsize = $_FILES['images1']['size'];
$extension = pathinfo($fname, PATHINFO_EXTENSION); 
$fnew = uniqid() . '.' . $extension;

$allowed_extensions = array('jpg', 'jpeg', 'png','svg'); 

if (!empty($fname)) {
    $store1 = "Res_img/dishes/" . basename($fnew);

    if (in_array($extension, $allowed_extensions)) {
        if (move_uploaded_file($temp, $store1)) {
            
        } else {
        }
    } else {
        $error_message = "Invalid file type. Allowed file types are jpg, jpeg, and png.";
    }
} else {
    $store1 = $_SESSION['f1'];
    
}
$check_cat = mysqli_query($con, "SELECT c_name FROM res_category WHERE c_name = '" . $_POST['c_name'] . "' AND c_id != '" . $_GET['cat_upd'] . "'");

	if(mysqli_num_rows($check_cat) > 0)
     {
        $error_message = "Category already exist!";
     }
	else{
    	
	$mql = "update res_category set c_name ='$_POST[c_name]',k1='$_POST[k1]',k2='$_POST[k2]',fpath='$store',icon='$store1' where c_id='$_GET[cat_upd]'";
	mysqli_query($con, $mql);
    $success_message =" Updated! Successfully.";
    
	}
}

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
        <h1>Update Category Details</h1>
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
                            <?php $ssql ="select * from res_category where c_id='$_GET[cat_upd]'";
													$res=mysqli_query($con, $ssql); 
													$row=mysqli_fetch_array($res);
                                                    $_SESSION['f']=$row['fpath'];
                                                    $_SESSION['f1']=$row['icon'];
                                                    ?>
                            <hr>
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Specific Category Name *</label>
                                        <input type="text" name="c_name" value="<?php echo $row['c_name'];  ?>"
                                            class="form-control" placeholder="Update Category Name" required>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Specific Keyword Tag 1 *</label>
                                        <input type="text" name="k1" value="<?php echo $row['k1'];  ?>"
                                            class="form-control" placeholder="Specific Keyword Tag 1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Specific Keyword Tag 2 *</label>
                                        <input type="text" name="k2" value="<?php echo $row['k2'];  ?>"
                                            class="form-control" placeholder="Specific Keyword Tag 2" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Category Image Upload* <small>(Only allowed for .jpeg,
                                                .jpg, .png, .svg)</small></label>
                                        <input type="file" name="images" class="form-control"
                                            accept=".jpg, .jpeg, .png, .svg" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Category Image</label>
                                        <img src="<?php echo $row['fpath']; ?>" class="img-responsive radius"
                                            style="max-height:70px;max-width:150px;" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Category Icon Upload* <small>(Only allowed for .jpeg,
                                                .jpg, .png, .svg)</small></label>
                                        <input type="file" name="images1" class="form-control"
                                            accept=".jpg, .jpeg, .png, .svg" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="">Category Icon</label>
                                        <img src="<?php echo $row['icon']; ?>" class="img-responsive radius"
                                            style="max-height:70px;max-width:150px;" />

                                    </div>
                                </div>
                            </div>
                            <!--/span-->

                        </div>
                        <div class="form-actions text-right">
                            <input type="submit" name="submit" class="btn btn-success" value="Update">
                            <a href="add_category.php" class="btn btn-warning btn-inverse">Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>



<?php require_once('footer.php'); ?>