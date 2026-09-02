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
require_once('header.php'); ?>
<link rel="stylesheet" href="./css/loader.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
    integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



<?php
$order_id = $_GET['order_id'];

$sql = "SELECT * from address ad inner join final f on ad.userid=f.user_id where f.order_id='$order_id'";


$res = mysqli_query($con, $sql);

if ($row = mysqli_fetch_array($res)) {
    $status = $row['status'];
}
?>
<?php
if (isset($_POST['shipment_submit'])) {
    $user_id = $_POST['user_id'];
    $track_number = $_POST['track_number'];
    $courier = $_POST['courier'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $cname = $_POST['cname'];
    $cemail = $_POST['cemail'];
    $cmobile = $_POST['cmobile'];
    $caddress = $_POST['caddress'];
    $orders_id = $_POST['orders_id'];
    $note = $_POST['note'];
    $shipping_status = $_POST['shipping_status'];
    $id = $_POST['id'];

    $shipment_date = date('d-m-Y');
    $shipment_time = date('h:i A');

    // Check if the order ID already exists in the shipment table
    $check_query = "SELECT * FROM shipment WHERE order_id = '$orders_id'";
    $check_result = mysqli_query($con, $check_query);
    $record_exists = mysqli_num_rows($check_result) > 0;

    if (mysqli_num_rows($check_result) > 0) {
        // Order ID exists, check if track_number and courier are already set
        $existing_record = mysqli_fetch_assoc($check_result);

        if ($existing_record['track_number'] && $existing_record['courier']) {
            // Track number and courier are already set, update other details only
            $update_query = "UPDATE shipment SET 
                                origin = '$origin', 
                                destination = '$destination', 
                                custmer_name = '$cname', 
                                customer_email = '$cemail', 
                                customer_phone = '$cmobile', 
                                customer_address = '$caddress', 
                                note = '$note', 
                                status = '$shipping_status', 
                                shipment_date = '$shipment_date', 
                                shipment_time = '$shipment_time' 
                            WHERE order_id = '$orders_id'";

            $result_update = mysqli_query($con, $update_query);

            if ($result_update) {
                echo "<script>toastr.success('Shipping Details Updated successfully', 'Success');</script>";
            } else {
                echo "<script>toastr.error('Failed to update shipping details, Please try again', 'Error');</script>";
            }
        } else {
            // Track number and courier are not set, insert them
            $insert_query = "UPDATE shipment SET 
                                track_number = '$track_number', 
                                courier = '$courier', 
                                origin = '$origin', 
                                destination = '$destination', 
                                custmer_name = '$cname', 
                                customer_email = '$cemail', 
                                customer_phone = '$cmobile', 
                                customer_address = '$caddress', 
                                note = '$note', 
                                status = '$shipping_status', 
                                shipment_date = '$shipment_date', 
                                shipment_time = '$shipment_time' 
                            WHERE order_id = '$orders_id'";

            $result_update = mysqli_query($con, $insert_query);

            if ($result_update) {
                echo "<script>toastr.success('Shipping Details Updated successfully', 'Success');</script>";
            } else {
                echo "<script>toastr.error('Failed to update shipping details, Please try again', 'Error');</script>";
            }
        }
    } else {
        // Order ID does not exist, perform an insert
        $insert_query = "INSERT INTO shipment (user_id, order_id, track_number, courier, origin, destination, custmer_name, customer_email, customer_phone, customer_address, note, status, shipment_date, shipment_time) VALUES
                ('$user_id', '$orders_id', '$track_number', '$courier', '$origin', '$destination', '$cname', '$cemail', '$cmobile', '$caddress', '$note', '$shipping_status', '$shipment_date', '$shipment_time')";

        $result_insert = mysqli_query($con, $insert_query);

        if ($result_insert) {
            echo "<script>toastr.success('Shipping Details Uploaded successfully', 'Success');</script>";
        } else {
            echo "<script>toastr.error('Something went wrong, Please try again', 'Error');</script>";
        }
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Shipment </h1>
    </div>

</section>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="callout callout-danger " style="display:none" id="callout-danger">
                <p>

                </p>
            </div>
            <div class="callout callout-success" style="display:none" id="callout-success">
                <p></p>
            </div>
        </div>
        <form id="product_form" action="" method="post" onsubmit="return validateInput()">
            <div class="row" style="margin:10px">
                <div class="col-md-6">
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <div class="form-body">
                                <h4 class="text-bold">Shipment Details : (<?php echo $row['order_id']; ?>)</h4>
                                <hr>
                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                <input type="hidden" name="id" value="<?php echo $rrows['id']; ?>">
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        <?php
                                        $order_id = $_GET['order_id'];
                                        $check_query = "SELECT * FROM shipment WHERE order_id = '$order_id'";
                                        $check_result = mysqli_query($con, $check_query);
                                        $record_exists = mysqli_num_rows($check_result) > 0;
                                        ?>
                                        <div class="form-group">
                                            <label class="control-label">Tracking Number*</label>
                                            <input type="text" id="track_number" name="track_number" class="form-control"
                                                placeholder="" pattern="[0-9]+" title="Only numbers are allowed" required
                                                <?php if ($record_exists) echo 'disabled'; ?>>
                                            <span id="alert-msg" style="color: red; display: none;"></span>


                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> Courier *</label>
                                            <input type="text" name="courier" class="form-control" placeholder="" required
                                                <?php if ($record_exists) echo 'disabled'; ?>>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"> Origin *</label>
                                            <input type="text" name="origin" class="form-control" placeholder="" readonly value="Pudukkottai"
                                                required>
                                        </div>


                                    </div>
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label class="control-label"> Destination *</label>
                                            <input type="text" name="destination" class="form-control" placeholder="" readonly value="<?php echo $row['district']; ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"> Shipment status *</label>
                                            <?php
// Query to fetch shipment status for the given order_id
$query_status = "SELECT status FROM shipment WHERE order_id = '$order_id'";
$result_status = mysqli_query($con, $query_status);

if ($result_status && mysqli_num_rows($result_status) > 0) {
    $row_status = mysqli_fetch_assoc($result_status);
    $status_ship = (int) $row_status['status']; // Ensure status is treated as an integer
} else {
    $status_ship = -1; // If no status is found, set status to -1
}
?>

<select name="shipping_status" class="form-control custom-select" required data-placeholder="Choose a Category">
    <option value="" disabled <?= $status_ship === -1 ? 'selected' : 'hidden' ?>>Choose a status</option>
    <option value="0" <?= $status_ship == 0 ? 'selected' : ($status_ship == -1 ? '' : 'disabled') ?>>Package delivered to source hub</option>
    <option value="1" <?= $status_ship == 1 ? 'selected' : ($status_ship == 0 ? '' : 'disabled') ?>>Package in transit</option>
    <option value="2" <?= $status_ship == 1 ? '' : 'disabled' ?>>Package reached destination</option>
    <option value="3" <?= $status_ship == 2 ? '' : 'disabled' ?>>Package delivered to destination hub</option>
    <option value="4" <?= $status_ship == 3 ? '' : 'disabled' ?>>Package out for delivery</option>
    <option value="5" <?= $status_ship == 4 ? '' : 'disabled' ?>>Package delivered</option>
</select>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <h4 class="text-bold">Customer Details :</h4>
                            <hr>
                            <table class="table table-bordered  table-stripped table-collapse ">
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $row['fname']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $row['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td><?php echo $row['mobile']; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td> <?php echo trim($row['flat']) . ', ' . trim($row['district']) . ', ' . trim($row['state']) . ', ' . trim($row['country']) . '- ' . trim($row['pin']); ?></td>
                                </tr>

                            </table>

                            <div class="form-body">

                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"> Description of Shipping Status*</label>
                                            <textarea type="text" name="note" rows="5" class="form-control" placeholder=""
                                                required></textarea>
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="hidden" name="cname" class="form-control" placeholder="" value="<?php echo $row['fname']; ?>"
                                                readonly required>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="cemail" class="form-control" placeholder="" value="<?php echo $row['email']; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="hidden" name="cmobile" class="form-control" value="<?php echo $row['mobile']; ?>" placeholder="" readonly
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea type="hidden" style="display:none;" name="caddress" class="form-control" placeholder="" required readonly>
                                                <?php echo trim($row['flat']) . ', ' . trim($row['district']) . ', ' . trim($row['state']) . ', ' . trim($row['country']) . '- ' . trim($row['pin']); ?>
                                            </textarea>
                                        </div>
                                    </div>

                                    <input type="hidden" name="orders_id" class="form-control" placeholder=""
                                        value="<?php echo $row['order_id']; ?>" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-right">
                    <div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
                    <button class="btn btn-success" type="submit" name="shipment_submit"> Submit</button>
                    <a href="my_orders.php" type="button" class="btn btn-warning">Go Back</a>
                </div>
            </div>
    </div>
    </form>



    <div class="row ">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:17%">Order Id</th>
                                <th style="width:15%">Track Number</th>
                                <th style="width:15%">Courier</th>
                                <th style="width:13%">Destination</th>
                                <th style="width:20%">Shipment Status</th>
                                <th style="width:12%">Note</th>
                            </tr>
                        </thead>
                        <?php
                        $order_id = $_GET['order_id'];
                        $order_id = mysqli_real_escape_string($con, $order_id);

                        $sql = "SELECT * FROM shipment WHERE order_id = '$order_id' order by id desc limit 1";
                        $query = mysqli_query($con, $sql);
                        ?>
                        <tbody>
                            <?php
                            while ($rows = mysqli_fetch_assoc($query)) {
                                if ($rows['status'] == 0) {
                                    $full_sts = 'Package delivered to source hub';
                                    $full_color = 'btn-primary';
                                } else if ($rows['status'] == 1) {
                                    $full_sts = 'Package in transit';
                                    $full_color = 'btn-primary';
                                } else if ($rows['status'] == 2) {
                                    $full_sts = 'Package reached to destination';
                                    $full_color = 'btn-primary';
                                } else if ($rows['status'] == 3) {
                                    $full_sts = 'Package delivered to destination hub';
                                    $full_color = 'btn-primary';
                                } else if ($rows['status'] == 4) {
                                    $full_sts = 'Package out for delivery';
                                    $full_color = 'btn-primary';
                                } else if ($rows['status'] == 5) {
                                    $full_sts = 'Package delivered';
                                    $full_color = 'btn-success';
                                }
                            ?>
                                <tr>
                                    <td><?php echo $rows['order_id']; ?></td>
                                    <td><?php echo $rows['track_number']; ?></td>
                                    <td><?php echo $rows['courier']; ?></td>
                                    <td><?php echo $rows['destination']; ?></td>
                                    <td><span><?= $full_sts; ?></span></td>
                                    <td><?php echo $rows['note']; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>


                </div>
            </div>
        </div>
</section>
<script>
    function validateInput() {
        const inputField = document.getElementById("track_number");
        const alertMsg = document.getElementById("alert-msg");
        const value = inputField.value;

        if (/[^0-9]/.test(value)) {
            alertMsg.textContent = "Only numbers are allowed!";
            alertMsg.style.display = "block";
            return false;
        } else {
            alertMsg.style.display = "none";
        }

        return true;
    }
</script>
<?php require_once('footer.php'); ?>