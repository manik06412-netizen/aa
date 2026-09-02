<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);

$message = '';
$error = '';

if (isset($_POST['add_pincode'])) {
    $pincode = mysqli_real_escape_string($con, trim($_POST['pincode'] ?? ''));
    $dist = mysqli_real_escape_string($con, trim($_POST['dist'] ?? ''));
    $state = mysqli_real_escape_string($con, trim($_POST['state'] ?? ''));
    $price = floatval($_POST['price'] ?? 0);

    if (empty($pincode)) {
        $error = 'Pincode is required!';
    } else {
        $check = mysqli_query($con, "SELECT id FROM pin WHERE pin = '$pincode'");
        if ($check && mysqli_num_rows($check) > 0) {
            // Update price
            mysqli_query($con, "UPDATE pin SET dist='$dist', state='$state', price='$price' WHERE pin = '$pincode'");
            $_SESSION['flash_success'] = "Pincode $pincode updated successfully!";
            header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
        } else {
            // Insert
            $sql = "INSERT INTO pin (pin, dist, state, price) VALUES ('$pincode', '$dist', '$state', '$price')";
            if (mysqli_query($con, $sql)) {
                $_SESSION['flash_success'] = "Delivery Pincode $pincode added successfully!";
            header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } else {
                $error = "Database error: " . mysqli_error($con);
            }
        }
    }
}

require_once('header.php');
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Delivery Pincodes & Shipping Costs</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <!-- Add / Search Pincode Form -->
        <div class="col-md-5">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-ban"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success alert-dismissible" style="border-radius: 8px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-check"></i> <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="box box-info" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 15px;">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: 600; font-size: 16px;">Add / Update Delivery Pincode</h3>
                </div>
                <form action="" method="post">
                    <div class="box-body" style="padding-top: 15px;">
                        <div class="form-group">
                            <label>Pincode (6-digits) <span>*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="pincode" id="pincode_input" placeholder="e.g. 600001" maxlength="6" required>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" onclick="lookupPincode()" id="lookup_btn">
                                        <i class="fa fa-search"></i> Lookup
                                    </button>
                                </span>
                            </div>
                            <small id="lookup_status" class="text-muted"></small>
                        </div>

                        <div class="form-group">
                            <label>District / City</label>
                            <input type="text" class="form-control" name="dist" id="dist_input" placeholder="e.g. Chennai">
                        </div>

                        <div class="form-group">
                            <label>State</label>
                            <input type="text" class="form-control" name="state" id="state_input" placeholder="e.g. Tamil Nadu">
                        </div>

                        <div class="form-group">
                            <label>Delivery / Shipping Cost (₹) <span>*</span></label>
                            <input type="number" step="0.01" class="form-control" name="price" placeholder="e.g. 50.00" required value="0.00">
                        </div>

                        <div style="margin-top: 15px;">
                            <button type="submit" name="add_pincode" class="btn btn-success btn-block" style="border-radius: 6px; font-weight: 600; padding: 10px;">
                                <i class="fa fa-save"></i> Save Delivery Pincode
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pincode Table List -->
        <div class="col-md-7">
            <div class="box box-info" style="border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 15px;">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: 600; font-size: 16px;">Saved Delivery Pincodes</h3>
                </div>
                <div class="box-body table-responsive" style="padding-top: 15px;">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="10">#</th>
                                <th>Pincode</th>
                                <th>District</th>
                                <th>State</th>
                                <th>Shipping Rate</th>
                                <th width="60">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $query = mysqli_query($con, "SELECT * FROM pin ORDER BY id DESC");
                            if ($query && mysqli_num_rows($query) > 0) {
                                while ($rows = mysqli_fetch_array($query)) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><strong style="color: #2563eb;"><?php echo htmlspecialchars($rows['pin']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($rows['dist'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($rows['state'] ?? 'N/A'); ?></td>
                                        <td><span class="badge bg-green">₹ <?php echo number_format(floatval($rows['price']), 2); ?></span></td>
                                        <td>
                                            <a href="#" class="btn btn-danger btn-xs" data-href="delete_pin.php?id=<?php echo $rows['id']; ?>" data-toggle="modal" data-target="#confirm-delete" title="Delete Pincode">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this delivery pincode?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>

<script>
function lookupPincode() {
    let pin = document.getElementById('pincode_input').value.trim();
    let statusEl = document.getElementById('lookup_status');
    let btn = document.getElementById('lookup_btn');
    
    if (pin.length !== 6) {
        statusEl.innerHTML = '<span style="color:red;">Please enter a valid 6-digit pincode</span>';
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    statusEl.innerHTML = 'Fetching postal details...';

    fetch('https://api.postalpincode.in/pincode/' + pin)
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-search"></i> Lookup';
        if (data && data[0] && data[0].Status === 'Success' && data[0].PostOffice && data[0].PostOffice.length > 0) {
            let po = data[0].PostOffice[0];
            document.getElementById('dist_input').value = po.District || po.Division || '';
            document.getElementById('state_input').value = po.State || '';
            statusEl.innerHTML = '<span style="color:green;"><i class="fa fa-check"></i> Found: ' + (po.Name || '') + ', ' + (po.District || '') + '</span>';
        } else {
            statusEl.innerHTML = '<span style="color:orange;">No postal data found. You can enter district & state manually.</span>';
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-search"></i> Lookup';
        statusEl.innerHTML = '<span style="color:gray;">Manual entry available.</span>';
    });
}
</script>