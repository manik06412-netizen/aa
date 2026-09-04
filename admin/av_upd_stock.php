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
error_reporting(0);
?>
<?php require_once('header.php'); ?>
<link rel="stylesheet" href="./css/loader.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
    integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<style>
.bg-prim {
    background-color: rgb(146, 187, 211) !important;
}
</style>

<section class="content-header">
    <div class="content-header-left">
        <h1>Category Details</h1>
    </div>
    <div class="content-header-right">
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:17%">Product Name</th>
                                <th style="width:15%">Measurement with quantity</th>
                                <th style="width:15%">Adjust</th>
                                <th style="width:12%">Current Status</th>
                                <th style="width:12%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $productid=$_GET['prd_id'];
    $i = 0;
    // SQL Query to fetch the data
    $sql = "SELECT 
            dishes.*, 
            prd_stock.*, 
            price.* 
        FROM 
            dishes 
        INNER JOIN 
            prd_stock ON dishes.rs_id = prd_stock.pd_code 
        INNER JOIN 
            price ON prd_stock.price_id = price.id
        INNER JOIN (
            SELECT 
                price_id, 
                MAX(id) AS max_id
            FROM 
                prd_stock
            GROUP BY 
                price_id
        ) latest_price ON prd_stock.id = latest_price.max_id
        where dishes.rs_id='$productid'
       ";
    $query = mysqli_query($con, $sql);

    // Array to store data grouped by dish ID
    $products = [];
    
    // Fetch the results and group them
    while ($rows = mysqli_fetch_assoc($query)) {
        $d_id = $rows['rs_id'];
        if (!isset($products[$d_id])) {
            // Initialize array for a new product
            $products[$d_id] = [
                'dish_name' => $rows['dish_name'],
                'img' => $rows['img'],
                'status' => $rows['status'],
                'measurements' => []
            ];
        }
        
        // Add measurement details
        $products[$d_id]['measurements'][] = [
            'quantity' => $rows['qn'],
            'weight' => $rows['wg'],
            'stock' => $rows['total']
        ];
    }

    // Output the data
    foreach ($products as $d_id => $product) {
        $statusText = ($product['status'] == 1) ? 'Active' : (($product['status'] == 2) ? 'Inactive' : 'Scheduled');
        $editLink = $d_id;
        $statusChangeLink = 'products_status.php?status_id=' . htmlspecialchars($d_id);

        echo '<tr>
            <td>
                <center><img src="' . htmlspecialchars($product['img']) . '" class="img-responsive radius" style="height:40px;width:40px;" /></center><br/>
                <b>' . htmlspecialchars($product['dish_name']) . '</b>
            </td>
            <td>';
        
        // Display measurements
        foreach ($product['measurements'] as $measurement) {
            echo $measurement['quantity'] . $measurement['weight'] . " - " . $measurement['stock'] . '<br />';
        }

        echo '</td>
            <td>
             <a href="#" data-toggle="modal" data-target="#editPriceModal" onclick="fet('. $d_id. ')" class="btn btn-primary btn-flat btn-addon btn-xs m-b-10 edit-price">Add</a>

  <a href="#" data-toggle="modal" data-target="#reducepriceModal" onclick="red('. $d_id. ')" class="btn btn-warning btn-flat btn-addon btn-xs m-b-10 edit-price">Reduce</a>

  <a href="#" data-toggle="modal" data-target="#editPriceModal3" onclick="fetchStockData('. $d_id. ')" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10 edit-price">Set</a>
          
               
                
            </td>
            <td><h6>' . $statusText . '</h6></td>
            <td>
                <div class="row">

                    <div class="col-md-10">
                          <a href="#" data-toggle="modal" data-target="#stockupdateModal" onclick="stc('.$d_id.')" class="btn btn-success btn-flat btn-addon btn-xs btn-sm m-b-10">Status Update</a>
                    </div>
                </div>
            </td>
        </tr>';
    }
    ?>
                        </tbody>

                    </table>

                </div>
            </div>

</section>

