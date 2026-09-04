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
<link rel="stylesheet" href="./css/loader.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
    integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
<style>
.upload-btn {
    cursor: pointer;
    display: inline-block;
    padding: 10px;
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
    width: 100%;
}

#images {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
}

#images div {
    position: relative;
    margin: 5px;
}

#images img {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

#images img:hover {
    width: 100px;
    height: 100px;
    object-fit: cover;
    opacity: 0.5;
}

.remove-btn {
    position: absolute;
    top: 2px;
    right: 5px;
    background-color: #252222;
    color: white;
    cursor: pointer;
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 30px;
    text-align: center;
    line-height: 20px;
    font-size: 14px;
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

.star-icon {
    cursor: pointer;
    color: #455972;
}

.star-icon.fas {
    color: #61b647;
}
</style>

<?php 
$error = '';
$success = '';

if(empty($barcode)){
    $barcode = rand(100000,999999).'-'.rand(100000,999999);
}
?>


<section class="content-header">
    <div class="content-header-left">
        <h1>Add Products</h1>
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
                                            <label class="control-label">Product Name*</label>
                                            <input type="text" name="d_name" class="form-control" placeholder=""
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Sub Category *</label>
                                            <input type="text" name="sc" class="form-control" placeholder="" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Select Category *</label>
                                            <select name="category" class="form-control custom-select" required
                                                data-placeholder="Choose a Category">
                                                <option value="" disabled selected>--Select Category--</option>
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
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">No.of Items * </label>
                                            <select name="no_of_items" class="form-control custom-select" required
                                                data-placeholder="Choose a Category">
                                                <option value="1">1</option>
                                                <option value="combo of 2">combo of 2</option>
                                                <option value="combo of 3">combo of 3</option>
                                                <option value="combo of 5">combo of 5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Keywords * <small>(Keywords should be
                                                    comma( ,
                                                    ) separated)</small></label>
                                            <input type="text" name="keywords"
                                                placeholder="keyword 1, keyword 2, etc..." class="form-control"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Age * <small>(for use)</small></label>
                                            <select name="age_range" class="form-control custom-select" required
                                                data-placeholder="Choose a Category">
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
                                                class="form-input-check " required>
                                            <label for="active"
                                                class="control-label btn status_btn_s btn-success">Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="margin:20px 0">
                                        <div class="form-group del_group">
                                            <input type="radio" id="inactive" name="product_status" value="2"
                                                class="form-input-check " required>
                                            <label for="inactive"
                                                class="control-label btn status_btn_s btn-danger">Inactive</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="margin:20px 0">
                                        <div class="form-group del_group">
                                            <input type="radio" id="schedule" name="product_status" value="3"
                                                class="form-input-check " required>
                                            <label for="schedule"
                                                class="control-label btn status_btn_s btn-primary">Scheduled</label>
                                        </div>
                                        <!-- </div> -->
                                    </div>
                                    <!--  -->
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                       
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <div class="form-body">
                                <h4 class="text-bold">Product description :</h4>
                                <hr>
                                <div class="row p-t-20">
                               
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Product description *</label>
                                            <textarea name="p_description" class="form-control" cols="30" rows="25"
                                                id="editor1"></textarea>
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
                                <hr>
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        <div class="form-group del_group">
                                            <input type="checkbox" onchange="SelectPart('delivery_info')" id="wold_Wide"
                                                name="delivery_info[]" value="Worldwide delivery"
                                                class="form-input-check delivery_info" disabled required>

                                            <label for="wold_Wide" class="control-label input_lab">Worldwide
                                                delivery</label>
                                        </div>
                                        <div class="form-group del_group">
                                            <input type="checkbox" onchange="SelectPart('delivery_info')" id="Selective"
                                                name="delivery_info[]" value="Selective delivery"
                                                class="form-input-check delivery_info" disabled required>
                                            <label for="Selective" class="control-label input_lab">Selective
                                                Country</label>
                                        </div>
                                        <div class="form-group del_group">
                                            <input type="checkbox" onchange="SelectPart('delivery_info')" id="local"
                                                name="delivery_info[]" value="Local delivery"
                                                class="form-input-check delivery_info" checked required>
                                            <label for="local" class="control-label input_lab">Local
                                                delivery</label>
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
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <h4 class="text-bold">Star Ratings :</h4>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <button onclick="Clear_stars()" type="button" style="width:50%"
                                            class="btn-success btn-xs btn"> Clear </button>
                                    </div>
                                </div>

                                <hr>
                                <div class="row p-t-20">

                                    <div class="col-12">
                                        <div class="form-group">






                                            <div style="text-align: center; padding: 12px 0;">
                                                <input type="hidden" name="ratings" id="urating" value="5" required>
                                                <div class="star-rating-box" style="display: inline-flex; align-items: center; gap: 8px; background: #fffbeb; padding: 8px 18px; border-radius: 30px; border: 1px solid #fde68a;">
                                                    <i class="fa fa-star star-icon active" data-rating="1" onclick="setRating(1)" style="font-size: 24px; cursor: pointer; color: #f59e0b;"></i>
                                                    <i class="fa fa-star star-icon active" data-rating="2" onclick="setRating(2)" style="font-size: 24px; cursor: pointer; color: #f59e0b;"></i>
                                                    <i class="fa fa-star star-icon active" data-rating="3" onclick="setRating(3)" style="font-size: 24px; cursor: pointer; color: #f59e0b;"></i>
                                                    <i class="fa fa-star star-icon active" data-rating="4" onclick="setRating(4)" style="font-size: 24px; cursor: pointer; color: #f59e0b;"></i>
                                                    <i class="fa fa-star star-icon active" data-rating="5" onclick="setRating(5)" style="font-size: 24px; cursor: pointer; color: #f59e0b;"></i>
                                                    <span id="rating_text" style="font-weight: 700; font-size: 13px; color: #92400e; margin-left: 6px;">5.0 Stars</span>
                                                </div>
                                            </div>
                                            <p class="text-bold " id="ratings_error" style="color:red; display:none; text-align: center;">
                                                Please select star ratings!</p>
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
                                                class="form-input-check " required>
                                            <label for="ref" class="control-label ">Refundable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            <input type="radio" id="non_ref" name="refund" value="Non Refundable"
                                                class="form-input-check " required>
                                            <label for="non_ref" class="control-label ">Non Refundable</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <!--  -->
                                    <div class="col-md-12 borders_show">
                                        <label for="">Delivery Mode *</label>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            <input type="checkbox" onchange="SelectPart('delivery_mode')" id="pay_on"
                                                name="delivery_mode[]" value="Pay on delivery"
                                                class="form-input-check delivery_mode " required>

                                            <label for="pay_on" class="control-label ">Pay on delivery</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <input type="checkbox" onchange="SelectPart('delivery_mode')"
                                                id="shipC_cost" name="delivery_mode[]" value="Shipping Cost"
                                                class="form-input-check delivery_mode " required>
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
                                                value="Free delivery" class="form-input-check " required>
                                            <label for="free_del" class="control-label ">Free delivery</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            <input type="radio" id="no_free" name="delivery_option"
                                                value="Delivery Charge" class="form-input-check delivery_mode "
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row ">
                                                <div class="col-md-6 ">
                                                    <label class="control-label">Barcode * </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="javascript:void(0);" class="btn btn-danger mt-2 "
                                                        style="width:100%" id="btn" onclick="go()">Generate barcode</a>
                                                </div>
                                            </div>
                                            <input type="hidden" name="" id="id_brcode">
                                            <input type="hidden" id="usr" readonly value="<?=$barcode; ?>"
                                                name="barcode" class="form-control" placeholder="0123-4567" required>
                                            <canvas id="barcode" download style="width:70%">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box box-info " style="padding:25px">
                        <div class="card-body">
                            <div class="form-body">
                                <h4 class="text-bold">Product Images :</h4>
                                <hr>
                                <div class="row p-t-20">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Product Images Upload *(only upload 5
                                                image !) <small class="text-warning">accepted for (.jpg, .jpeg,
                                                    .png,
                                                    .svg) types.!</small></label>
                                            <div class="upload-btn" onclick="selectFile()">
                                                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                <span>Choose files to Upload</span>
                                                <input type="file" id="upload-file" multiple
                                                    accept=".jpg, .jpeg, .png, .svg" onchange="uploads(event)"
                                                    style="display: none;">
                                            </div>
                                            <div class="images" id="images"></div>
                                            <p class="text-bold " id="img_error" style="color:red; display:none;">
                                                Select
                                                a product images !</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-right">
                            <div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
                            <button class="btn btn-success" type="submit" name="product_submit">Add Product</button>
                            <button type="button" class="btn btn-warning" onclick="RESET_FORM()">Cancel</button>
                        </div>
                    </div>
                </div>
               
                <!--  -->

           

                
            </div>

    </div>
    </form>
    </div>
</section>
<!-- Confirmation Modal -->
<!-- Confirmation Modal -->
<div id="confirmation-modal">
    <div class="modal-content">
        <p>Are you sure you want to save this product?</p>
        <button id="confirm-yes" class="btn btn-success">Yes</button>
        <button id="confirm-no" class="btn btn-danger">No</button>
    </div>
</div>
<!-- Add some basic styles for the modal -->
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
    background-color: #28A745;
    color: white;
}

