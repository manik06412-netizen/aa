<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);
?>
<?php
include("../config.php");
error_reporting(0);

if(isset($_POST['submit'] ))
{
    if(empty($_POST['code']))
    {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Field Required!</strong>
                  </div>';
    }
    else
    {
        $check_cat= mysqli_query($con, "SELECT code FROM promo where code = '".$_POST['code']."' ");
        if(mysqli_num_rows($check_cat) > 0)
        {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Promocode already exists!</strong>
                      </div>';
        }
        else
        {
            $date = date("D M d Y");               
            $mql = "INSERT INTO promo VALUES(null,'".$_POST['code']."','".$_POST['pur']."','".$_POST['sdat']."','".$_POST['edat']."','".$_POST['dis']."','0','" . $date . "')";
            if (mysqli_query($con, $mql)) {
                $_SESSION['flash_success'] = 'New Promocode Added Successfully.';
                header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            }
        }
    }
}
?>
<?php require_once('header.php'); ?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Promo Codes Manager</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <?php  
            if(!empty($error)) echo $error;
            
            $flash_promo = $_SESSION['flash_success'] ?? '';
            unset($_SESSION['flash_success']);
            if (!empty($flash_promo)): ?>
            <div class="alert alert-success alert-dismissible fade show" style="border-radius:8px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Congrats!</strong> <?php echo htmlspecialchars($flash_promo); ?>
            </div>
            <?php endif;
            ?>
        </div>

        <div class="col-lg-12">
            <div class="card card-outline-primary" style="border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.05); border:none; margin-bottom: 30px;">
                <div class="card-header" style="background:#0b192c; padding:15px 20px; border-radius:12px 12px 0 0; border:none;">
                    <h4 class="m-b-0 text-white" style="margin:0; font-weight:600; font-size:15px;"><i class="fa fa-tag"></i> Add Promo code</h4>
                </div>
                <div class="card-body" style="padding:24px; background:#fff; border-radius:0 0 12px 12px; border:1px solid #e2e8f0; border-top:none;">
                    <form action='' method='post' enctype='multipart/form-data'>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" style="font-weight:600; color:#334155; margin-bottom: 8px; display: inline-block;">Promo Code</label>
                                        <div style="display:flex; width:100%; align-items: stretch;">
                                            <input type="text" name="code" id="promoCode" class="form-control" maxlength="8" placeholder="Click Generate to create a code" style="border-radius:8px 0 0 8px; height: 38px; flex: 1; min-width: 0; border-right: none;" required>
                                            <button type="button" class="btn btn-info" onclick="generatePromoCode()" style="border-radius:0 8px 8px 0; height:38px; margin:0 !important; background:#008290; border:1px solid #008290; color:#fff; cursor:pointer; font-weight:600; padding: 0 15px; flex-shrink: 0; display: inline-flex; align-items: center; gap: 6px;"><i class="fa fa-refresh"></i> Generate</button>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                function generatePromoCode() {
                                    var codeLength = 8;
                                    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                                    var generatedCode = '';
                                    for (var i = 0; i < codeLength; i++) {
                                        var randomIndex = Math.floor(Math.random() * characters.length);
                                        generatedCode += characters.charAt(randomIndex);
                                    }
                                    document.getElementById('promoCode').value = generatedCode;
                                }
                                </script>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" style="font-weight:600; color:#334155; margin-bottom: 8px; display: inline-block;">Discount %</label>
                                        <select name="dis" class="form-control" style="border-radius:8px; height:38px;" required>
                                            <option value="" disabled selected>Select Discount %</option>
                                            <option value="5">5%</option>
                                            <option value="10">10%</option>
                                            <option value="20">20%</option>
                                            <option value="30">30%</option>
                                            <option value="40">40%</option>
                                            <option value="50">50%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top:15px;">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php $date=date("Y-m-d"); ?>
                                        <label class="control-label" style="font-weight:600; color:#334155; margin-bottom: 8px; display: inline-block;">Purpose</label>
                                        <textarea name="pur" class="form-control" placeholder="Enter Purpose" style="border-radius:8px; min-height:38px; height: 38px;" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" style="font-weight:600; color:#334155; margin-bottom: 8px; display: inline-block;">Start Date</label>
                                        <input type="date" name="sdat" class="form-control" style="border-radius:8px; height:38px;"
                                        min="<?php echo date('Y-m-d'); ?>" placeholder="Enter Start Date" value="<?php echo $date; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" style="font-weight:600; color:#334155; margin-bottom: 8px; display: inline-block;">End Date</label>
                                        <input type="date" name="edat" class="form-control" style="border-radius:8px; height:38px;"
                                        min="<?php echo date('Y-m-d'); ?>" placeholder="Enter End Date" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions" style="margin-top:25px; display:flex; gap:10px;">
                            <button type="submit" name="submit" class="btn btn-primary" style="background:#0070F3; border-color:#0070F3; padding:8px 24px; font-weight:600; border-radius:8px; cursor:pointer;"><i class="fa fa-check"></i> Save Code</button>
                            <a href="dashboard.php" class="btn btn-default" style="padding:8px 24px; font-weight:600; border-radius:8px; border:1px solid #cbd5e1; background:#f8fafc; color:#475569; text-decoration:none; display:inline-flex; align-items:center; height:38px; justify-content: center;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card" style="border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.05); border:none;">
                <div class="card-body" style="padding:24px;">
                    <h4 class="card-title" style="font-weight:700; color:#0b192c; margin-bottom:20px;">Listed Promocode</h4>
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>ID#</th>
                                    <th>PromoCode</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Modify Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql="SELECT * FROM promo order by id desc";
                                $query=mysqli_query($con,$sql);
                                
                                if(!mysqli_num_rows($query) > 0 )
                                {
                                    echo '<tr><td colspan="6"><center>No Promo Code Data!</center></td></tr>';
                                }
                                else
                                {				
                                    while($rows=mysqli_fetch_array($query))
                                    {
                                        $status = $rows['status'];
                                        
                                        if ($status == 0) {
                                            $buttonClass = 'act-status';
                                            $buttonText = 'Activate';
                                            $buttonLink = 'update_status.php?cat_upd=' . $rows['id'] . '&status=1';
                                        } else {
                                            $buttonClass = 'act-delete';
                                            $buttonText = 'Inactivate';
                                            $buttonLink = 'update_status.php?cat_upd=' . $rows['id'] . '&status=0';
                                        }
                                        
                                        echo '<tr>
                                            <td style="vertical-align: middle;">' . htmlspecialchars($rows['id']) . '</td>
                                            <td style="vertical-align: middle;"><b>' . htmlspecialchars($rows['code']) . '</b></td>
                                            <td style="vertical-align: middle;">' . htmlspecialchars($rows['sdat']) . '</td>
                                            <td style="vertical-align: middle;">' . htmlspecialchars($rows['edat']) . '</td>
                                            <td style="vertical-align: middle;">' . htmlspecialchars($rows['dat']) . '</td>
                                            <td style="vertical-align: middle;">
                                                <div class="action-btn-group" style="max-width: 140px; margin: 0 auto; justify-content: center;">
                                                    <a href="update_promo.php?cat_upd=' . htmlspecialchars($rows['id']) . '" class="act-btn act-edit" title="Settings"><i class="fa fa-cog"></i></a>
                                                    <a href="' . htmlspecialchars($buttonLink) . '" class="act-btn ' . $buttonClass . '" title="' . $buttonText . '"><i class="fa fa-power-off"></i></a>
                                                    <a href="#" onclick="confirmDelete(' . $rows['id'] . ')" class="act-btn act-delete" title="Delete Promo"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </td>
                                        </tr>';
                                    }
                                }
                                ?>
                                 <script>
                                 function confirmDelete(categoryId) {
                                     var confirmDelete = confirm("Are you sure you want to delete this Promo Code?");
                                     if (confirmDelete) {
                                         window.location.href = 'delete_promo.php?cat_del=' + categoryId;
                                     }
                                 }
                                 </script>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>