<script>
$('#example1').DataTable({
    "order": [],
    "columnDefs": [{
        "orderable": false,
        "targets": "_all"
    }]
});
</script>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this Product?
            </div>
            <div class="modal-footer">
                <input type="hidden" id="delete_id">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a onclick="Submit_delete()" class="btn btn-danger btn-ok">Delete</a>
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
                <h4 class="modal-title" id="editPriceModalLabel">Add Stock</h4>
            </div>
            <div class="modal-body">
                <form id="editPriceForm" action="edit_pp1.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="id" id="modal-id" style="display:none;"> <!-- Hidden input to store id -->

                    <div class="form-body">
                        <!-- Content will be dynamically added here by JavaScript -->
                    </div>
                </form>

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="editPriceModal3" tabindex="-1" role="dialog" aria-labelledby="editPriceModalLabel3"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editPriceModalLabel3">Set Stock</h4>
            </div>
            <div class="modal-body">
                <form id="editPriceForm3" action="edit_pp1.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="id" id="modal-id1" style="display:none;"> <!-- Hidden input to store id -->

                    <div class="form-body">
                       

                    </div>
                </form>

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reducepriceModal" tabindex="-1" role="dialog" aria-labelledby="reducepriceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="reducepriceModalLabel">Reduce Stock</h4>
            </div>
            <div class="modal-body">
                <form id="reducepriceForm" action="edit_pp1.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="id" id="modal-red" style="display:none;"> <!-- Hidden input to store id -->

                    <div class="form-body">
                        <!-- Content will be dynamically added here by JavaScript -->
                    </div>
                </form>

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade " id="stockupdateModal" tabindex="-1" role="dialog" aria-labelledby="stockupdateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="stockupdateModalLabel">Update Stock</h4>
            </div>
            <form id="stockupdateForm" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="modal-id2">
                <div class="form-body pt-5">
                </div>
            </form>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<?php require_once('footer.php'); ?>


