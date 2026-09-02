<?php
session_start();
error_reporting(0);
include('inc/config.php');
if (empty($_GET['prd_id'])) {
   echo"<script>window.location.href='add_products.php'; </script>";
   
    exit(); 
}
if (isset($_POST['submit_edit'])) {
    $price_id = $_POST['price_id'];
    $weight = $_POST['weight'];
    $oprice = $_POST['oprice'];
    $sprice = $_POST['sprice'];
    $gst = $_POST['gst'];
    $discount = $_POST['discount'];
    
    $statement = $pdo->prepare("UPDATE price SET qn=?, oprice=?, pp=?, gst=?, discount=? WHERE id=?");
    $statement->execute(array($weight, $oprice, $sprice, $gst, $discount, $stock, $status, $price_id));

    $_SESSION['success_message'] = 'Price updated successfully.';
    
    echo "<script>window.location.href='$_SERVER[PHP_SELF]?prd_id=$prd_id';</script>";

    exit;
}

if(isset($_POST['submit'] ))
   {
        		
   	$check_cat= mysqli_query($con, "SELECT bname FROM btype where bname = '".$_POST['c_name']."' ");
   	if(mysqli_num_rows($check_cat) > 0)
        {
            $_SESSION['error_message'] .= 'Measurements already exist!';
          
        }
   	else{              
   	$mql = "INSERT INTO btype(bname) VALUES('".$_POST['c_name']."')";
   	mysqli_query($con, $mql);
      
    $_SESSION['success_message'] = 'New Type Added Successfully.!';
      
       }
   	}
          
?>
<?php 
session_start();
require_once('header.php'); ?>
<?php 
                                    $prd_id=$_GET['prd_id'];
                                    $qml ="select * from dishes where rs_id='$prd_id'";
                                    $rest=mysqli_query($con, $qml); 
                                    $roww=mysqli_fetch_array($rest);
                                    $prd_code= $roww['rs_id'];
                                 	?>



<section class="content-header">
    <div class="content-header-left">
        <h1>Add Price</h1><br />

    </div>
    <div class="content-header-right">
        <h5 class="text-bold ">Step 2/3</h5>
    </div>
</section>