.btn-danger {
    background-color: #DC3545;
    color: white;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-danger:hover {
    background-color: #C82333;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.star-icon').click(function() {
        var clickedRating = parseInt($(this).data(
            'rating'));
        $('input[name="urating"]').val(clickedRating);

        $('.star-icon').removeClass('fas').addClass(
            'far');

        for (var i = 1; i <= clickedRating; i++) {
            $('.star-icon[data-rating="' + i + '"]')
                .removeClass('far').addClass('fas');
        }
    });
});

function setRating(rating) {
    document.getElementById("urating").value = rating;
    var labels = ["", "1.0 Star", "2.0 Stars", "3.0 Stars", "4.0 Stars", "5.0 Stars"];
    var ratingText = document.getElementById("rating_text");
    if (ratingText) {
        ratingText.innerText = (labels[rating] || rating + " Stars");
    }
    $('.star-icon').each(function() {
        var r = parseInt($(this).attr('data-rating'));
        if (r <= rating) {
            $(this).removeClass('fa-star-o').addClass('fa-star active').css('color', '#f59e0b');
        } else {
            $(this).removeClass('fa-star active').addClass('fa-star-o').css('color', '#cbd5e1');
        }
    });
}

function Clear_stars() {
    setRating(5);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
function go() {
    const value = document.getElementById("usr").value;
    if (typeof JsBarcode !== 'undefined') {
        JsBarcode("#barcode", value);
    }
    downloadBarCode();
}

let barcode_com_name = '';

function downloadBarCode() {
    const canvas = document.getElementById("barcode");
    const image = canvas.toDataURL("image/jpeg");

    const timestamp = new Date().toISOString().replace(/[-:.]/g, "");
    const filename = `Barcode_${timestamp}.jpg`;
    barcode_com_name = filename;

    const byteString = atob(image.split(',')[1]);
    const mimeString = image.split(',')[0].split(':')[1].split(';')[0];
    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);
    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    const blob = new Blob([ab], {
        type: mimeString
    });

    const formData = new FormData();
    formData.append('file', blob, filename);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "barcode_upd.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("id_brcode").value = "1";
            console.log("File uploaded successfully");
        } else {
            console.error("Error uploading file");
        }
    };
    xhr.send(formData);
}

