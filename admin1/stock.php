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
        <button class="btn btn-primary btn-xs" id="export_table">CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print</button>
        <button class="btn btn-primary btn-xs" id="download_pdf">PDF</button>
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
                                <th style="width:35%; text-align: left;">Product Name</th>
                                <th style="width:20%; text-align: left;">Qty</th>
                                <th style="width:20%">Adjust</th>
                                <th style="width:12%">Current Status</th>
                                <th style="width:13%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
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
            <td style="text-align: left; vertical-align: middle;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <img src="' . htmlspecialchars($product['img']) . '" class="radius" style="height:42px;width:42px;object-fit:cover;border-radius:8px;border: 1px solid #e2e8f0;flex-shrink:0;" onerror="this.onerror=null; this.src=\'Res_img/no_image.png\'"/>
                    <span style="font-weight:700;color:#1e293b;font-size:13.5px;">' . htmlspecialchars($product['dish_name']) . '</span>
                </div>
            </td>

            <td style="vertical-align: middle; text-align: left;">';
        
        // Display measurements
        foreach ($product['measurements'] as $measurement) {
            echo '<div style="margin-bottom:4px; font-size:13px; color:#475569;">' . htmlspecialchars($measurement['quantity'] . $measurement['weight']) . ' - <span class="badge-count" style="background:#eff6ff !important;color:#2563eb !important;border-color:#bfdbfe !important;padding:2px 8px;font-size:11px;border:1px solid;display:inline-flex;">' . $measurement['stock'] . '</span></div>';
        }

        echo '</td>
            <td style="vertical-align: middle;">
                <div style="display:flex; flex-direction:column; gap:6px; align-items:center;">
                    <a href="#" data-toggle="modal" data-target="#editPriceModal" onclick="fet('. $d_id. ')" class="btn btn-success btn-xs" style="width:120px; margin:0 !important; cursor:pointer;"><i class="fa fa-plus"></i> Add stock</a>
                    <a href="#" data-toggle="modal" data-target="#reducepriceModal" onclick="red('. $d_id. ')" class="btn btn-warning btn-xs" style="width:120px; margin:0 !important; cursor:pointer;"><i class="fa fa-minus"></i> Reduce stock</a>
                    <a href="#" data-toggle="modal" data-target="#editPriceModal3" onclick="fetchStockData('. $d_id. ')" class="btn btn-info btn-xs" style="width:120px; margin:0 !important; cursor:pointer;"><i class="fa fa-edit"></i> Set stock</a>
                </div>
            </td>
            <td style="vertical-align: middle;">
                <span class="status-badge ' . ($product['status'] == 1 ? 'status-active' : 'status-inactive') . '">
                    ' . $statusText . '
                </span>
            </td>
            <td style="vertical-align: middle;">
                <a href="#" data-toggle="modal" data-target="#stockupdateModal" onclick="stc('.$d_id.')" class="btn btn-danger btn-xs" style="cursor:pointer;"><i class="fa fa-ban"></i> Stop Selling</a>
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

