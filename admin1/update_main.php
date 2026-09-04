<?php
     error_reporting(0);
     session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
    include ("../dbconnect.php");
    $cat_upd = $_GET['cat_upd'];
$select_details = mysqli_query($con,"SELECT * FROM main_market where id={$cat_upd}");
$fetch_results = mysqli_fetch_array($select_details);
      function CallSelect($con, $value, $table) {
        $options = ''; 
        $query = "SELECT DISTINCT $value FROM $table";
        $result = mysqli_query($con, $query);
        if ($result) {
            
            while ($row = mysqli_fetch_array($result)) {
                $options .= "<option value='" . $row[$value] . "'>" . $row[$value] . "</option>";
            }
        } else {
            $options .= "<option value=''>Error retrieving data</option>";
        }
        return $options; 
    }
    // insert query
    if(isset($_POST['submit'])){
        $c_name = $_POST['c_name'];
        $table_id = $_POST['table_id'];
        $currency = $_POST['currency'];
        $update_connect = null;
        $check = mysqli_query($con,"SELECT * FROM main_market");
        while($row = mysqli_fetch_array($check)){
              if($row["id"] == $table_id ){
                continue;
              }
              if ($row["country"] == $c_name) {
                $update_connect = 4; 
                break; 
            }
        }
        if (!isset($update_connect)){
            $update = mysqli_query($con,"UPDATE main_market SET country = '{$c_name}',currency_code = '{$currency}' WHERE id={$table_id}");
            if($update){
                echo"<script>alert('UPDATED SUCCESSFULLY');window.location.href='main_market.php';</script>";
            }else{
                echo"<script>alert('ERROR');window.location.href='main_market.php';</script>";
            }
        }else{
            echo"<script>alert('ALREADY REGISTERED');window.location.href='main_market.php';</script>";
        }
    }
    ?>
<?php include "head.php"; ?>

<body class="fix-header">
    <div id="main-wrapper">
        <!-- header header  -->
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper" style="height:1200px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Main Market Distribution</h4>
                                </div>
                                <div class="card-body">
                                    <form action='' method='post' enctype='multipart/form-data'>
                                        <input type="hidden" name="table_id" value="<?=$cat_upd; ?>">
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Select Country</label>
                                                    <select onchange="SelectStandard()"
                                                        id="exampleFormControlSelectStandard" name="c_name"
                                                        class="form-control" required>
                                                        <option value="<?=$fetch_results['country']; ?>">
                                                            <?=$fetch_results['country']; ?></option>
                                                        <?= CallSelect($con, 'country_name', 'countries'); ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Currency Code</label>
                                                    <input readonly type="text" name="currency"
                                                        value="<?=$fetch_results['currency_code']; ?>"
                                                        id="exampleFormControlSection" class="form-control"
                                                        placeholder="Currency Code" required>
                                                </div>

                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="form-actions">
                                            <input type="submit" name="submit" class="btn btn-success" value="Save">
                                            <input type="reset" class="btn btn-inverse" value="Cancel">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer"> © All rights reserved. </footer>
        </div>
    </div>

    <script>
    function SelectStandard() {
        let selectedStandard = document.getElementById('exampleFormControlSelectStandard').value;
        console.log(selectedStandard);
        let data = new FormData();
        data.append('Standard', selectedStandard);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'response.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                let result = JSON.parse(xhr.responseText);
                if (result.status === true) {
                    document.getElementById('exampleFormControlSection').value = result.res;
                }
            }
        };
        xhr.send(data);
    }
    </script>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
</body>

</html>