let allFiles = [];
const MAX_IMAGES = 5;

function selectFile() {
    document.getElementById('upload-file').click();
}

function uploads(event) {
    const files = Array.from(event.target.files);

    if (allFiles.length + files.length > MAX_IMAGES) {
        alert(`You can only upload a maximum of ${MAX_IMAGES} images.`);
        document.getElementById('upload-file').value = "";
        return;
    }

    const uniqueFiles = new Map([...allFiles, ...files].map(file => [file.name, file]));
    allFiles = Array.from(uniqueFiles.values());

    displayImages();
}

function displayImages() {
    const imagesContainer = document.getElementById('images');
    imagesContainer.innerHTML = '';
    const fragment = document.createDocumentFragment();

    allFiles.forEach(file => {
        const src = URL.createObjectURL(file);
        const imgElement = document.createElement('img');
        imgElement.src = src;
        imgElement.className = 'image-preview';

        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.type = 'button';
        removeBtn.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i>';
        removeBtn.onclick = () => removeImage(file.name);

        const container = document.createElement('div');
        container.className = 'image-container';
        container.appendChild(imgElement);
        container.appendChild(removeBtn);

        fragment.appendChild(container);
    });

    imagesContainer.appendChild(fragment);
}

function removeImage(fileName) {
    allFiles = allFiles.filter(file => file.name !== fileName);
    displayImages();
}

