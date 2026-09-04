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
<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
    .nav {
        display: flex;
        list-style-type: none;
        padding: 0;
    }

    .nav li {
        margin-right: 15px;
    }

    .nav li a {
        text-decoration: none;
        color: #007bff;
    }

    .nav li a.active {
        font-weight: bold;
        color: #0056b3;
    }

    .section {
        display: none;
    }

    .section.active {
        display: block;
    }

    .card .img {
        max-height: 100px;
        max-width: 150px;
    }

    .image-card {
        position: relative;
        width: 100%;
        max-width: 300px;
        margin: auto;
    }

    .delete-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(255, 0, 0, 0.7);
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        z-index: 10;
    }

    .delete-btn:hover {
        background-color: rgba(255, 2, 0, 0.9);
    }
    </style>
    <?php
    include("../config.php");
    error_reporting(0);
    session_start();

    $query1 = "SELECT image FROM logo ORDER BY id DESC LIMIT 1";
    $result1 = mysqli_query($con, $query1);
    $logo = mysqli_fetch_assoc($result1)['image'];

    $query2 = "SELECT * FROM fevicon ORDER BY id DESC LIMIT 1";
    $result2 = mysqli_query($con, $query2);
    $fevicon = mysqli_fetch_assoc($result2)['fevicon'];
    $fevicon1 = mysqli_fetch_assoc($result2)['fname'];

    $query3 = "SELECT * FROM footer_contact ORDER BY id DESC LIMIT 1";
    $result3 = mysqli_query($con, $query3);
    $contact = mysqli_fetch_assoc($result3);

    $query4 = "SELECT * FROM about_us ";
    $result4 = mysqli_query($con, $query4);
    $about = mysqli_fetch_assoc($result4);


    ?>
    <?php include "head.php"; ?>
</head>

< class="fix-header fix-sidebar">
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Website Settings</h4>
                                <ul class="nav">
                                    <li><a href="#" data-target="logo-section" class="nav-link">Logo</a></li>
                                    <li><a href="#" data-target="fevicon-section" class="nav-link">Fevicon</a></li>
                                    <li><a href="#" data-target="contact-section" class="nav-link">Contact</a></li>
                                    <!-- <li><a href="#" data-target="about-section" class="nav-link">About</a></li>
    <li><a href="#" data-target="banner-section" class="nav-link">Banner</a></li> -->
                                    <li><a href="#" data-target="content-section" class="nav-link">Topbar</a></li>
                                </ul>

                                <div id="logo-section" class="section <?php echo $activeTab === 'logo-section' ? 'active' : ''; ?>">
                                    <form action='add_websettings.php' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Existing Logo</label>
                                                        <?php if ($logo): ?>
                                                        <img class="img" src="<?php echo $logo; ?>" alt="Existing Logo">
                                                        <?php else: ?>
                                                        <p>No logo available.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Upload logo (jpg, png)</label>
                                                        <input type="file" name="image" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <input type="submit" name="submit" class="btn btn-success"
                                                    value="Update">
                                                <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div id="fevicon-section" class="section <?php echo $activeTab === 'fevicon-section' ? 'active' : ''; ?>">
                                    <form action='add_websettings.php' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <input type="hidden" value="fevicon-section" name="tab_name">
                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Existing Fevicon</label>
                                                        <?php if ($fevicon): ?>
                                                        <img class="img" src="<?php echo $fevicon; ?>"
                                                            alt="Existing Fevicon">
                                                        <?php else: ?>
                                                        <p>No fevicon available.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Upload fevicon (jpg, png)</label>
                                                        <input type="file" name="fevicon" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <input type="submit" name="fevicon" class="btn btn-success"
                                                    value="Update">
                                                <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="contact-section" class="section <?php echo $activeTab === 'contact-section' ? 'active' : ''; ?>">
                                    <form action='add_websettings.php' method='post'>
                                        <div class="form-body">
                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="hidden" name="id"
                                                            value="<?php echo htmlspecialchars($contact['id']); ?>">
                                                        <label class="control-label">Copyrights</label>
                                                        <input type="text" name="copyrights" class="form-control"
                                                            rows="5"
                                                            value="<?php echo htmlspecialchars($contact['copyrights']); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Contact Address</label>
                                                        <textarea name="contact_address" class="form-control" rows="5"
                                                            required><?php echo htmlspecialchars($contact['contact_address']); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Contact Email</label>
                                                        <input type="text" name="contact_email" class="form-control"
                                                            rows="5"
                                                            value="<?php echo htmlspecialchars($contact['contact_email']); ?>"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Contact Phone Number</label>
                                                        <input type="tel" class="form-control"
                                                            placeholder="Enter Your mobile number"
                                                            value="<?php echo htmlspecialchars($contact['contact_phone']); ?>"
                                                            minlength="10" maxlength="10"
                                                            title="Phone number must start with 9, 8, 7, or 6 followed by 9 digits"
                                                            oninput="phoneNumberValidation(this, 'contactPhoneError')"
                                                            pattern="^[7896][0-9]{9}$" required name="contact_phone" />
                                                        <small id="contactPhoneError" class="text-danger"
                                                            style="display:none;">Please enter digits only.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Alternate Number</label>
                                                        <input type="tel" class="form-control"
                                                            placeholder="Enter Your mobile number"
                                                            value="<?php echo htmlspecialchars($contact['alternate_number']); ?>"
                                                            minlength="10" maxlength="10"
                                                            title="Phone number must start with 9, 8, 7, or 6 followed by 9 digits"
                                                            oninput="phoneNumberValidation(this, 'alternatePhoneError')"
                                                            pattern="^[7896][0-9]{9}$" required
                                                            name="alternate_number" />
                                                        <small id="alternatePhoneError" class="text-danger"
                                                            style="display:none;">Please enter digits only.</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Whatsapp Link</label>
                                                        <input type="tel" class="form-control"
                                                            placeholder="Enter Your mobile number"
                                                            value="<?php echo htmlspecialchars($contact['whatsapp']); ?>"
                                                            minlength="10" maxlength="10"
                                                            title="Phone number must start with 9, 8, 7, or 6 followed by 9 digits"
                                                            oninput="phoneNumberValidation(this, 'whatsappPhoneError')"
                                                            pattern="^[7896][0-9]{9}$" required name="whatsapp" />
                                                        <small id="whatsappPhoneError" class="text-danger"
                                                            style="display:none;">Please enter digits only.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Instagram Link</label>
                                                        <input type="text" name="instagram" class="form-control"
                                                            rows="5"
                                                            value="<?php echo htmlspecialchars($contact['instagram']); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Facebook Link</label>
                                                        <input type="text" name="facebook" class="form-control" rows="5"
                                                            value="<?php echo htmlspecialchars($contact['facebook']); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-t-20">

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Map Link</label>
                                                        <input type="text" name="contact_map" class="form-control"
                                                            rows="5"
                                                            value="<?php echo htmlspecialchars($contact['contact_map']); ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <input type="submit" name="contact" class="btn btn-success"
                                                    value="Update">
                                                <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="content-section" class="section <?php echo $activeTab === 'content-section' ? 'active' : ''; ?>">
                             
                                    <form action='add_websettings.php' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Add Text</label>
                                                        <input type="text" name="content" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <input type="submit" name="topbar" class="btn btn-success"
                                                    value="Upload">
                                                <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="card">
                                        <div class="row">

                                            <table class="table table-bordered  table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Product Name</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        include "../dbconnect.php";
                                                        $query6 = "select * from topbar";
                                                        $res6 = mysqli_query($con, $query6);
                                                        while ($row6 = mysqli_fetch_array($res6)) {
                                                        ?>
                                                    <td><?php echo $row6['id']; ?></td>
                                                    <td><?php echo $row6['content']; ?></td>
                                                    <td style="text-align:center;"><a
                                                            href="delete_image.php?tid=<?php echo $row6['id']; ?>"
                                                            class="btn btn-danger ">Delete</a></td>
                                                </tbody>
                                                <?php
                                                        }

                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <?php
    $tap_part = $_GET['tap_'];
    ?>
 <script>
