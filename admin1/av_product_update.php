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

<style>
.upload-btn {
    width: 90%;
    margin-top: 10px;
    height: 150px;
    border: 2px dashed rgb(214, 214, 214);
    margin: 0 auto;
    padding: 24px;
    text-align: center;
    cursor: pointer;
}

.upload-btn i {
    display: block;
    font-size: 70px;
    color: #000;
}

.upload-btn span {
    font-weight: 600;
    margin-top: 11px;
    display: block;
}

#images {
    width: 98%;
    margin: 20px 0;
    display: inline-block;
}

#images img {
    width: 20%;
    height: 120px;
    object-fit: cover;
    margin: 10px;
    padding: 0;
    float: left;
    border: 0.5px solid #ddd;
    cursor: pointer;
    transition: 0.8s ease-in-out;
}

#images img:hover {
    opacity: 0.5;
}

#upload-file {
    display: none;
}

.status_btn_s {
    width: 100% !important;
}

.input_lab {
    padding: 5px;
    cursor: pointer;
    border: 0.2px solid rgb(134, 134, 134);
    border-radius: 4px;
    width: 100%;
    text-align: center;
}

.input_lab:hover {
    padding: 5px;
    cursor: pointer;
    border: 0.2px solid rgb(134, 134, 134);
    border-radius: 4px;
    width: 100%;
    text-align: center;
    box-shadow: 1px 1px 3px rgb(190, 190, 190);
}

.del_group {
    display: flex;
}

.del_group input[type=checkbox] {
    width: 50%;
    transform: scale(0.5);
    transform-origin: 0 0;
}

.del_group input[type=radio] {
    width: 70%;
    transform: scale(0.7);
    transform-origin: 0 0;
}
</style>

<?php
$error = '';
$success = '';

if(empty($barcode)){
    $barcode = rand(10000,99999).'-'.rand(10000,99999);
}


?>
<?php
 $status_id = trim($_GET['menu_upd']);
 $sql=mysqli_query($con,"SELECT * FROM dishes WHERE d_id = $status_id");
 $result = mysqli_fetch_array($sql);

 function MultiSelect($totals) {
    return array_map('trim', explode(',', $totals));
}
?>