<section class="content">
    <div class="row">
        <div class="col-12">
            <?php if (isset($_SESSION['error_message'])): ?>
            <div id="error-message" class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
            <div id="success-message" class="alert alert-success">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <div class="box box-info " style="padding:25px">
                <div class="card-body">


                    <form action='add_price_backend.php' id="myForm" method='post' enctype='multipart/form-data'>
                        <div class="form-body">


                            <h5 class="text-bold">Product Name: <?php echo $roww['dish_name'];?></h5>
                            <h5 class="text-bold">Product code : #<?php echo $roww['rs_id']; ?></h5>
                            <hr>
                            <input type="hidden" name="d_name" value="<?php echo $roww['dish_name'];?>"
                                class="form-control" placeholder="Product name" readonly>
                            <input type="hidden" name="pcode" value="<?php echo $roww['rs_id'];?>"
                                class="form-control form-control-danger" placeholder="Product code" readonly>


                            <div class="row p-t-20">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">MRP *</label>
                                        <input type="text" id="mrp" name="oprice" class="form-control positive-number"
                                            placeholder="Actual MRP" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Volume *</label>
                                        <input type="text" name="qn" class="form-control positive-number"
                                            placeholder="Specific Volume" required>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label">Selling Price *</label>
                                        <input type="text" name="price" id="sellingPrice"
                                            class="form-control positive-number" placeholder="Selling Price" required>
                                        <div id="sellingPriceError" class="error-message"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="control-label">Units *</label>
                                        <div style="display:flex">
                                            <select name="weight" class="form-control"
                                                oninvalid="this.setCustomValidity('Please select a weight')"
                                                oninput="setCustomValidity('')">
                                                <option value="">Select units</option>
                                                <?php
                                                $statement = $pdo->prepare("SELECT * FROM btype ");
                                                $statement->execute();
                                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);    
                                                foreach ($result as $row) {
                                                ?>
                                                <option value="<?php echo $row['bname']; ?>">
                                                    <?php echo $row['bname']; ?>
                                                </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <a href="#" class="btn btn-warning mt-3" data-toggle="modal"
                                                data-target="#uniqueConfirmationModal" id="unitsbtn">+Add New</a>
                                        </div>
                                    </div>

                                </div>
                                <!-- <div class="col-md-1">
                                    <div class="form-group">
                                        <label class="control-label"></label><br />
                                        <a href="#" class="btn btn-warning mt-3 " data-toggle="modal"
                                            data-target="#confirmationModal" id="nextButton">Next</a>
                                    </div>
                                </div> -->


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">GST (%)</label>

                                        <select name="gst" class="form-control "
                                            oninvalid="this.setCustomValidity('Please select a gst')"
                                            oninput="setCustomValidity('')">
                                            <option value="0">0</option>
                                            <option value="5">5</option>
                                            <option value="12">12</option>
                                            <option value="18">18</option>
                                            <option value="28">28</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Discount* <small>(%)</small></label>
                                        <input type="text" name="dis" class="form-control positive-number"
                                            placeholder="Discount in Percentage" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Available Quantity *</label>
                                        <input type="number" name="stock" min="1" class="form-control "
                                            placeholder="Available Quantity" required>

                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Stock* </label><br />
                                        <div class="row">
                                            <div class="col-md-6 ">
                                                <input type="radio" id="free_del" name="s_status" value="Instock"
                                                    class="form-input-check " required>

                                                <label for="free_del" class="control-label ">Instock</label>
                                            </div>
                                            <div class="col-md-6 ">
                                                <input type="radio" id="no_free" name="s_status"
                                                    value="Currently Unavailable"
                                                    class="form-input-check delivery_mode " required>
                                                <label for="no_free" class="control-label ">Currently
                                                    Unavailable</label>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="form-actions text-right">
                                <input type="submit" name="submit" class="btn btn-success" value="Add">
                                <?php
                                    $sql2 = "SELECT * FROM price WHERE pcode='$prd_id'";
                                    $query2 = mysqli_query($con, $sql2);
                                    if (mysqli_num_rows($query2) > 0) {
                                    
                                        echo '    <a href="#" class="btn btn-warning mt-3 " data-toggle="modal"
                                    data-target="#confirmationModal" id="nextButton">Next</a> ';
                                    
                                        
                                    }
                                    ?>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <h4 class="text-bold">&nbsp;&nbsp;&nbsp;Price Details</h4>
                <hr>
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
                                echo '
                                <tr>
                        
                                    <td>' . $rows['qn'] . $rows['wg'] . '</td>
                                    <td>' . $rows['oprice'] . '</td>                                                                                      
                                    <td>' . $rows['pp'] . '</td>
                                    <td>' . $rows['gst'] . '</td>
                                    <td>' . $rows['discount'] . '</td>
                                    <td>' . $rows['total_stock'] . '</td>
                                    <td>' . $rows['s_status'] . '</td>
                                    <td>
                                        
                                    
                                        <a href="#"
                                            data-href="delete_pp1.php?id='.$rows['id'].'&menu_del='.$prd_id.'"
                                            data-toggle="modal" data-target="#confirm-delete"
                                            class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                            <i class="fa fa-trash-o" style="font-size:16px"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#editPriceModal" onclick="fet('.$rows['id']. ')" class="btn btn-primary btn-flat btn-addon btn-xs m-b-10 edit-price"><i class="fa fa-pencil" style="font-size:16px"></i>
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
                Are you sure want to delete this Price?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<style>
        #priceErrorMsg {
            display: none; 
            color: red; 
            margin-top: 10px; /* Add some margin for better visibility */
        }
    </style>