$(document).ready(function() {
    // Phone Number Validation Function
    function phoneNumberValidation(input, errorId) {
        const phoneNumberPattern = /^[7896][0-9]{0,9}$/; // Allows partial valid numbers
        const errorMessage = document.getElementById(errorId);

        // Check for alphabetic characters
        const hasAlphabet = /[a-zA-Z]/.test(input.value);

        if (hasAlphabet) {
            errorMessage.textContent = 'Alphabetic characters are not allowed. Please enter digits only.';
            errorMessage.style.display = 'block';
            input.classList.add('is-invalid');
        } else {
            // Remove any non-digit characters (excluding alphabet check)
            input.value = input.value.replace(/\D/g, '');

            // Validate the input against the pattern
            if (!phoneNumberPattern.test(input.value) || input.value.length > 10) {
                errorMessage.textContent =
                    'Invalid phone number. Please enter a valid 10-digit phone number starting with 9, 8, 7, or 6.';
                errorMessage.style.display = 'block';
                input.classList.add('is-invalid');
            } else {
                errorMessage.style.display = 'none';
                input.classList.remove('is-invalid');
            }
        }
    }

    // Function to show the correct tab based on URL parameter
    function showTab(tabId) {
        $('.nav-link').removeClass('active');
        $('.section').removeClass('active').hide();

        $('#' + tabId).addClass('active').show();
        $('.nav-link[data-target="' + tabId + '"]').addClass('active');
        history.pushState(null, null, '?tab=' + tabId);
    }

    // On clicking a tab link
    $('.nav-link').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('target');
        showTab(target);
    });

    // Initial setup: show tab based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'logo-section';
    showTab(activeTab);

    // Hide sections initially except the one defined in the URL or default one
    $('.section').hide();
    $('#' + activeTab).show();

    // Handle delete modal setup
    $('#deleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var imageId = button.data('id');
        var modal = $(this);
        modal.find('#confirmDelete').data('id', imageId);
    });

    // Handle confirmation of deletion
    $('#confirmDelete').on('click', function() {
        var imageId = $(this).data('id');
        $.ajax({
            url: 'delete_image.php',
            method: 'POST',
            data: {
                id: imageId
            },
            success: function(response) {
                location.reload();
            }
        });
    });
});
</script>

    <footer class="footer"> © All rights reserved. </footer>
    <!-- End footer -->
    </div>
    <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
    <!-- All Jquery -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
</body>

</html>