async function Check_validation() {
    if (document.getElementById("urating") && document.getElementById("urating").value == 0) {
        document.getElementById("urating").value = "5";
        setRating(5);
    }
    if (document.getElementById("id_brcode") && document.getElementById("id_brcode").value == "") {
        go();
        document.getElementById("id_brcode").value = "1";
    }
    if (allFiles.length === 0) {
        alert("Please select at least one product image!");
        return false;
    }
    // Sync Summernote description
    if (typeof $('#editor1').summernote !== 'undefined') {
        var summernoteContent = $('#editor1').summernote('code');
        $('#editor1').val(summernoteContent);
    }
    var desc = document.getElementById("editor1") ? document.getElementById("editor1").value : "";
    if (desc.trim() === "" || desc === "<p><br></p>") {
        alert("Please enter the product description!");
        return false;
    }
    return true;
}

document.getElementById("product_form").addEventListener("submit", async function(e) {
    e.preventDefault();

    const isValid = await Check_validation();
    if (!isValid) return;

    // Direct submit or confirmation
    const canvas = document.getElementById("barcode");
    let barcodeImage = "";
    if (canvas) {
        try {
            barcodeImage = canvas.toDataURL("image/jpeg");
        } catch(err) { /* ignore */ }
    }
    if (!barcode_com_name) {
        const timestamp = new Date().toISOString().replace(/[-:.]/g, "");
        barcode_com_name = `Barcode_${timestamp}.jpg`;
    }

    const formElement = document.getElementById("product_form");
    const formData = new FormData(formElement);
    allFiles.forEach(file => formData.append('files[]', file));
    formData.append("barcode_image", barcode_com_name);
    formData.append("product_submit", "1");

    let loading_spinner = document.getElementById("loading_spinner");
    if (loading_spinner) loading_spinner.style.display = "block";

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "add_products_backend.php", true);
    xhr.onload = function() {
        if (loading_spinner) loading_spinner.style.display = "none";
        try {
            let raw = this.responseText.trim();
            let jsonStart = raw.indexOf('{');
            let jsonEnd = raw.lastIndexOf('}');
            let result = null;
            if (jsonStart !== -1 && jsonEnd !== -1) {
                result = JSON.parse(raw.substring(jsonStart, jsonEnd + 1));
            } else {
                result = JSON.parse(raw);
            }

            const calloutDanger = document.getElementById("callout-danger");
            const calloutSuccess = document.getElementById("callout-success");
            const scrollPosition = window.scrollY;

            switch (result.status) {
                case 1:
                    if (calloutSuccess) {
                        calloutSuccess.style.display = "block";
                        calloutSuccess.innerHTML = "<p>Step 1 Complete! Redirecting to Step 2 (Add Price & Stock)...</p>";
                    }
                    if (calloutDanger) calloutDanger.style.display = "none";
                    setTimeout(function() {
                        window.location.href = `add_price.php?prd_id=${result.prd_id}`;
                    }, 400);
                    break;
                case 2:
                    if (calloutDanger) {
                        calloutDanger.style.display = "block";
                        calloutDanger.innerHTML = "<p>Product name already exists! Please use a different name.</p>";
                    }
                    if (calloutSuccess) calloutSuccess.style.display = "none";
                    break;
                case 3:
                    alert("Database error: " + (result.error || 'Could not save product'));
                    break;
                case 5:
                    if (calloutDanger) {
                        calloutDanger.style.display = "block";
                        calloutDanger.innerHTML = "<p>Image upload failed!</p>";
                    }
                    break;
                default:
                    alert("Response: " + this.responseText);
            }
            window.scrollTo(0, scrollPosition);
        } catch(e) {
            console.error("Parse error:", e, this.responseText);
            alert("Server returned: " + this.responseText.substring(0, 200));
        }
    };
    xhr.onerror = function() {
        if (loading_spinner) loading_spinner.style.display = "none";
        alert("Network error while submitting product.");
    };
    xhr.send(formData);
});

function RESET_FORM() {
    if (confirm("Are you sure you want to reset the details?")) {
        document.getElementById("product_form").reset();
    }
}

function SelectPart(className) {
    const checkboxes = document.querySelectorAll(`.${className}`);
    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    checkboxes.forEach(checkbox => {
        checkbox.required = !anyChecked;
    });
}
SelectPart('delivery_info');
</script>

<?php require_once('footer.php'); ?>