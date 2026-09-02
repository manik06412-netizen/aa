<?php require_once('header.php'); ?>
<?php

$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Meaurement Details';
echo "<script>var sessionTitle = '$title';</script>";

   if(isset($_POST['submit'] ))
   {
       if(empty($_POST['c_name']))
   		{
               echo "<script>alert('field Required!');window.location.href='add_measurements.php';</script>";
               echo $title;
   		}
   	else
   	{
   		

$q_code = mysqli_real_escape_string($con, $_POST['q_code']);
$c_name = mysqli_real_escape_string($con, $_POST['c_name']);


$check_cat = mysqli_query($con, "SELECT bname FROM btype WHERE bname = '$c_name' OR q_code = '$q_code'");

if (mysqli_num_rows($check_cat) > 0) {
    $error_message .= 'Measurements already exist!<br>';
} else {

    $mql = "INSERT INTO btype(q_code, bname) VALUES('$q_code', '$c_name')";
    
    if (mysqli_query($con, $mql)) {
        $success_message = 'New Type Added Successfully!';
    } else {
        $error_message .= 'Error adding new type: ' . mysqli_error($con) . '<br>';
    }
}

   	}
   }
   
   ?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Add Measurement's</h1>
    </div>
    <div class="content-header-right">
        <!-- <a href="color-add.php" class="btn btn-primary btn-sm">Add New</a> -->
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info ">
                <div class="card-body">

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
                    <form action='' method='post' enctype='multipart/form-data'>
                        <div class="form-body">
                            <hr>
                            <div class="container">
                                <div class="row d-flex justify-content-center"
                                    style="display:flex; justify-content:center">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="c_name">Quantity:</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="c_name" name="q_code"
                                                    required>&nbsp;
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="c_name">Measurement Type:</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" id="c_name" name="c_name"
                                                    required>&nbsp;
                                                <div class="text-right">
                                                    <input type="submit" name="submit" class="btn btn-success btn-small"
                                                        value="Add" style="margin-bottom:10px">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <div class="content-header-left">
            <h1>View Measurement's</h1>
        </div>
        <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table">CSV</button>
	<button class="btn btn-primary btn-xs" id="print_table">Print</button>
    <button class="btn btn-primary btn-xs" id="download_pdf">PDF</button>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-bordered text-center table-hover table-striped">
                            <thead>
                                <tr>
                                    
                                    <th>Quantity</th>
                                    <th>Measurement Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                     $sql="SELECT * FROM btype order by id desc";
                     $query=mysqli_query($con,$sql);
                     
                     	if(!mysqli_num_rows($query) > 0 )
                     		{
                     			echo '<td colspan="7"><center>No Categories-Data!</center></td>';
                     		}
                     	else
                     		{		
                                                                     $i=1;		
                     					while($rows=mysqli_fetch_array($query))
                     						{
                     									
                     					 echo '<tr>
                                          
                                            <td>' . $rows['q_code'] . '</td>
                                             <td>' . $rows['bname'] . '</td>
                                              <td><a href="#"
                                              data-href="delete_btype.php?cat_del='.$rows['id'] .'"
                                              data-toggle="modal" data-target="#confirm-delete"
                                               class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                               <i class="fa fa-trash-o" style="font-size:16px"></i></a>
                                            </tr>';
                                            }	
                                        }
                     ?>
                            </tbody>
                        </table>
                    </div>
                </div>
    </section>
</section>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this Measurement?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<?php require_once('footer.php'); ?>