<!DOCTYPE html>
<html lang="en">
<?php include "head.php"; ?>

<body class="fix-header">
    <div id="main-wrapper">
        <!-- header header  -->
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper" style="height:1200px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Shipping</h4>
                                </div>
                                <div class="card-body">
                                    <form id="country_find">
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="cityInput">Enter Country Name:</label>
                                                <input type="text" onkeydown="MK_Characters_Only('cityInput')"
                                                    class="form-control" id="cityInput" name="city">
                                            </div>
                                            <div class="col-1 d-flex justify-content-center align-items-center">
                                                <label class="mt-4" for="pinCodeInput">Or</label>
                                            </div>
                                            <div class="col-3">
                                                <label for="pinCodeInput">Enter PIN Code:</label>
                                                <input type="text" onkeydown="MK_Number_Only_1('pinCodeInput')"
                                                    class="form-control" id="pinCodeInput" name="pinCode" maxlength="6">
                                            </div>
                                            <div class="col-3">
                                                <button type="button" class="btn btn-primary mt-4"
                                                    style="margin-top:30px !important;"
                                                    onclick="searchResults_find()">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-center" id="dataTable" width="100%"
                                        cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Pin code</th>
                                                <th>Country</th>
                                                <th>State</th>
                                                <th>Sub state</th>
                                                <th>Region</th>
                                                <th>City</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="body_results" class="text-center">
                                            <?php
                                  $sql="SELECT * FROM pin ";
                                  $query=mysqli_query($con,$sql);
                                 	if(!mysqli_num_rows($query) > 0 ){
                              			echo '<td colspan="11"><center>No Shipping-Data!</center></td>';
                              		}else{				
                              			while($rows=mysqli_fetch_array($query)){
                                        echo '<tr>
                                            <td>'.$rows['pin'].'</td>
                                            <td>'.$rows['taluk'].'</td>
                                            <td>'.$rows['state'].'</td>
                                            <td>'.$rows['dist'].'</td>
                                            <td>'.$rows['circle'].'</td>
                                            <td>'.$rows['division'].'</td>
                                            <td>'.$rows['price'].'</td>
                                            </tr>';
                                        }}
                                ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    function searchResults_find() {
                        let form = document.getElementById('country_find');
                        let city = form.elements['city'].value; // Get city value
                        let pinCode = form.elements['pinCode'].value; // Get pinCode value

                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', 'price_rates.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                let response = JSON.parse(xhr.responseText);
                                let body_results = document.getElementById('body_results');
                                if (response.status) {
                                    body_results.innerHTML = response.results;
                                } else {
                                    body_results.innerHTML = 'No data found';
                                }
                            } else {
                                console.error('Request failed. Error code: ' + xhr.status);
                            }
                        };

                        xhr.onerror = function() {
                            console.error('Request failed. Network error.');
                        };

                        xhr.send('city=' + encodeURIComponent(city) + '&pinCode=' + encodeURIComponent(pinCode));
                    }

                    function MK_Number_Only_1(id_name) {
                        let values = document.getElementById(id_name);
                        if (values.value.charAt(0) === '0') {
                            values.value = '';
                        }

                        values.value = values.value.replace(/[^0-9.]/g, "").replace(/(\..*?)\..*/g, "$1");
                    }

                    function MK_Characters_Only(id_name) {
                        let nameInput = document.getElementById(id_name);

                        nameInput.addEventListener('input', function() {
                            let inputValue = nameInput.value.trim();
                            let validChars = inputValue.replace(/[^A-Za-z]/g, '');

                            if (inputValue !== validChars) {
                                nameInput.value = validChars;
                            }

                            if (validChars === '') {
                                nameInput.setCustomValidity('Please enter alphabetic characters');
                                nameInput.classList.add('is-invalid');
                            } else {
                                nameInput.setCustomValidity('');
                                nameInput.classList.remove('is-invalid');
                            }
                        });
                    }
                    </script>
                    <?php include('footer.php'); ?>
</body>

</html>