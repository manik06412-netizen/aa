<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once APP_ROOT . '/views/layouts/header.php';

$user_id = $_SESSION['uid'] ?? '';
$email = $_SESSION['uemail'] ?? '';

// Fetch user data safely
$user_data = null;
if (!empty($user_id) || !empty($email)) {
    $uEsc = mysqli_real_escape_string($con, $user_id);
    $eEsc = mysqli_real_escape_string($con, $email);
    $q = mysqli_query($con, "SELECT * FROM user WHERE user_id='$uEsc' OR id='$uEsc' OR email='$eEsc' LIMIT 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $user_data = mysqli_fetch_assoc($q);
    }
}

$fname = $user_data['fname'] ?? ($_SESSION['uname'] ?? 'Guest');
$lname = $user_data['lname'] ?? '';
$eid   = $user_data['email'] ?? ($_SESSION['uemail'] ?? '');
$mob   = $user_data['mobile'] ?? '';
$active_tab = $_GET['tab'] ?? 'overview';
?>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <div class="sub_header_in">
            <div class="container text-center">
                <h1 class="text-white mb-2">My Account & Profile</h1>
                <p class="text-white-50 mb-0" style="font-size:14px;">Manage your personal information, security preferences, and order history.</p>
            </div>
        </div>

        <main style="padding: 40px 0 70px;">
            <div class="container">

                <!-- Alert Messages -->
                <?php if (isset($_SESSION['mgs'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px;font-weight:600;">
                    <i class="fa fa-check-circle mr-2"></i> <?php echo $_SESSION['mgs']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['mgs']); } ?>

                <?php if (isset($_SESSION['err_mgs'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px;font-weight:600;">
                    <i class="fa fa-exclamation-triangle mr-2"></i> <?php echo $_SESSION['err_mgs']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['err_mgs']); } ?>

                <div class="row">
                    
                    <!-- Left: Profile Sidebar Card -->
                    <div class="col-lg-4 mb-4">
                        <div style="background:#ffffff;border-radius:16px;border:1.5px solid #E2E8F0;padding:28px 20px;box-shadow:0 4px 20px rgba(11,25,44,0.06);text-align:center;">
                            <div style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg, #0D47A1, #00BCD4);color:#ffffff;display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;margin:0 auto 16px;box-shadow:0 6px 18px rgba(13,71,161,0.25);">
                                <?php echo strtoupper(substr($fname, 0, 1)); ?>
                            </div>
                            <h3 style="font-size:18px;font-weight:800;color:#0B192C;margin-bottom:4px;"><?php echo htmlspecialchars($fname . ' ' . $lname); ?></h3>
                            <p class="text-muted mb-3" style="font-size:13px;"><?php echo htmlspecialchars($eid ?: 'Customer'); ?></p>
                            
                            <div class="mb-4">
                                <span class="badge badge-pill badge-success px-3 py-1" style="font-size:11px;font-weight:700;">
                                    <i class="fa fa-check-circle mr-1"></i> Active Customer
                                </span>
                            </div>

                            <!-- Nav Tabs Sidebar -->
                            <div class="list-group" id="profileNavTabs" style="border-radius:12px;overflow:hidden;border:1px solid #E2E8F0;">
                                <a href="javascript:void(0);" onclick="switchTab('overview')" id="tab-btn-overview" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($active_tab === 'overview') ? 'active' : ''; ?>" style="font-weight:600;font-size:13.5px;padding:14px 18px;">
                                    <i class="fa fa-user mr-3" style="width:20px;"></i> Profile Overview
                                </a>
                                <a href="javascript:void(0);" onclick="switchTab('edit')" id="tab-btn-edit" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($active_tab === 'edit') ? 'active' : ''; ?>" style="font-weight:600;font-size:13.5px;padding:14px 18px;">
                                    <i class="fa fa-user-edit mr-3" style="width:20px;"></i> Edit Information
                                </a>
                                <a href="javascript:void(0);" onclick="switchTab('password')" id="tab-btn-password" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($active_tab === 'password') ? 'active' : ''; ?>" style="font-weight:600;font-size:13.5px;padding:14px 18px;">
                                    <i class="fa fa-key mr-3" style="width:20px;"></i> Change Password
                                </a>
                                <a href="myorders.php" class="list-group-item list-group-item-action d-flex align-items-center" style="font-weight:600;font-size:13.5px;padding:14px 18px;">
                                    <i class="fa fa-box mr-3" style="width:20px;"></i> My Orders
                                </a>
                                <a href="logout.php" class="list-group-item list-group-item-action d-flex align-items-center text-danger" style="font-weight:600;font-size:13.5px;padding:14px 18px;">
                                    <i class="fa fa-sign-out-alt mr-3" style="width:20px;"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Tab Contents -->
                    <div class="col-lg-8">
                        
                        <!-- TAB 1: Profile Overview -->
                        <div id="tab-content-overview" class="profile-tab-content" style="<?php echo ($active_tab === 'overview') ? '' : 'display:none;'; ?>">
                            <div style="background:#ffffff;border-radius:16px;border:1.5px solid #E2E8F0;padding:30px;box-shadow:0 4px 20px rgba(11,25,44,0.06);">
                                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                                    <h4 style="font-size:18px;font-weight:800;color:#0B192C;margin:0;">Personal Details</h4>
                                    <button onclick="switchTab('edit')" class="btn btn-sm btn-outline-primary" style="font-weight:700;border-radius:8px;">
                                        <i class="fa fa-edit mr-1"></i> Edit Info
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted" style="font-size:12px;font-weight:700;text-transform:uppercase;">First Name</label>
                                        <div style="font-size:15px;font-weight:700;color:#0F172A;"><?php echo htmlspecialchars($fname ?: '—'); ?></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted" style="font-size:12px;font-weight:700;text-transform:uppercase;">Last Name</label>
                                        <div style="font-size:15px;font-weight:700;color:#0F172A;"><?php echo htmlspecialchars($lname ?: '—'); ?></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted" style="font-size:12px;font-weight:700;text-transform:uppercase;">Email Address</label>
                                        <div style="font-size:15px;font-weight:700;color:#0F172A;"><?php echo htmlspecialchars($eid ?: '—'); ?></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted" style="font-size:12px;font-weight:700;text-transform:uppercase;">Contact Number</label>
                                        <div style="font-size:15px;font-weight:700;color:#0F172A;"><?php echo htmlspecialchars($mob ?: '—'); ?></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted" style="font-size:12px;font-weight:700;text-transform:uppercase;">Account Role</label>
                                        <div><span class="badge badge-info px-2 py-1">Customer Account</span></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted" style="font-size:12px;font-weight:700;text-transform:uppercase;">Member Since</label>
                                        <div style="font-size:14px;font-weight:600;color:#64748B;"><?php echo htmlspecialchars($user_data['date'] ?? '2026'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: Edit Information -->
                        <div id="tab-content-edit" class="profile-tab-content" style="<?php echo ($active_tab === 'edit') ? '' : 'display:none;'; ?>">
                            <div style="background:#ffffff;border-radius:16px;border:1.5px solid #E2E8F0;padding:30px;box-shadow:0 4px 20px rgba(11,25,44,0.06);">
                                <h4 style="font-size:18px;font-weight:800;color:#0B192C;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #E2E8F0;">
                                    Edit Personal Information
                                </h4>

                                <form action="edit1.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label style="font-size:13px;font-weight:700;color:#1E293B;">First Name *</label>
                                            <input type="text" name="fname1" class="form-control" value="<?php echo htmlspecialchars($fname); ?>" required style="height:46px;font-size:14px;border-radius:8px;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label style="font-size:13px;font-weight:700;color:#1E293B;">Last Name</label>
                                            <input type="text" name="lname1" class="form-control" value="<?php echo htmlspecialchars($lname); ?>" style="height:46px;font-size:14px;border-radius:8px;">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label style="font-size:13px;font-weight:700;color:#1E293B;">Email Address (Locked)</label>
                                            <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($eid); ?>" readonly style="height:46px;font-size:14px;border-radius:8px;">
                                            <small class="text-muted">Email address cannot be modified directly.</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label style="font-size:13px;font-weight:700;color:#1E293B;">Mobile Number *</label>
                                            <input type="tel" name="mobile1" class="form-control" value="<?php echo htmlspecialchars($mob); ?>" minlength="10" maxlength="10" pattern="^[6789][0-9]{9}$" placeholder="9876543210" required style="height:46px;font-size:14px;border-radius:8px;">
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" name="reg1" class="btn btn-primary px-4 py-2" style="background:linear-gradient(135deg, #0D47A1, #1976D2);border:none;border-radius:8px;font-weight:700;font-size:14px;">
                                            <i class="fa fa-save mr-2"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- TAB 3: Change Password -->
                        <div id="tab-content-password" class="profile-tab-content" style="<?php echo ($active_tab === 'password') ? '' : 'display:none;'; ?>">
                            <div style="background:#ffffff;border-radius:16px;border:1.5px solid #E2E8F0;padding:30px;box-shadow:0 4px 20px rgba(11,25,44,0.06);">
                                <h4 style="font-size:18px;font-weight:800;color:#0B192C;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #E2E8F0;">
                                    Update Password & Security
                                </h4>

                                <form action="edit1.php" method="POST">
                                    <div class="mb-3">
                                        <label style="font-size:13px;font-weight:700;color:#1E293B;">Current Password *</label>
                                        <input type="password" name="cur" class="form-control" placeholder="Enter current password" required style="height:46px;font-size:14px;border-radius:8px;">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label style="font-size:13px;font-weight:700;color:#1E293B;">New Password *</label>
                                            <input type="password" name="new" class="form-control" placeholder="Enter new password" minlength="6" required style="height:46px;font-size:14px;border-radius:8px;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label style="font-size:13px;font-weight:700;color:#1E293B;">Confirm New Password *</label>
                                            <input type="password" name="con" class="form-control" placeholder="Re-enter new password" minlength="6" required style="height:46px;font-size:14px;border-radius:8px;">
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" name="update_password" class="btn btn-primary px-4 py-2" style="background:linear-gradient(135deg, #0D47A1, #1976D2);border:none;border-radius:8px;font-weight:700;font-size:14px;">
                                            <i class="fa fa-lock mr-2"></i> Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>

        <footer id="footer">
            <?php require APP_ROOT . '/views/layouts/footer.php'; ?>
        </footer>
    </div>

    <?php require APP_ROOT . '/views/layouts/sign_footer.php'; ?>

    <script>
    function switchTab(tabId) {
        document.querySelectorAll('.profile-tab-content').forEach(function(el) {
            el.style.display = 'none';
        });
        document.querySelectorAll('#profileNavTabs .list-group-item').forEach(function(el) {
            el.classList.remove('active');
        });

        var content = document.getElementById('tab-content-' + tabId);
        var btn = document.getElementById('tab-btn-' + tabId);
        if (content) content.style.display = 'block';
        if (btn) btn.classList.add('active');
    }
    </script>
</body>
</html>