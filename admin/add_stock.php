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
error_reporting(0);
if (empty($_GET['prd_id'])) {
   echo"<script>window.location.href='add_products.php'; </script>";
   
    exit(); 
}

if (isset($_SESSION['success_message'])) {
  
    unset($_SESSION['success_message']);
}
?>

<?php require_once('header.php'); ?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Edit Stock</h1><br />

    </div>
    <div class="content-header-right">
        <h5 class="text-bold ">Step 2/3</h5>
    </div>
</section>


<?php 
                                      $prd_id=$_GET['prd_id'];
                                   // $prd_id=6390;
                                    $qml ="select * from dishes where rs_id='$prd_id'";
                                 $rest=mysqli_query($con, $qml); 
                                 $roww=mysqli_fetch_array($rest);
                                $prd_code= $roww['rs_id'];
                                 	?>


<section class="content">
    <div class="row">
        <div class="col-12">
          
<?php if($_SESSION['success_message']): ?>  
        <div class="callout callout-success">
        <p><?php echo $_SESSION['success_message'] ; ?></p>
        </div>
        <?php endif; ?>    
        
        
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <h4 class="text-bold">&nbsp;&nbsp;&nbsp;Price Details</h4>
                    <hr>
                    <h5 class="text-bold">&nbsp;&nbsp;&nbsp;Product Name: <?php echo $roww['dish_name'];?></h5>
                    <h5 class="text-bold">&nbsp;&nbsp;&nbsp;Product code : #<?php echo $roww['rs_id']; ?></h5>
                    <div class="box-body table-responsive">
                        <table id="example1" class="table text-center table-bordered table-hover table-striped">
                            <thead>
                                <tr>

                                    <th>Weight</th>
                                    <th>Actual Price (RS)</th>
                                    <th>Selling Price (RS)</th>
                                    <th>GST%</th>
                                    <th>Discount%</th>
                                    <th>Total Quantity</th>
                                    <th>Stock Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                   
                                $sql = "SELECT * FROM price WHERE pcode='$prd_id' ORDER BY id DESC";
                                $query = mysqli_query($con, $sql);

                                if (!mysqli_num_rows($query) > 0) {
                            
                                } else {
                                    $ik = 1;
                                    while ($rows = mysqli_fetch_array($query)) {
                                        echo '<tr>
                                            
                                                <td>' . $rows['qn'] . $rows['wg'] . '</td>
                                                <td>' . $rows['oprice'] . '</td>                                                                                      
                                                <td>' . $rows['pp'] . '</td>
                                                <td>' . $rows['gst'] . '</td>
                                                <td>' . $rows['discount'] . '</td>
                                                <td>' . $rows['total_stock'] . '</td>
                                                <td>' . $rows['s_status'] . '</td>
                                                <td>
                                                    
                                                
                                                    

                                            <a href="#" 
                                    data-toggle="modal" 
                                    data-target="#editPriceModal" 
                                    onclick="fet('.$rows['id']. ')"
                                    class="btn btn-primary btn-flat btn-addon btn-xs m-b-10 edit-price">
                                    <i class="fa fa-pencil" style="font-size:16px"></i>
                                    </a>

                                                        


                                                        </td>
                                                    </tr>';
                                            }
                                        }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editPriceModal" tabindex="-1" role="dialog" aria-labelledby="editPriceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editPriceModalLabel">Update Stock</h4>
            </div>
            <div class="modal-body">
                <form id="editPriceForm" action="edit_pp2.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="modal-id">
                    <input type="hidden" name="pcode" id="modal-pcode">
                    <div class="form-body">
                        <div class="row p-t-20">



                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Available Quantity *</label>
                                    <input type="text" name="stock" id="modal-stock"
                                        class="form-control positive-number" placeholder="Available Quantity" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Stock* </label>
                                    <select name="s_status" id="modal-s_status" class="form-control ">
                                        <option value="Instock">Instock</option>
                                        <option value="Currently Unavailable">Currently Unavailable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right">
                            <input type="submit" name="submit" class="btn btn-success" value="Update">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
</section>




<script>
function fet(id_name) {
    let data = new FormData();
    data.append('id', id_name);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_price.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
                // Handle the error as needed
            } else {
                document.getElementById('modal-id').value = result.id;
                document.getElementById('modal-pcode').value = result.pcode;

                document.getElementById('modal-stock').value = result.total_stock;

                document.getElementById('modal-s_status').value = result.s_status;
            }
        } else {
            console.error('Request failed with status: ' + xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error('Request error...');
    };

    xhr.send(data);
}
</script>
<?php require_once('footer.php'); ?>