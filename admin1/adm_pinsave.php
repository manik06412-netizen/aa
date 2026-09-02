<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
?>
<?php
include("../dbconnect.php");
error_reporting(0);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php include "head.php"; ?>

<body class="fix-header fix-sidebar">

    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                        <div class="card-body">
    <h4 class="card-title">Add Pinamount</h4>
    <label for="districtInput">Enter District Name:</label>
    <input type="text" id="districtInput" name="city">
    <label for="pinCodeInput">Or Enter PIN Code:</label>
    <input type="text" id="pinCodeInput" name="pinCode" maxlength="6" required>
    <button id="submitBtn">Submit</button>
    <div id="result"></div>

    <script>
    document.getElementById('submitBtn').addEventListener('click', function() {
        const districtName = document.getElementById('districtInput').value;
        const pinCode = document.getElementById('pinCodeInput').value;

        if (districtName.length > 0 && pinCode.length === 0) {
            fetch(`https://api.postalpincode.in/postoffice/${encodeURIComponent(districtName)}`)
                .then(handleResponse)
                .catch(handleError);
        } else if (pinCode.length === 6) {
            fetch(`https://api.postalpincode.in/pincode/${pinCode}`)
                .then(handleResponse)
                .catch(handleError);
        } else {
            document.getElementById('result').innerHTML = 'Please enter a valid city name or PIN code.';
        }
    });

    function handleResponse(response) {
        response.json().then(data => {
            if (data[0].Status === 'Success') {
                const postOffices = data[0].PostOffice;
                const distinctPostOffices = getDistinctPostOffices(postOffices);
                displayResultInTable(distinctPostOffices);
            } else {
                document.getElementById('result').innerHTML = 'No records found';
            }
        });
    }

    function handleError(error) {
        console.error('Error:', error);
        document.getElementById('result').innerHTML = 'Failed to fetch data';
    }

    function getDistinctPostOffices(postOffices) {
        const seen = new Set();
        return postOffices.filter(office => {
            const isDuplicate = seen.has(office.Pincode);
            seen.add(office.Pincode);
            return !isDuplicate;
        });
    }

    function displayResultInTable(postOffices) {
    let html = `<table class="table table-bordered"><tr>
        <th>Pincode</th>
        <th>District</th>
        <th>Price</th>
        <th></th>
    </tr>`;
    postOffices.forEach(office => {
        html += `<tr>
            <td>${office.Pincode}</td>
            <td>${office.District}</td>
            <td><input type="text" class="priceInput" data-postofficeid="${office.PostOfficeId}" placeholder="Set Price" required/></td>
            <td><a href="#" class="btn btn-success saveBtn">Save</a></td>
        </tr>`;
    });
    html += `</table>`;
    document.getElementById('result').innerHTML = html;

    // Add event listeners to save buttons
    const saveButtons = document.querySelectorAll('.saveBtn');
    saveButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const row = this.parentNode.parentNode;
            const priceInput = row.querySelector('.priceInput');
            const price = priceInput.value;
            const postOfficeId = priceInput.dataset.postofficeid;
            const href = `save1.php?postOfficeId=${postOfficeId}&price=${encodeURIComponent(price)}&district=${encodeURIComponent(row.cells[1].textContent)}&pincode=${encodeURIComponent(row.cells[0].textContent)}`;
            window.location.href = href;
        });
    });
}

    </script>
</div>

                        </div>
                    </div>
                </div>

                <div class="table-responsive m-t-40">
                    <table id="example23" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                              
                                <th>District</th>
                               
                                <th>Pincode</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT DISTINCT id,pincode, district,  price 
                                    FROM pinamount";
                            $query = mysqli_query($con, $sql);
                            
                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="8"><center>No Shipping Data!</center></td></tr>';
                            } else {
                                while ($rows = mysqli_fetch_array($query)) {
                                    $status = $rows['status'];
                                    $buttonClass = ($status == 0) ? 'btn btn-success btn-flat btn-addon btn-sm m-b-10 m-l-5' : 'btn btn-danger btn-flat btn-addon btn-sm m-b-10 m-l-5';
                                    $buttonText = ($status == 0) ? 'Activate' : 'Inactivate';
                                    $buttonLink = 'update_pinstatus1.php?cat_upd=' . $rows['id'] . '&status=' . ($status == 0 ? '1' : '0');
                                    
                                    echo '<tr>
                                        
                                        <td>'.$rows['district'].'</td>
                                         <td>'.$rows['pincode'].'</td>
                                        <td>'.$rows['price'].'</td>
                                        <td>
                                            <a href="#" onclick="confirmDelete(' . $rows['id'] . ')" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                <i class="fa fa-trash-o" style="font-size:16px"></i>
                                            </a>
                                           
                                        </td>
                                    </tr>';
                                }
                            }
                            ?>
                             <script>
                                            function confirmDelete(categoryId) {
                                                var confirmDelete = confirm(
                                                    "Are you sure you want to delete this Pincode?");
                                                if (confirmDelete) {
                                                    window.location.href = 'delete_pin.php?cat_del=' + categoryId;
                                                } else {
                                                    // Do nothing or handle cancellation
                                                }
                                            }
                                            </script>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="footer"> All rights reserved. </footer>
    </div>

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
