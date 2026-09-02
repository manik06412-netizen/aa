<?php
include "header.php";
$id=$_GET['id'];
$select="select * from price where id='$id'";
$result=mysqli_query($con,$select);
if($roow=mysqli_fetch_array($result)){

}
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Update Price</h1>
    </div>
    <!-- <div class="content-header-right">
        <h5 class="text-bold ">Step 2/3</h5>
    </div> -->
</section>
  <div class="col-md-12">
            <div class="box box-info " style="padding:25px">
                <div class="card-body">
                <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Select all input fields with the class 'positive-number'
    const inputFields = document.querySelectorAll('.positive-number');

    // Add an event listener for each input field
    inputFields.forEach(inputField => {
      inputField.addEventListener('input', () => {
        // Get and clean the input value
        let value = inputField.value;

        // Remove any non-numeric characters (allowing empty strings for temporary user input)
        value = value.replace(/[^0-9.]/g, '');

        // Set the cleaned value back to the input field
        inputField.value = value;
      });
    });
  });
</script>

                    <form action='edit_pp1.php' method='post' enctype='multipart/form-data'>
                        <div class="form-body">

                            <?php 
                                    //   $prd_id=$_GET['prd_id'];
                                    $prd_id=6390;
                                    $qml ="select * from dishes where rs_id='$prd_id'";
                                 $rest=mysqli_query($con, $qml); 
                                 $roww=mysqli_fetch_array($rest);
                                $prd_code= $roww['rs_id'];
                                 	?>
                         
                            <h4 class="text-bold">Product Name: <?php echo $roww['dish_name'];?></h4>
                            <hr>
                            <input type="hidden" name="d_name" value="<?php echo $roww['dish_name'];?>"
                                class="form-control" placeholder="Product name" readonly>
                            <input type="hidden" name="pcode" value="<?php echo $roww['rs_id'];?>"
                                class="form-control form-control-danger" placeholder="Product code" readonly>

<input type="hidden" name="id" value="<?php echo $roow['id']; ?>">

                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">MRP *</label>
                                        <input type="text" value="<?php echo $roow['oprice'];?>" name="oprice" class="form-control positive-number" placeholder="Actual MRP"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Volume *</label>
                                        <input type="text" name="qn"value="<?php echo $roow['qn'];?>" class="form-control positive-number" placeholder="Specific Volume"
                                            required>
                                    </div>


                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label">Selling Price *</label>
                                        <input type="text" name="price"value="<?php echo $roow['pp'];?>" class="form-control positive-number" placeholder="Selling Price"
                                            required>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Units *</label>
                                        <select name="weight" class="form-control select2 top-cat"
                                                oninvalid="this.setCustomValidity('Please select a weight')"
                                                oninput="setCustomValidity('')">
                                            <?php
                                            $current_weight = $roow['wg'];
                                            echo '<option value="' . htmlspecialchars($current_weight, ENT_QUOTES, 'UTF-8') . '" selected>' . htmlspecialchars($current_weight, ENT_QUOTES, 'UTF-8') . '</option>';
                                            $statement = $pdo->prepare("SELECT * FROM btype");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                $weight = htmlspecialchars($row['bname'], ENT_QUOTES, 'UTF-8');
                                                echo '<option value="' . $weight . '"' . ($current_weight == $weight ? ' selected' : '') . '>' . $weight . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">GST *</label>
                                        <input type="text" name="gst"  class="form-control positive-number"
                                            placeholder="GST in Percentage" value="<?php echo $roow['gst'];?>" required>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Discount* <small>(%)</small></label>
                                        <input type="text" name="dis"value="<?php echo $roow['discount'];?>" class="form-control positive-number"
                                            placeholder="Discount in Percentage" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Available Quantity *</label>
                                        <input type="text" name="stock"value="<?php echo $roow['total_stock'];?>" class="form-control positive-number"
                                            placeholder="Available Quantity" required>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Stock* </label>
                                        <select name="s_status"value="<?php echo $roow['s_status'];?>" class="form-control select2 top-cat">
                                            <option value="<?php echo $roow['s_status'];?>"><?php echo $roow['s_status'];?></option>

                                            <option value="Instock">Instock</option>
                                            <option value="Currently Unavailable">Currently Unavailable</option>

                                        </select>
                                    </div>
                                </div>
                                <!--/span-->

                            </div>
                            <div class="form-actions text-right">
                                <input type="submit" name="submit" class="btn btn-success" value="Update">
                                <?php

  
    echo '<a href="add_price.php" class="btn btn-warning">GoBack</a>';
   

?>

                            </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
         include "footer.php";
          ?>