document.addEventListener('DOMContentLoaded', function() {
    // Print Table
    const printBtn = document.getElementById('print_table');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }

    // PDF Download (triggers Print to Save as PDF)
    const pdfBtn = document.getElementById('download_pdf');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', function() {
            window.print();
        });
    }

    // CSV Export
    const csvBtn = document.getElementById('export_table');
    if (csvBtn) {
        csvBtn.addEventListener('click', function() {
            let csv = [];
            const rows = document.querySelectorAll("#example1 tr");
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll("td, th");
                // Don't export the Action column (last column)
                for (let j = 0; j < cols.length - 1; j++) {
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                    row.push('"' + text.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }
            let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
            let downloadLink = document.createElement("a");
            downloadLink.download = "stock_report.csv";
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        });
    }
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editPriceModalLabel">Add Stock</h4>
            </div>
            <div class="modal-body">
                <form id="editPriceForm" action="" method="post" enctype="multipart/form-data">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editPriceModalLabel3">Set Stock</h4>
            </div>
            <div class="modal-body">
                <form id="editPriceForm3" action="" method="post" enctype="multipart/form-data">
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
                <form id="reducepriceForm" action="" method="post" enctype="multipart/form-data">
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
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="stockupdateModalLabel">Stop Selling</h4>
            </div>
            <div class="modal-body">
                <form id="stockupdateForm" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="modal-id2">
                    <div class="form-body pt-5">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<?php require_once('footer.php'); ?>

<script>
function validateNumber(input) {
    // Remove non-numeric characters
    input.value = input.value.replace(/[^0-9]/g, '');
}
</script>

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
                        <div class="row" style="margin-bottom: 15px; align-items: center; display: flex;">
                            <div class="col-xs-8">
                                <h6 style="color:#64748b; font-weight:600; margin:0 0 4px 0;"># ${rs_id}</h6>
                                <h4 style="color:#0f172a; font-weight:700; margin:0; font-size:16px;">${dish.dish_name}</h4>
                            </div>
                            <div class="col-xs-4 text-right">
                                <img src="${dish.img}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;" onerror="this.onerror=null; this.src='Res_img/no_image.png';">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped" style="margin-bottom: 25px;">
                                    <thead>
                                        <tr style="background:#f8fafc; color:#475569;">
                                            <th style="font-weight:600; font-size:12px;">Qty</th>
                                            <th style="font-weight:600; font-size:12px;">Current Stock</th>
                                            <th style="font-weight:600; font-size:12px;">Add Quantity</th>
                                            <th style="font-weight:600; font-size:12px; width:90px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${dish.qn.map((qn, index) => `
                                            <tr>
                                                <td style="vertical-align: middle; font-weight:600; color:#1e293b;">${qn} - ${dish.wg[index]}</td>
                                                <td style="vertical-align: middle;"><span class="badge-count" style="background:#eff6ff !important; color:#2563eb !important; border-color:#bfdbfe !important; border:1px solid; padding:3px 8px; font-weight:600; border-radius:6px; font-size:11.5px; display:inline-block;">${dish.total[index]}</span></td>
                                                <td style="vertical-align: middle;">
                                                    <input type="text" name="new_stock" id="new_stock_${index}" class="form-control" placeholder="Qty" pattern="\d*" oninput="validateNumber(this)" style="height:34px; border-radius:6px; border:1px solid #cbd5e1; width:100%; font-size:13px;" required>
                                                    <input type="hidden" name="prd_code" value="${dish.pd_code[index]}">
                                                    <input type="hidden" name="price_id" value="${dish.price_id[index]}">
                                                    <input type="hidden" name="prev_total" value="${dish.total[index]}">
                                                    <input type="hidden" name="status" value="${dish.stk_status[index]}">
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <button type="button" class="btn btn-success btn-xs" onclick="submitRow(${index}, '${dish.pd_code[index]}', '${dish.price_id[index]}', '${dish.total[index]}', '${dish.stk_status[index]}')" style="height:34px; border-radius:6px; font-weight:600; padding:0 15px; margin:0 !important; cursor:pointer; width:100%; display:inline-flex; align-items:center; justify-content:center; background:#10b981 !important; border-color:#10b981 !important; color:#fff;">Add</button>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
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
    let newStockValue = newStockInput ? newStockInput.value.trim() : '';

    if (newStockValue === '' || isNaN(newStockValue) || Number(newStockValue) <= 0) {
        toastr.error('Please enter a valid stock quantity to add', 'Error');
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
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.error) {
                    toastr.error(response.error, 'Error');
                } else {
                    toastr.success('Stock added successfully!', 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1200);
                }
            } catch(e) {
                toastr.success('Stock added successfully!', 'Success');
                setTimeout(function() {
                    location.reload();
                }, 1200);
            }
        } else {
            toastr.error('Request failed with server status ' + xhr.status, 'Error');
        }
    };

    xhr.onerror = function() {
        toastr.error('Network request failed', 'Error');
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
                let formBody = document.querySelector('#editPriceForm3 .form-body') || document.querySelector('#setpriceForm .form-body');
                if (!formBody) {
                    console.error("Form body not found for set price");
                    return;
                }
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
                            set_stock: [],
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
                    dishEntry.set_stock.push(item.set_stock);
                    dishEntry.price_id.push(item.price_id);
                    dishEntry.pd_code.push(item.pd_code);
                    dishEntry.total.push(item.total);
                    dishEntry.stk_status.push(item.stk_status);
                });

                // Render each dish
                dishMap.forEach((dish, rs_id) => {
                    formBody.innerHTML += `
                        <div class="row" style="margin-bottom: 15px; align-items: center; display: flex;">
                            <div class="col-xs-8">
                                <h6 style="color:#64748b; font-weight:600; margin:0 0 4px 0;"># ${rs_id}</h6>
                                <h4 style="color:#0f172a; font-weight:700; margin:0; font-size:16px;">${dish.dish_name}</h4>
                            </div>
                            <div class="col-xs-4 text-right">
                                <img src="${dish.img}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;" onerror="this.onerror=null; this.src='Res_img/no_image.png';">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped" style="margin-bottom: 25px;">
                                    <thead>
                                        <tr style="background:#f8fafc; color:#475569;">
                                            <th style="font-weight:600; font-size:12px;">Qty</th>
                                            <th style="font-weight:600; font-size:12px;">Current Stock</th>
                                            <th style="font-weight:600; font-size:12px;">Set Stock</th>
                                            <th style="font-weight:600; font-size:12px; width:90px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${dish.qn.map((qn, index) => `
                                            <tr>
                                                <td style="vertical-align: middle; font-weight:600; color:#1e293b;">${qn} - ${dish.wg[index]}</td>
                                                <td style="vertical-align: middle;"><span class="badge-count" style="background:#eff6ff !important; color:#2563eb !important; border-color:#bfdbfe !important; border:1px solid; padding:3px 8px; font-weight:600; border-radius:6px; font-size:11.5px; display:inline-block;">${dish.total[index]}</span></td>
                                                <td style="vertical-align: middle;">
                                                    <input type="text" name="new_stock" id="set_stock_${index}" class="form-control" placeholder="Qty" pattern="\\d*" oninput="validateNumber(this)" style="height:34px; border-radius:6px; border:1px solid #cbd5e1; width:100%; font-size:13px;" required>
                                                    <input type="hidden" name="prd_code" value="${dish.pd_code[index]}">
                                                    <input type="hidden" name="price_id" value="${dish.price_id[index]}">
                                                    <input type="hidden" name="prev_total" value="${dish.total[index]}">
                                                    <input type="hidden" name="status" value="${dish.stk_status[index]}">
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <button type="button" class="btn btn-info btn-xs" onclick="addStock(${index}, '${dish.pd_code[index]}', '${dish.price_id[index]}', '${dish.total[index]}', '${dish.stk_status[index]}')" style="height:34px; border-radius:6px; font-weight:600; padding:0 15px; margin:0 !important; cursor:pointer; width:100%; display:inline-flex; align-items:center; justify-content:center; background:#0070F3 !important; border-color:#0070F3 !important; color:#fff;">Set</button>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
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
    let newStockInput = document.getElementById(`set_stock_${index}`) || document.getElementById(`new_stock_${index}`);
    let newStockValue = newStockInput ? newStockInput.value.trim() : '';

    if (newStockValue === '' || isNaN(newStockValue)) {
        toastr.error('Please enter the stock value to set', 'Error');
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
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.error) {
                    toastr.error(response.error, 'Error');
                } else {
                    toastr.success('Stock set successfully!', 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1200);
                }
            } catch(e) {
                toastr.success('Stock set successfully!', 'Success');
                setTimeout(function() {
                    location.reload();
                }, 1200);
            }
        } else {
            toastr.error('Request failed with status: ' + xhr.status, 'Error');
        }
    };

    xhr.onerror = function() {
        toastr.error('Network request failed', 'Error');
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
                        <div class="row" style="margin-bottom: 15px; align-items: center; display: flex;">
                            <div class="col-xs-8">
                                <h6 style="color:#64748b; font-weight:600; margin:0 0 4px 0;"># ${rs_id}</h6>
                                <h4 style="color:#0f172a; font-weight:700; margin:0; font-size:16px;">${dish.dish_name}</h4>
                            </div>
                            <div class="col-xs-4 text-right">
                                <img src="${dish.img}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;" onerror="this.onerror=null; this.src='Res_img/no_image.png';">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered table-striped" style="margin-bottom: 25px;">
                                    <thead>
                                        <tr style="background:#f8fafc; color:#475569;">
                                            <th style="font-weight:600; font-size:12px;">Qty</th>
                                            <th style="font-weight:600; font-size:12px;">Current Stock</th>
                                            <th style="font-weight:600; font-size:12px;">Reason</th>
                                            <th style="font-weight:600; font-size:12px;">Reduce Stock</th>
                                            <th style="font-weight:600; font-size:12px; width:90px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${dish.qn.map((qn, index) => `
                                            <tr>
                                                <td style="vertical-align: middle; font-weight:600; color:#1e293b;">${qn} - ${dish.wg[index]}</td>
                                                <td style="vertical-align: middle;"><span class="badge-count" style="background:#eff6ff !important; color:#2563eb !important; border-color:#bfdbfe !important; border:1px solid; padding:3px 8px; font-weight:600; border-radius:6px; font-size:11.5px; display:inline-block;">${dish.total[index]}</span></td>
                                                <td style="vertical-align: middle;">
                                                    <input type="text" name="reason" id="reason_${index}" class="form-control" placeholder="Reason" style="height:34px; border-radius:6px; border:1px solid #cbd5e1; width:100%; font-size:13px;" required>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <input type="text" name="new_stock" id="reduce_stock_${index}" class="form-control" placeholder="Qty" pattern="\\d*" oninput="validateNumber(this)" style="height:34px; border-radius:6px; border:1px solid #cbd5e1; width:100%; font-size:13px;" required>
                                                    <input type="hidden" name="prd_code" value="${dish.pd_code[index]}">
                                                    <input type="hidden" name="price_id" value="${dish.price_id[index]}">
                                                    <input type="hidden" name="prev_total" value="${dish.total[index]}">
                                                    <input type="hidden" name="status" value="${dish.stk_status[index]}">
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <button type="button" class="btn btn-warning btn-xs" onclick="submitRow1(${index}, '${dish.pd_code[index]}', '${dish.price_id[index]}', '${dish.total[index]}', '${dish.stk_status[index]}')" style="height:34px; border-radius:6px; font-weight:600; padding:0 15px; margin:0 !important; cursor:pointer; width:100%; display:inline-flex; align-items:center; justify-content:center; background:#ea580c !important; border-color:#ea580c !important; color:#fff;">Reduce</button>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
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
    let reason = reasonInput ? reasonInput.value.trim() : '';
    let reduce_stock = newStockInput ? newStockInput.value.trim() : '';

    if (reduce_stock === '' || isNaN(reduce_stock) || Number(reduce_stock) <= 0) {
        toastr.error('Please enter a valid stock value to reduce.', 'Error');
        return;
    }
    if (reason === '') {
        toastr.error('Please enter the reason for reducing stock.', 'Error');
        return;
    }

    let formData = new FormData();
    formData.append('prd_code', pd_code);
    formData.append('price_id', price_id);
    formData.append('prev_total', prev_total);
    formData.append('status', status);
    formData.append('reason', reason);
    formData.append('new_stock', reduce_stock);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'Reduce_stock.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                let response = JSON.parse(xhr.responseText);
                if (response.error) {
                    toastr.error(response.error, 'Error');
                } else {
                    toastr.success('Stock reduced successfully.', 'Success');
                    setTimeout(function() {
                        location.reload();
                    }, 1200);
                }
            } catch(e) {
                toastr.success('Stock reduced successfully.', 'Success');
                setTimeout(function() {
                    location.reload();
                }, 1200);
            }
        } else {
            toastr.error('Request failed with status: ' + xhr.status, 'Error');
        }
    };

    xhr.onerror = function() {
        toastr.error('Network request error', 'Error');
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
                          
                           <div class="col-lg-9">
                               <h6># ${result[0].rs_id}</h6>
                               <h3>${result[0].dish_name}</h3>
                           </div>
                            <div class="col-lg-3">
                               <img src="${result[0].img}" width="80px" height="80px" style="margin-left:20px" alt="Product Image">
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
                                           <th>Change Action</th>
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
                    let status_of_btn = '';
                    let stock_color = '';
                    let mystatus = '';
                    i++;
                    if (item.stk_status == 'Currently Unavailable') {
                        mystatus = 'Stop Selling';
                        status_of = 'Instock';
                        status_of_btn = 'Start selling';
                        stock_color = 'btn-success';
                    } else {
                        mystatus = 'Start Selling';
                        status_of = 'Currently Unavailable';
                        status_of_btn = 'Stop selling';
                        stock_color = 'btn-danger';
                    }
                    priceDetailsTableBody.innerHTML += `
                           <tr>
                               <td>${item.qn}</td>
                               <td>${item.wg}</td>
                               <td>
                               ${mystatus}
                               <input type="hidden" name="rs_id" id="rs_id${i}" value="${item.pd_code}">
                               <input type="hidden" name="price_id" id="price_id${i}" value="${item.price_id}">
                               <input type="hidden" name="prev_stock" id="prev_stock${i}" value="${item.prev_stock}">
                               <input type="hidden" name="new_stock" id="new_stock${i}" value="${item.new_stock}">
                               <input type="hidden" name="total" id="total${i}" value="${item.total}">
                               </td>   
                               <td> <button type="button" class="btn ${stock_color} btn-xs" onclick="updateStockDetails(${item.pd_code}, ${item.price_id}, '${status_of}')" stock-status-btn>${status_of_btn}</button></td>
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
                        toastr.success('Stock status updated successfully', 'Success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000); // 2000 milliseconds = 2 seconds; adjust as needed
                        break;
                       
                    case 2:
                        toastr.error('Failed to update stock status', 'Error');
                        //alert('Failed to update stock status');
                        break;
                    case 3:
                        toastr.error('No matching record found', 'Error');
                        //alert('No matching record found');
                        break;
                    default:
                        toastr.error('Unexpected response from server', 'Error');
                        //alert('Unexpected response from server');
                }
            } catch (e) {
                alert('Error parsing server response' + e);
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