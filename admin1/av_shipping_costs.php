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
require_once('header.php'); 
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Shipping Cost Details';
echo "<script>var sessionTitle = '$title';</script>";
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add Shipping Cost</h1>
    </div>
    <div class="content-header-right">
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info " style="padding:40px; display:flex; justify-content:center">
                <div class="card-body">
                    <label for="districtInput">Enter District Name:</label>
                    <input type="text" id="districtInput" name="city">
                    <label for="pinCodeInput">Or Enter PIN Code:</label>
                    <input type="text" id="pinCodeInput" name="pinCode" maxlength="6" required>
                    <button class="btn btn-success btn-sm" id="submitBtn">Submit</button>
                    <div id="result" style="margin-top:40px;"></div>

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
                            document.getElementById('result').innerHTML =
                                'Please enter a valid city name or PIN code.';
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
                            <th>Name</th>
                <th>Pincode</th>
               
                <th>District</th>
               
                
                <th>Price</th>
                            <th></th>
                        </tr>`;
                        postOffices.forEach(office => {
                            html += `<tr>
                               <td>${office.Name}</td>
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
                                const href =
                                    `save1.php?postOfficeId=${postOfficeId}&name=${encodeURIComponent(row.cells[0].textContent)}&price=${encodeURIComponent(price)}&district=${encodeURIComponent(row.cells[2].textContent)}&pincode=${encodeURIComponent(row.cells[1].textContent)}`;
                                window.location.href = href;
                            });
                        });
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <section class="content-header">
        <div class="content-header-left">
            <h1>View Shipping Cost</h1>
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
                        <table id="example1" class="table table-bordered text-center table-hover table-striped">
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
                                    FROM pinamount order by id asc";
                            $query = mysqli_query($con, $sql);
                            
                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="8"><center>No Shipping Data!</center></td></tr>';
                            } else {
                                while ($rows = mysqli_fetch_array($query)) {
                                    
                                    echo '<tr>
                                        
                                        <td>'.$rows['district'].'</td>
                                         <td>'.$rows['pincode'].'</td>
                                        <td>'.$rows['price'].'</td>
                                        <td>
                                        <a href="#"
                                              data-href="delete_pin.php?cat_del='.$rows['id'] .'"
                                              data-toggle="modal" data-target="#confirm-delete"
                                               class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                               <i class="fa fa-trash-o" style="font-size:16px"></i></a>
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
                Are you sure want to delete this Shipping Cost?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
<?php require_once('footer.php'); ?>