<div class="modal fade" id="editPriceModal" tabindex="-1" role="dialog" aria-labelledby="editPriceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editPriceModalLabel">Update Price</h4>
            </div>
            <div class="modal-body">
                <form id="editPriceForm" action="edit_pp1.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="modal-id">
                    <input type="hidden" name="pcode" id="modal-pcode">
                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">MRP *</label>
                                    <input type="text" name="oprice" id="modal-oprice"
                                        class="form-control positive-number" placeholder="Actual MRP" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Volume *</label>
                                    <input type="text" name="qn" id="modal-qn" class="form-control positive-number"
                                        placeholder="Specific Volume" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Selling Price *</label>
                                    <input type="text" name="price" id="modal-price"
                                        class="form-control positive-number" placeholder="Selling Price" required>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Units *</label>
                                    <select name="weight" id="modal-weight" class="form-control "
                                        oninvalid="this.setCustomValidity('Please select a weight')"
                                        oninput="setCustomValidity('')">
                                        <?php
                                            $current_weight = $row['wg'];
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
                                    <select name="gst" id="modal-gst" class="form-control"
                                        oninvalid="this.setCustomValidity('Please select a GST value')"
                                        oninput="setCustomValidity('')">
                                        <option value="0">0</option>
                                        <option value="5">5</option>
                                        <option value="12">12</option>
                                        <option value="18">18</option>
                                        <option value="28">28</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Discount* <small>(%)</small></label>
                                    <input type="text" name="dis" id="modal-dis" class="form-control positive-number"
                                        placeholder="Discount in Percentage" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Available Quantity *</label>

                                    <input type="number" name="stock" min="1" id="modal-stock" class="form-control "
                                        placeholder="Available Quantity" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Stock* </label>
                                    <input type="text" name="s_status" id="modal-s_status" class="form-control" readonly>
                                  
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right">
                            <input type="submit" name="submit" id="submit-btn" class="btn btn-success" value="Update">
                         
                        </div>
                        <div id="priceErrorMsg"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('modal-price');
            const opriceInput = document.getElementById('modal-oprice');
            const submitButton = document.getElementById('submit-btn');
            const errorMessageDiv = document.getElementById('priceErrorMsg'); // Updated ID

            if (!priceInput || !opriceInput || !submitButton || !errorMessageDiv) {
                console.error('One or more elements are missing from the DOM.');
                return;
            }

            let errorDisplayed = false; // Flag to track if error message has been shown

            function validatePrices() {
                // Convert inputs to numbers
                const price = parseFloat(priceInput.value.trim()) || 0;
                const oprice = parseFloat(opriceInput.value.trim()) || 0;

                console.log('Price:', price);
                console.log('MRP:', oprice);

                // Validate if selling price is not greater than MRP
                if (price > oprice) {
                    if (!errorDisplayed) {
                        errorMessageDiv.textContent =
                            'Selling Price should not be greater than MRP.';
                        errorMessageDiv.style.display = 'block';
                        submitButton.disabled = true;
                        errorDisplayed = true; // Set flag to true after displaying error
                        console.log('Error message displayed.');
                    }
                } else {
                    if (errorDisplayed) {
                        errorMessageDiv.textContent = '';
                        errorMessageDiv.style.display = 'none';
                        submitButton.disabled = false;
                        errorDisplayed = false; // Reset flag when error is resolved
                        console.log('Error message hidden.');
                    }
                }
            }

            // Attach the validation function to input events
            priceInput.addEventListener('input', validatePrices);
            opriceInput.addEventListener('input', validatePrices);

            // Initial validation in case inputs are pre-filled
            validatePrices();
        });
    </script>

<div id="unique-confirmation-modal">
    <div class="unique-modal-content">
        <p>Are you sure you want to move to the next step?</p>
        <a href="#" id="unique-cancelButton" class="btn btn-danger">Cancel</a>
        <a href="#" id="unique-confirmNext" class="btn btn-success">Next</a>
    </div>
</div>