<section class="content-header">
    <div class="content-header-left">
        <h1>Update Products</h1>
    </div>
    <div class="content-header-right">
        <h5 class="text-bold ">Step 1/3</h5>
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
        <!-- <form method="post" action="add_products_backend.php" enctype="multipart/form-data"> -->
        <form id="product_form">
            <div class="row" style="margin:10px">
                <div class="col-md-8">
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <div class="form-body">
                                <h4 class="text-bold">Product Information :</h4>
                                <hr>
                                <div class="row p-t-20">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="hidden" name="product_id" value="<?=$result['rs_id'] ?>">
                                            <label class="control-label">Product Name*</label>
                                            <input type="text" name="d_name" class="form-control" placeholder=""
                                                value="<?=$result['dish_name'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Sub Category *</label>
                                            <input type="text" name="sc" class="form-control" placeholder=""
                                                value="<?=$result['subcate'] ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Select Category *</label>
                                            <select name="category" class="form-control custom-select" required
                                                data-placeholder="Choose a Category">
                                                <option value="<?=$result['category'] ?>" selected>
                                                    <?=$result['category'] ?></option>
                                                <?php
                                                    $ssql = "SELECT * FROM res_category";
                                                    $res = $con->query($ssql);
                                                    while ($row = $res->fetch_assoc()) {
                                                        echo '<option value="' . htmlspecialchars($row['c_name']) . '">' . htmlspecialchars($row['c_name']) . '</option>';
                                                    }
                                                     ?>
                                            </select>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Brand Name</label>
                                            <input type="text" name="brand_name" class="form-control" placeholder=""
                                                value="<?=$result['brand_name'] ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">No.of Items * </label>
                                            <select name="no_of_items" class="form-control custom-select" required
                                                data-placeholder="Choose a Category">
                                                <option value="<?=$result['no_items'] ?>"><?=$result['no_items'] ?>
                                                </option>
                                                <option value="1"> 1</option>
                                                <option value="combo of 2">combo of 2</option>
                                                <option value="combo of 3">combo of 3</option>
                                                <option value="combo of 5">combo of 5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Keywords * <small>(Keywords should be comma( ,
                                                    ) separated)</small></label>
                                            <input type="text" name="keywords" value="<?=$result['keywords'] ?>"
                                                placeholder="keyword 1, keyword 2, etc..." class="form-control"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Age * (for use)</label>
                                            <select name="age_range" class="form-control custom-select" required
                                                data-placeholder="Choose a Category">
                                                <option value="<?=$result['age_range'] ?>"><?=$result['age_range'] ?>
                                                </option>
                                                <option value="All">All</option>
                                                <option value="Children">Children</option>
                                                <option value="Adult">Adult</option>
                                                <option value="Mature">Mature</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Best Before * <small>(in
                                                    months)</small></label>
                                            <select name="best_before" class="form-control custom-select" required
                                                data-placeholder="Choose a Category">
                                                <option value="<?=$result['best_before']; ?>">
                                                    <?=$result['best_before']; ?></option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="6">6</option>
                                                <option value="12">12</option>
                                                <option value="No validity">No validity</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-top:10px;">
                                        <label class="text-bold">Product Status :</label>
                                    </div>
                                    <div class="col-md-4" style="margin:20px 0">
                                        <div class="form-group del_group">
                                            <input type="radio" id="active" name="product_status" value="1"
                                                class="form-input-check "
                                                <?php echo $result['status'] == '1' ? "checked" : ""; ?> required>
                                            <label for="active"
                                                class="control-label btn status_btn_s btn-success">Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="margin:20px 0">
                                        <div class="form-group del_group">
                                            <input type="radio" id="inactive" name="product_status" value="2"
                                                class="form-input-check "
                                                <?php echo $result['status'] == '2' ? "checked" : ""; ?> required>
                                            <label for="inactive"
                                                class="control-label btn status_btn_s btn-danger">Inactive</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="margin:20px 0">
                                        <!-- <div class="form-group del_group">
                                            <input type="radio" id="schedule" name="product_status" value="3"
                                                class="form-input-check "
                                                <?php echo $result['status'] == '3' ? "checked" : ""; ?> required>
                                            <label for="schedule"
                                                class="control-label btn status_btn_s btn-primary">Scheduled</label>
                                        </div> -->
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Product description *</label>
                                            <textarea name="p_description" class="form-control" cols="30" rows="10"
                                                id="editor1"><?=$result['description'] ?></textarea>
                                            <div class="invalid-feedback  text-white" id="error_message"
                                                style="display:none; padding:1px;align-items:center;height:45px !important;background-color: red;">
                                                <span>This field is required.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <div class="form-body">
                                <h4 class="text-bold">Delivery Information :</h4>
                                <?php 
                               
                               $delivery_s = MultiSelect($result['deliv_info']);
                               
                               ?>
                                <hr>
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        <div class="form-group del_group">
                                            <input type="checkbox" onchange="SelectPart('delivery_info')" id="wold_Wide"
                                                name="delivery_info[]" value="Worldwide delivery"
                                                class="form-input-check delivery_info"
                                                <?php echo in_array('Worldwide delivery', $delivery_s) ? 'checked' : ''; ?>
                                                disabled required>

                                            <label for="wold_Wide" class="control-label input_lab">Worldwide
                                                delivery</label>
                                        </div>
                                        <div class="form-group del_group">
                                            <input type="checkbox" onchange="SelectPart('delivery_info')" id="Selective"
                                                name="delivery_info[]" value="Selective delivery"
                                                class="form-input-check delivery_info" disabled
                                                <?php echo in_array('Selective delivery', $delivery_s) ? 'checked' : ''; ?>
                                                required>
                                            <label for="Selective" class="control-label input_lab">Selective
                                                Country</label>
                                        </div>
                                        <div class="form-group del_group">
                                            <input type="checkbox" onchange="SelectPart('delivery_info')" id="local"
                                                name="delivery_info[]" value="Local delivery"
                                                class="form-input-check delivery_info"
                                                <?php echo in_array('Local delivery', $delivery_s) ? 'checked' : ''; ?>
                                                required>
                                            <label for="local" class="control-label input_lab">Local delivery</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="control-label">Star Ratings* </label>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button onclick="Clear_stars()" type="button" style="width:50%"
                                                        class="btn-success btn-xs btn"> Clear </button>
                                                </div>
                                            </div>
                                            <div class=""
                                                style="font-size: 30px; padding-bottom:10px; text-align: center;">
                                                <input type="hidden" name="ratings" id="urating"
                                                    value="<?=$result['ratings'] ?>" required>
                                                <i class="fa fa-star star-icon" data-rating="1"></i>
                                                <i class="fa fa-star star-icon" data-rating="2"></i>
                                                <i class="fa fa-star star-icon" data-rating="3"></i>
                                                <i class="fa fa-star star-icon" data-rating="4"></i>
                                                <i class="fa fa-star star-icon" data-rating="5"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <div class="form-body">
                                <h4 class="text-bold">Features :</h4>
                                <hr>
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        <label for="">Refund Options *</label>
                                    </div>

                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            <input type="radio" id="ref" name="refund" value="Refundable"
                                                class="form-input-check "
                                                <?php echo $result['refund'] == 'Refundable' ? "checked" : ""; ?>
                                                required>
                                            <label for="ref" class="control-label ">Refundable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            <input type="radio" id="non_ref" name="refund" value="Non Refundable"
                                                class="form-input-check "
                                                <?php echo $result['refund'] == 'Non Refundable' ? "checked" : ""; ?>
                                                required>
                                            <label for="non_ref" class="control-label ">Non Refundable</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <!--  -->
                                    <div class="col-md-12 borders_show">
                                        <label for="">Delivery Mode *</label>
                                        <?php 
                                    $refund_s = MultiSelect($result['deliv_mode']);
                               
                              ?>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            <input type="checkbox" onchange="SelectPart('delivery_mode')" id="pay_on"
                                                name="delivery_mode[]" value="Pay on delivery"
                                                class="form-input-check delivery_mode "
                                                <?php echo in_array('Pay on delivery', $refund_s) ? 'checked' : ''; ?>
                                                required>

                                            <label for="pay_on" class="control-label ">Pay on delivery</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <input type="checkbox" onchange="SelectPart('delivery_mode')"
                                                id="shipC_cost" name="delivery_mode[]" value="Shipping Cost"
                                                class="form-input-check delivery_mode "
                                                <?php echo in_array('Shipping Cost', $refund_s) ? 'checked' : ''; ?>>
                                            <label for="shipC_cost" class="control-label ">Shipping Cost</label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-md-12 borders_show">
                                        <label for="">Delivery Options *</label>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <input type="radio" id="free_del" name="delivery_option"
                                                value="Free delivery" class="form-input-check "
                                                <?php echo $result['deliv_opt'] == 'Free delivery' ? "checked" : ""; ?>
                                                required>
                                            <label for="free_del" class="control-label ">Free delivery</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            <input type="radio" id="no_free" name="delivery_option"
                                                value="Delivery Charge" class="form-input-check delivery_mode "
                                                <?php echo $result['deliv_opt'] == 'Delivery Charge' ? "checked" : ""; ?>
                                                required>
                                            <label for="no_free" class="control-label ">Delivery Charge</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <div class="form-body">
                                <h4 class="text-bold">Product barcode :</h4>
                                <hr>
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Barcode * </label>
                                            <input type="text" readonly value="<?=$result['barcode'] ?>" name="barcode"
                                                class="form-control" placeholder="0123-4567" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-md-12">
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <div class="form-body">
                                <h4 class="text-bold">Product Images Information:</h4>
                                <hr>
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Product Images Upload *(only upload 5
                                                image !) <small class="text-warning">accepted for (.jpg, .jpeg, .png,
                                                    .svg) types.!</small></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Product Image 1</label>
                                        <input type="file" accept=".jpg, .jpeg, .png, .svg"
                                            onchange="uploads(event,'first_img')" name="image1" class="form-control">
                                        <input type="hidden" value="<?=$result['img']; ?>" name="existing_image1">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Product Existing Image 1</label>
                                        <img id="first_img"
                                            src="<?= htmlspecialchars($result['img'], ENT_QUOTES, 'UTF-8'); ?>"
                                            onerror="this.onerror=null; this.src='Res_img/no_image.png';"
                                            style="width:100%;height:120px" alt="Image">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Product Image 2</label>
                                        <input type="file" accept=".jpg, .jpeg, .png, .svg"
                                            onchange="uploads(event,'first_img1')" name="image2" class="form-control">
                                        <input type="hidden" value="<?=$result['img2']; ?>" name="existing_image2">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Product Existing Image 2</label>
                                        <img id="first_img1" src="<?=$result['img2']; ?>"
                                            onerror="this.onerror=null; this.src='Res_img/no_image.png';"
                                            style="width:100%;height:90px" alt="">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Product Image 3</label>
                                        <input type="file" accept=".jpg, .jpeg, .png, .svg"
                                            onchange="uploads(event,'first_img3')" name="image3" class="form-control">
                                        <input type="hidden" value="<?=$result['img3']; ?>" name="existing_image3">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Product Existing Image 3</label>
                                        <img id="first_img3"
                                            src="<?= htmlspecialchars($result['img3'], ENT_QUOTES, 'UTF-8'); ?>"
                                            onerror="this.onerror=null; this.src='Res_img/no_image.png';"
                                            style="width:100%;height:120px" alt="Image">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Product Image 4</label>
                                        <input type="file" accept=".jpg, .jpeg, .png, .svg"
                                            onchange="uploads(event,'first_img4')" name="image4" class="form-control">
                                        <input type="hidden" value="<?=$result['img4']; ?>" name="existing_image4">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Product Existing Image 4</label>
                                        <img id="first_img4" src="<?=$result['img4']; ?>"
                                            onerror="this.onerror=null; this.src='Res_img/no_image.png';"
                                            style="width:100%;height:90px" alt="">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Product Image 5</label>
                                        <input type="file" accept=".jpg, .jpeg, .png, .svg"
                                            onchange="uploads(event,'first_img5')" name="image5" class="form-control">
                                        <input type="hidden" value="<?=$result['img5']; ?>" name="existing_image5">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="">Product Existing Image 5</label>
                                        <img id="first_img5"
                                            src="<?= htmlspecialchars($result['img5'], ENT_QUOTES, 'UTF-8'); ?>"
                                            onerror="this.onerror=null; this.src='Res_img/no_image.png';"
                                            style="width:100%;height:120px" alt="Image">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button class="btn btn-success" type="submit" name="product_submit">Update
                                    Product</button>
                                <a href="products_list.php" class="btn btn-warning">Go Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- Confirmation Modal -->