<script>
function fet(id_name) {
    let data = new FormData();
    data.append('id', id_name);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_stock.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
                // Handle the error as needed
            } else {
                // Update the form's hidden input with the fetched id
                document.getElementById('modal-id').value = result.pd_code;

                // Trigger the second AJAX call to fetch detailed data
                updateForm(result.pd_code);
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

// Function to fetch and update the form with detailed data
function updateForm(pd_code) {
    let data = new FormData();
    data.append('pd_code', pd_code);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_dishes.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
            } else {
                let formBody = document.querySelector('#editPriceForm .form-body');
                formBody.innerHTML = '';

                // Create a map to store dishes by rs_id
                let dishMap = new Map();

                result.forEach(item => {
                    if (!dishMap.has(item.rs_id)) {
                        // Add a new entry for this dish
                        dishMap.set(item.rs_id, {
                            img: item.img,
                            dish_name: item.dish_name,
                            qn: [],
                            wg: [],
                            new_stock: [],
                            pd_code: [],
                            price_id: [],
                            total: [],
                            stk_status: []
                        });
                    }

                    // Add quantity-related info
                    let dishEntry = dishMap.get(item.rs_id);
                    dishEntry.qn.push(item.qn);
                    dishEntry.wg.push(item.wg);
                    dishEntry.new_stock.push(item.new_stock);
                    dishEntry.price_id.push(item.price_id);
                    dishEntry.pd_code.push(item.pd_code);
                    dishEntry.total.push(item.total);
                    dishEntry.stk_status.push(item.stk_status);
                });

                // Render each dish
                dishMap.forEach((dish, rs_id) => {
                    formBody.innerHTML += `
                        <div class="row">
                            <div class="col-lg-2">
                                <img src="${dish.img}" width="100px" height="100px">
                            </div>
                            <div class="col-lg-10">
                                <h6># ${rs_id}</h6>
                                <h3>${dish.dish_name}</h3>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Quantity</th>
                                    <th>Measurement</th>
                                    <th>Current Stock</th>
                                    <th>Add new</th>
                                    <th>Add</th>
                                </tr>
                                ${dish.qn.map((qn, index) => `
                                    <tr>
                                        <td>${qn}</td>
                                        <td>${dish.wg[index]}</td>
                                        <td>${dish.total[index]}</td>
                                        <td>
                                            <input type="text" name="new_stock" id="new_stock_${index}" required>
                                            <input type="hidden" name="prd_code" value="${dish.pd_code[index]}">
                                            <input type="hidden" name="price_id" value="${dish.price_id[index]}">
                                            <input type="hidden" name="prev_total" value="${dish.total[index]}">
                                            <input type="hidden" name="status" value="${dish.stk_status[index]}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" onclick="submitRow(${index}, '${dish.pd_code[index]}', '${dish.price_id[index]}', '${dish.total[index]}', '${dish.stk_status[index]}')">Add</button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </table>
                        </div>
                    `;
                });
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

function submitRow(index, pd_code, price_id, prev_total, status) {
    let newStockInput = document.getElementById(`new_stock_${index}`);
    let newStockValue = newStockInput.value.trim();

    if (newStockValue === '') {
        toastr.error('Please enter the new stock value.', 'Error');
        return;
    }

    let formData = new FormData();
    formData.append('prd_code', pd_code);
    formData.append('price_id', price_id);
    formData.append('prev_total', prev_total);
    formData.append('status', status);
    formData.append('new_stock', newStockValue);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_stock.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let response = JSON.parse(xhr.responseText);
            if (response.error) {
                console.error(response.error);
            } else {
                toastr.success('Stock added successfully', 'Succcess');
                setTimeout(function() {
                            location.reload();
                        }, 2000);
            }
        } else {
            console.error('Request failed with status: ' + xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error('Request error...');
    };

    xhr.send(formData);
}
</script>

<script>
function fetchStockData(id_name) {
    let data = new FormData();
    data.append('id', id_name);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_stock.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
                // Handle the error as needed
            } else {
                // Update the form's hidden input with the fetched id
                document.getElementById('modal-id1').value = result.pd_code;

                // Trigger the second AJAX call to fetch detailed data
                populateFormWithDishes(result.pd_code);
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

// Function to fetch and update the form with detailed data
function populateFormWithDishes(pd_code) {
    let data = new FormData();
    data.append('pd_code', pd_code);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_dishes.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
            } else {
                let formBody = document.querySelector('#editPriceForm3 .form-body');
                formBody.innerHTML = '';

                // Create a map to store dishes by rs_id
                let dishMap = new Map();

                result.forEach(item => {
                    if (!dishMap.has(item.rs_id)) {
                        // Add a new entry for this dish
                        dishMap.set(item.rs_id, {
                            img: item.img,
                            dish_name: item.dish_name,
                            qn: [],
                            wg: [],
                            new_stock: [],
                            pd_code: [],
                            price_id: [],
                            total: [],
                            stk_status: []
                        });
                    }

                    // Add quantity-related info
                    let dishEntry = dishMap.get(item.rs_id);
                    dishEntry.qn.push(item.qn);
                    dishEntry.wg.push(item.wg);
                    dishEntry.new_stock.push(item.new_stock);
                    dishEntry.price_id.push(item.price_id);
                    dishEntry.pd_code.push(item.pd_code);
                    dishEntry.total.push(item.total);
                    dishEntry.stk_status.push(item.stk_status);
                });

                // Render each dish
                dishMap.forEach((dish, rs_id) => {
                    formBody.innerHTML += `
                        <div class="row">
                            <div class="col-lg-2">
                                <img src="${dish.img}" width="100px" height="100px">
                            </div>
                            <div class="col-lg-10">
                                <h6># ${rs_id}</h6>
                                <h3>${dish.dish_name}</h3>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Quantity</th>
                                    <th>Measurement</th>
                                    <th>Current Stock</th>
                                    <th>set stock</th>
                                    <th>Set</th>
                                </tr>
                                ${dish.qn.map((qn, index) => `
                                    <tr>
                                        <td>${qn}</td>
                                        <td>${dish.wg[index]}</td>
                                        <td>${dish.total[index]}</td>
                                        <td>
                                            <input type="text" name="new_stock" id="new_stock_${index}" required>
                                            <input type="hidden" name="prd_code" value="${dish.pd_code[index]}">
                                            <input type="hidden" name="price_id" value="${dish.price_id[index]}">
                                            <input type="hidden" name="prev_total" value="${dish.total[index]}">
                                            <input type="hidden" name="status" value="${dish.stk_status[index]}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning" onclick="addStock(${index}, '${dish.pd_code[index]}', '${dish.price_id[index]}', '${dish.total[index]}', '${dish.stk_status[index]}')">Add</button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </table>
                        </div>
                    `;
                });
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

function addStock(index, pd_code, price_id, prev_total, status) {
    let newStockInput = document.getElementById(`new_stock_${index}`);
    let newStockValue = newStockInput.value.trim();

    if (newStockValue === '') {
        toastr.error('Please enter the new stock value.', 'Error');
        
        return;
    }

    let formData = new FormData();
    formData.append('prd_code', pd_code);
    formData.append('price_id', price_id);
    formData.append('prev_total', prev_total);
    formData.append('status', status);
    formData.append('new_stock', newStockValue);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'set_stock.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let response = JSON.parse(xhr.responseText);
            if (response.error) {
                console.error(response.error);
            } else {
                toastr.success('Stock fixed successfully..', 'Succcess');
                setTimeout(function() {
                            location.reload();
                        }, 2000);
            }
        } else {
            console.error('Request failed with status: ' + xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error('Request error...');
    };

    xhr.send(formData);
}

function red(id_name) {
    let data = new FormData();
    data.append('id', id_name);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_stock.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
                // Handle the error as needed
            } else {
                // Update the form's hidden input with the fetched id
                document.getElementById('modal-red').value = result.pd_code;

                // Trigger the second AJAX call to fetch detailed data
                updateForm1(result.pd_code);
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

function updateForm1(pd_code) {
    let data = new FormData();
    data.append('pd_code', pd_code);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_dishes.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
            } else {
                let formBody = document.querySelector('#reducepriceForm .form-body');
                formBody.innerHTML = '';

                let dishMap = new Map();

                result.forEach(item => {
                    if (!dishMap.has(item.rs_id)) {
                        // Add a new entry for this dish
                        dishMap.set(item.rs_id, {
                            img: item.img,
                            dish_name: item.dish_name,
                            qn: [],
                            wg: [],
                            reduce_stock: [],
                            reason: [],
                            pd_code: [],
                            price_id: [],
                            total: [],
                            stk_status: []
                        });
                    }
                    let dishEntry = dishMap.get(item.rs_id);
                    dishEntry.qn.push(item.qn);
                    dishEntry.wg.push(item.wg);
                    dishEntry.reduce_stock.push(item.reduce_stock);
                    dishEntry.price_id.push(item.price_id);
                    dishEntry.reason.push(item.reason);
                    dishEntry.pd_code.push(item.pd_code);
                    dishEntry.total.push(item.total);
                    dishEntry.stk_status.push(item.stk_status);
                });

                // Render each dish
                dishMap.forEach((dish, rs_id) => {
                    formBody.innerHTML += `
                        <div class="row">
                            <div class="col-lg-2">
                                <img src="${dish.img}" width="80px" height="80px">
                            </div>
                            <div class="col-lg-10">
                                <h6># ${rs_id}</h6>
                                <h3>${dish.dish_name}</h3>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>Measurement</th>
                                        <th>Current Stock</th>
                                        <th>Reason</th>
                                        <th>Reduce Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${dish.qn.map((qn, index) => `
                                        <tr>
                                            <td>${qn}</td>
                                            <td>${dish.wg[index]}</td>
                                            <td>${dish.total[index]}</td>
                                            <td><input type="text" name="reason" id="reason_${index}" required></td>
                                            <td>
                                                <input type="text" name="new_stock" id="reduce_stock_${index}" required>
                                                <input type="hidden" name="prd_code" value="${dish.pd_code[index]}">
                                                <input type="hidden" name="price_id" value="${dish.price_id[index]}">
                                                <input type="hidden" name="prev_total" value="${dish.total[index]}">
                                                <input type="hidden" name="status" value="${dish.stk_status[index]}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning" onclick="submitRow1(${index}, '${dish.pd_code[index]}', '${dish.price_id[index]}', '${dish.total[index]}', '${dish.stk_status[index]}')">Reduce</button>
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                });
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

function submitRow1(index, pd_code, price_id, prev_total, status) {
    let newStockInput = document.getElementById(`reduce_stock_${index}`);
    let reasonInput = document.getElementById(`reason_${index}`);
    let reason = reasonInput.value.trim();
    let reduce_stock = newStockInput.value.trim();

    if (reduce_stock === '') {
        toastr.error('Please enter the new stock value.', 'Error');
        
        return;
    }
    if (reason === '') {
        toastr.error('Please enter the reason.', 'Error');

        return;
    }

    let formData = new FormData();
    formData.append('prd_code', pd_code);
    formData.append('price_id', price_id);
    formData.append('prev_total', prev_total);
    formData.append('status', status);
    formData.append('reason', reason);
    formData.append('reduce_stock', reduce_stock);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_stock1.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let response = JSON.parse(xhr.responseText);
            if (response.error) {
                console.error(response.error);
                toastr.error('Reduced stock is greater than available stock.', 'Error');
               
            } else {
                toastr.success('Stock reduced successfully.', 'Succcess');
                setTimeout(function() {
                            location.reload();
                        }, 2000);
            }
        } else {
            console.error('Request failed with status: ' + xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error('Request error...');
    };

    xhr.send(formData);
}
function stc(id_name) {
    let data = new FormData();
    data.append('id', id_name);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_stock.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
            } else {
                document.getElementById('modal-id2').value = result.pd_code;
                updateForms(result.pd_code);
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

function updateForms(pd_code) {
    let data = new FormData();
    data.append('pd_code', pd_code);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_dishes.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            let result = JSON.parse(xhr.responseText);
            if (result.error) {
                console.error(result.error);
            } else {
                let formBody = document.querySelector('#stockupdateForm .form-body');
                formBody.innerHTML = '';

                formBody.innerHTML += `
                       <div class="row ">
                           <div class="col-lg-2">
                               <img src="${result[0].img}" width="100px" height="100px" style="margin-left:20px" alt="Product Image">
                           </div>
                           <div class="col-lg-10">
                               <h6># ${result[0].rs_id}</h6>
                               <h3>${result[0].dish_name}</h3>
                           </div>
                       </div>
                   `;

                formBody.innerHTML += `
                       <div class="col-lg-12">
                           <div class="box-body table-responsive">
                               <table id="example1" class="table text-center table-bordered table-hover table-striped">
                                   <thead>
                                       <tr>
                                           <th>Quantity</th>
                                           <th>Measurement</th>
                                           <th>Current Stock Status</th>
                                           <th>Action</th>
                                       </tr>
                                   </thead>
                                   <tbody id="priceDetailsTableBody"></tbody>
                               </table>
                           </div>
                       </div>
                   `;

                let priceDetailsTableBody = document.getElementById('priceDetailsTableBody');
                let i = 0;
                result.forEach(item => {
                    let status_of = '';
                    let stock_color = '';
                    i++;
                    if (item.stk_status == 'Currently Unavailable') {
                        status_of = 'In Stock';
                        stock_color = 'btn-success';
                    } else {
                        status_of = 'Currently Unavailable';
                        stock_color = 'btn-warning';
                    }
                    priceDetailsTableBody.innerHTML += `
                           <tr>
                               <td>${item.qn}</td>
                               <td>${item.wg}</td>
                               <td>
                               ${item.stk_status}
                               <input type="hidden" name="rs_id" id="rs_id${i}" value="${item.pd_code}">
                               <input type="hidden" name="price_id" id="price_id${i}" value="${item.price_id}">
                               <input type="hidden" name="prev_stock" id="prev_stock${i}" value="${item.prev_stock}">
                               <input type="hidden" name="new_stock" id="new_stock${i}" value="${item.new_stock}">
                               <input type="hidden" name="total" id="total${i}" value="${item.total}">
                               </td>   
                               <td> <button type="button" class="btn ${stock_color}" onclick="updateStockDetails(${item.pd_code}, ${item.price_id}, '${status_of}')" stock-status-btn>${status_of}</button></td>
                           </tr>
                       `;
                });

                //    document.querySelectorAll('.stock-action-btn').forEach(button => {
                //        button.addEventListener('click', function() {
                //            let currentStatus = this.innerText.trim();
                //            let newStatus = currentStatus === 'In Stock' ? 'Currently Unavailable' :
                //                'In Stock';
                //            this.innerText = newStatus;

                //            this.dataset.newStatus = newStatus;
                //        });
                //    });

                //    document.getElementById('saveChangesButton').addEventListener('click', function() {
                //        document.querySelectorAll('.stock-action-btn').forEach(button => {
                //            if (button.dataset.newStatus) {
                //                let rs_id = button.dataset.rs_id;
                //                let price_id = button.dataset.price;
                //                let prev_stock = button.dataset.prev;
                //                let new_stock = button.dataset.new;
                //                let total = button.dataset.total;
                //                let date = button.dataset.date;
                //                let note = button.dataset.note;
                //                let new_status = button.dataset.newStatus;

                //                updateStockRecord(rs_id, price_id, prev_stock, new_stock, total,
                //                    new_status, date, note);
                //            }
                //        });
                //    });
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
//    
function updateStockDetails(productId, priceId, statusOf) {
    const data = new FormData();
    data.append('product_id', productId);
    data.append('price_id', priceId);
    data.append('status_of', statusOf);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_stock_status.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const result = JSON.parse(xhr.responseText);
                switch (result.status) {
                    case 1:
                        toastr.success('Stock status updated successfully', 'Succcess');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                        break;
                    case 2:
                        toastr.error('Failed to update stock status', 'Error');
                        break;
                    case 3:
                        toastr.error('No matching record found', 'Error');
                        break;
                    default:
                    toastr.error('Unexpected response from server', 'Error');
                }
            } catch (e) {
                alert('Error parsing server response'+e);
            }
        } else {
            alert('Request failed with status ' + xhr.status);
        }
    };
    xhr.onerror = function() {
        alert('Request failed');
    };
    xhr.send(data);
}


function updateStockRecord(rs_id, price_id, prev_stock, new_stock, total, new_status, date, note) {
    let data = new FormData();
    data.append('rs_id', rs_id);
    data.append('price_id', price_id);
    data.append('prev_stock', prev_stock);
    data.append('new_stock', new_stock);
    data.append('total', total);
    data.append('new_status', new_status);
    data.append('date', date);
    data.append('note', note);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_stock_status.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            console.log('Stock status updated successfully');
        } else {
            console.error('Update failed with status: ' + xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error('Request error...');
    };

    xhr.send(data);
}
</script>