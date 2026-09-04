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
?>
<?php require_once('header.php'); ?>

<section class="content-header">
    <div class="content-header-left">
        <h1>All Registered Vendors</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card" style="border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,0.05); border:none;">
                <div class="card-body" style="padding:24px;">
                    <h4 class="card-title" style="font-weight:700; color:#0b192c; margin-bottom:20px;">Vendor Registry</h4>
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Owner Name</th>
                                    <th>Business Type</th>
                                    <th>Company Name</th>
                                    <th>Email ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql="SELECT * FROM vendor order by id desc";
                                $query=mysqli_query($con,$sql);
                                
                                if(!mysqli_num_rows($query) > 0 )
                                {
                                    echo '<tr><td colspan="5"><center>No Vendor Data Available!</center></td></tr>';
                                }
                                else
                                {				
                                    while($rows=mysqli_fetch_array($query))
                                    {
                                        echo '<tr>
                                            <td style="vertical-align: middle; font-weight: 600; color: #1e293b;">'.htmlspecialchars($rows['owner_name']).'</td>
                                            <td style="vertical-align: middle;">'.htmlspecialchars($rows['business_type']).'</td>
                                            <td style="vertical-align: middle;">'.htmlspecialchars($rows['company_name']).'</td>
                                            <td style="vertical-align: middle;">'.htmlspecialchars($rows['email']).'</td>
                                            <td style="vertical-align: middle;">
                                                <a href="vender_details.php?id='.htmlspecialchars($rows['id']).'" class="btn btn-warning btn-xs" style="height: 30px; border-radius: 6px; font-weight: 600; padding: 0 16px; display: inline-flex; align-items: center; justify-content: center; background: #eab308 !important; border-color: #eab308 !important; color: #fff; text-decoration: none;">View Details</a>
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
</section>

<?php require_once('footer.php'); ?>