<div id="confirmation-modal">
    <div class="modal-content">
        <p>Are you sure you want to save this product?</p>
        <button id="confirm-yes" class="btn btn-success">Yes</button>
        <button id="confirm-no" class="btn btn-danger">No</button>
    </div>
</div>

<style>
#confirmation-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    display: none;
    /* Hide modal by default */
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
    /* Adjust as needed */
}

.modal-content p {
    margin-bottom: 20px;
}

.modal-content button {
    padding: 10px 20px;
    margin: 0 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Function to set the star ratings based on the value
    function setStarRatings(rating) {
        // Reset all stars to default color
        $('.star-icon').css('color', '#455972'); // Default color

        // Set selected stars to yellow
        for (var i = 1; i <= rating; i++) {
            $('.star-icon[data-rating="' + i + '"]').css('color',
                '#61b647'); // Yellow color
        }
    }

    // Get the existing rating value from the hidden input field
    var existingRating = parseInt($('#urating').val());
    setStarRatings(existingRating);

    // Handle click events to set new rating
    $('.star-icon').click(function() {
        var clickedRating = parseInt($(this).data('rating'));
        $('#urating').val(clickedRating);
        setStarRatings(clickedRating);
    });
});

// Function to clear star ratings
function Clear_stars() {
    $('#urating').val(0); // Set rating to 0
    $('.star-icon').css('color', '#455972'); // Reset all stars to default color
}
</script>
<script>
let callout_danger = document.getElementById("callout-danger");
let callout_success = document.getElementById("callout-success");
const scrollPosition = window.scrollY;