<div class="modal fade" id="uniqueConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Add Units</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action='add_price.php?prd_id=<?php echo $prd_code; ?>' method='post'
                    enctype='multipart/form-data'>
                    <div class="form-body">
                        <label for="c_name">Measurement Type:</label>
                        <input type="text" id="c_name" name="c_name" required>&nbsp;&nbsp;

                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="submit" class="btn btn-success" value="Add">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function hideMessages() {
    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        var successMessage = document.getElementById('success-message');
        if (errorMessage) errorMessage.classList.add('hidden');
        if (successMessage) successMessage.classList.add('hidden');
    }, 5000);
}


hideMessages();
</script>
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
                document.getElementById('modal-oprice').value = result.oprice;
                document.getElementById('modal-price').value = result.pp;
                document.getElementById('modal-qn').value = result.qn;
                document.getElementById('modal-weight').value = result.wg;
                document.getElementById('modal-gst').value = result.gst;
                document.getElementById('modal-stock').value = result.total_stock;
                document.getElementById('modal-dis').value = result.discount;
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


<!-- Optional: JavaScript to handle the button click and modal logic -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var nextButton = document.getElementById('nextButton');
    var cancelButton = document.getElementById('unique-cancelButton');
    var confirmationModal = document.getElementById('unique-confirmation-modal');
    var confirmNext = document.getElementById('unique-confirmNext');

    // Show modal
    nextButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default action of the link
        var prdId = <?php echo json_encode($prd_id); ?>;
        var nextUrl = 'p_description.php?prd_id=' + prdId; // Replace with the actual URL if needed
        confirmNext.href = nextUrl;
        confirmationModal.style.display = 'flex'; // Show the modal
    });

    // Hide modal on cancel
    cancelButton.addEventListener('click', function() {
        confirmationModal.style.display = 'none'; // Hide the modal
    });

    // Close modal when clicking outside of the modal content
    confirmationModal.addEventListener('click', function(event) {
        if (event.target === confirmationModal) {
            confirmationModal.style.display = 'none';
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('myForm');
    var mrpInput = document.getElementById('mrp');
    var sellingPriceInput = document.getElementById('sellingPrice');
    var mrpError = document.getElementById('mrpError');
    var sellingPriceError = document.getElementById('sellingPriceError');
    var submitButton = form.querySelector('input[type="submit"]');

    function validatePrices() {
        var mrp = parseFloat(mrpInput.value) || 0;
        var sellingPrice = parseFloat(sellingPriceInput.value) || 0;

        var isValid = true;

        if (sellingPrice > mrp) {
            sellingPriceError.textContent = 'Selling Price cannot be greater than MRP.';
            isValid = false;
        } else {
            sellingPriceError.textContent = '';
        }

        // Enable or disable the submit button based on validation result
        submitButton.disabled = !isValid;
    }

    mrpInput.addEventListener('input', validatePrices);
    sellingPriceInput.addEventListener('input', validatePrices);

    // Initial validation to set the correct state of the submit button
    validatePrices();
});
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>
.error-message {
    color: red;
    /* font-size: 0.875em; */
}
</style>
<style>
.alert {
    padding: 15px;
    margin: 15px 0;
    border-radius: 4px;
    color: white;
}

.alert-error {
    background-color: #dc3545;
    /* Red */
}

.alert-success {
    background-color: #28a745;
    /* Green */
}

.alert.hidden {
    display: none;
}
</style>

<style>
/* Custom modal styles */
#unique-confirmation-modal {
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

.unique-modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
    /* Adjust as needed */
}

.unique-modal-content p {
    margin-bottom: 20px;
}

.unique-modal-content button,
.unique-modal-content a {
    padding: 10px 20px;
    margin: 0 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    /* //font-size: 16px; */
}

.btn-success {
    background-color: #28a745;
    color: white;
    text-decoration: none;
    /* Remove underline from links */
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    text-decoration: none;
    /* Remove underline from links */
}

.btn-success:hover {
    background-color: #218838;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>

<?php require_once('footer.php'); ?>