document.getElementById("product_form").addEventListener("submit", function(e) {
    e.preventDefault();

    let form_data = new FormData(this);
    form_data.append("product_submit", "");

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_product_backend.php", true);
    xhr.onload = function() {
        let result = JSON.parse(this.responseText);
        if (result.status === 1) {
            callout_success.style.display = "block";
            callout_danger.style.display = "none";
            callout_success.innerHTML = "<p>Product Updated Successfully!</p>";
            document.getElementById("product_form").reset();

        } else if (result.status === 2) {
            callout_danger.style.display = "block";
            callout_success.style.display = "none";
            callout_danger.innerHTML = "<p>Dish name already exists!</p>";
        } else if (result.status === 3) {
            alert("Database error occurred!");
        } else if (result.status === 5) {
            callout_success.style.display = "none";
            callout_danger.style.display = "block";
            callout_danger.innerHTML = "<p>Image upload failed!</p>";
        } else if (result.status === 6) {
            callout_success.style.display = "none";
            callout_danger.style.display = "block";
            callout_danger.innerHTML = "<p>Please Fill the Description!</p>";
        } else {
            alert("An unknown error occurred!");
        }
        window.scrollTo(0, scrollPosition);
    };
    xhr.send(form_data);
});



function RESET_FORM() {
    if (confirm("Are you sure you want to reset the details?")) {
        document.getElementById("product_form").reset();
    }
}


function uploads(event, image_name) {
    let files = event.target.files;
    if (files.length > 0) {
        let file = files[0];
        let reader = new FileReader();

        reader.onload = function(e) {
            let img = document.getElementById(image_name);
            img.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
}

// 

function SelectPart(className) {
    let checkboxes = document.querySelectorAll('.' + className);

    let anyChecked = false;
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            anyChecked = true;
        }
    });

    checkboxes.forEach(function(checkbox) {
        if (anyChecked) {
            checkbox.removeAttribute('required');
        } else {
            checkbox.setAttribute('required', 'required');
        }
    });
}
SelectPart('delivery_info');
SelectPart('delivery_mode');
</script>


<?php require_once('footer.php'); ?>