<?php
/**
 * admin1/profile-edit.php — Fully working profile editor
 * Works for BOTH login sources: admin table + tbl_user table
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/inc/config.php';

if (!isset($_SESSION['admin1_user'])) {
    header('Location: login.php'); exit;
}

// ── Determine login source ──────────────────────────────────
$a1       = $_SESSION['admin1_user'];
$src      = $a1['role'] ?? 'admin';          // 'admin' or 'avadmin'
$is_tbluser = ($src === 'avadmin');           // tbl_user login
$uid      = $a1['id'] ?? 1;

$success_message = '';
$error_message   = '';

// Upload directory (relative to Karuda Computers_in root)
$upload_dir = __DIR__ . '/../assets/uploads/';
if (!is_dir($upload_dir)) {
    $upload_dir = __DIR__ . '/assets/uploads/';   // fallback
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
}
$upload_web = '../assets/uploads/';

// ── Fetch current data ──────────────────────────────────────
$full_name = ''; $email = ''; $phone = ''; $photo = ''; $role_label = '';

if ($is_tbluser) {
    // From tbl_user
    try {
        $st = $pdo->prepare("SELECT * FROM tbl_user WHERE id = ? LIMIT 1");
        $st->execute([$uid]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $full_name  = $row['full_name'] ?? '';
            $email      = $row['email']     ?? '';
            $phone      = $row['phone']     ?? '';
            $photo      = $row['photo']     ?? '';
            $role_label = $row['role']      ?? 'Admin';
        }
    } catch (Exception $e) {}
} else {
    // From admin table
    $esc = mysqli_real_escape_string($con, $uid);
    $r = mysqli_query($con, "SELECT * FROM admin WHERE adm_id = '$esc' LIMIT 1");
    if ($r && $row = mysqli_fetch_assoc($r)) {
        $full_name  = $row['username'] ?? '';
        $email      = $row['email']    ?? '';
        $phone      = $row['mobile']   ?? '';
        $photo      = '';
        $role_label = 'Administrator';
    }
}

// ── FORM 1: Update Info ────────────────────────────────────
if (isset($_POST['form1'])) {
    $new_name  = trim($_POST['full_name'] ?? '');
    $new_email = trim($_POST['email']     ?? '');
    $new_phone = trim($_POST['phone']     ?? '');

    if (empty($new_name))  { $error_message .= "Name cannot be empty.<br>"; }
    if (empty($new_email)) { $error_message .= "Email cannot be empty.<br>"; }
    elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) { $error_message .= "Valid email required.<br>"; }

    if (empty($error_message)) {
        if ($is_tbluser) {
            try {
                $st = $pdo->prepare("UPDATE tbl_user SET full_name=?, email=?, phone=? WHERE id=?");
                $st->execute([$new_name, $new_email, $new_phone, $uid]);
                $full_name = $new_name; $email = $new_email; $phone = $new_phone;
                $_SESSION['admin1_user']['full_name'] = $new_name;
                $_SESSION['admin1_user']['email']     = $new_email;
                $_SESSION['user']['full_name']        = $new_name;
                $_SESSION['flash_success'] = 'Profile information updated successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } catch (Exception $e) { $error_message = 'Update failed: ' . $e->getMessage(); }
        } else {
            // admin table
            $esc_name  = mysqli_real_escape_string($con, $new_name);
            $esc_email = mysqli_real_escape_string($con, $new_email);
            $esc_phone = mysqli_real_escape_string($con, $new_phone);
            $esc_uid   = mysqli_real_escape_string($con, $uid);
            mysqli_query($con, "UPDATE admin SET username='$esc_name', email='$esc_email', mobile='$esc_phone' WHERE adm_id='$esc_uid'");
            $full_name = $new_name; $email = $new_email; $phone = $new_phone;
            $_SESSION['admin1_user']['full_name'] = $new_name;
            $_SESSION['flash_success'] = 'Profile information updated successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
        }
    }
}

// ── FORM 2: Update Photo ───────────────────────────────────
if (isset($_POST['form2'])) {
    $file     = $_FILES['photo'] ?? null;
    $allowed  = ['jpg','jpeg','png','gif','webp'];

    if (!$file || empty($file['name'])) {
        $error_message = 'Please select a photo to upload.';
    } else {
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error_message = 'Only jpg, jpeg, png, gif, webp files allowed.';
        } elseif ($file['size'] > 3 * 1024 * 1024) {
            $error_message = 'Photo must be under 3 MB.';
        } else {
            $final_name = 'admin-' . $uid . '-' . time() . '.' . $ext;
            $dest = $upload_dir . $final_name;

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                // Delete old photo
                if (!empty($photo) && $photo !== 'no_image.png' && file_exists($upload_dir . $photo)) {
                    @unlink($upload_dir . $photo);
                }
                $photo = $final_name;
                // Update DB
                if ($is_tbluser) {
                    try {
                        $pdo->prepare("UPDATE tbl_user SET photo=? WHERE id=?")->execute([$final_name, $uid]);
                    } catch(Exception $e) {}
                }
                $_SESSION['admin1_user']['photo'] = $final_name;
                $_SESSION['user']['photo']        = $final_name;
                $_SESSION['flash_success'] = 'Photo updated successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } else {
                $error_message = 'Upload failed. Check folder permissions: ' . $upload_dir;
            }
        }
    }
}

// ── FORM 3: Change Password ────────────────────────────────
if (isset($_POST['form3'])) {
    $cur_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['password']         ?? '';
    $re_pass  = $_POST['re_password']      ?? '';

    if (empty($new_pass) || empty($re_pass)) {
        $error_message = 'Password fields cannot be empty.';
    } elseif (strlen($new_pass) < 6) {
        $error_message = 'Password must be at least 6 characters.';
    } elseif ($new_pass !== $re_pass) {
        $error_message = 'Passwords do not match.';
    } else {
        $hashed = md5($new_pass);
        if ($is_tbluser) {
            try {
                $pdo->prepare("UPDATE tbl_user SET password=? WHERE id=?")->execute([$hashed, $uid]);
                $_SESSION['flash_success'] = 'Password updated successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
            } catch(Exception $e) { $error_message = 'Failed: ' . $e->getMessage(); }
        } else {
            $esc_uid  = mysqli_real_escape_string($con, $uid);
            $esc_hash = mysqli_real_escape_string($con, $hashed);
            mysqli_query($con, "UPDATE admin SET password='$esc_hash' WHERE adm_id='$esc_uid'");
            $_SESSION['flash_success'] = 'Password updated successfully!';
        header('Location: ' . basename($_SERVER['PHP_SELF'])); exit;
        }
    }
}

require_once('header.php');
?>

<style>
/* ── Profile Page Styles ── */
.profile-wrap { max-width: 820px; margin: 0 auto; }
.profile-header-card {
    background: linear-gradient(135deg, #0d1117 0%, #1e293b 100%);
    border-radius: 20px;
    padding: 32px 36px;
    display: flex;
    align-items: center;
    gap: 28px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.profile-header-card::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(34,197,94,0.08);
    pointer-events: none;
}
.profile-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
.profile-avatar {
    width: 88px; height: 88px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,0.15);
    box-shadow: 0 8px 24px rgba(0,0,0,0.35);
    background: #1e293b;
}
.profile-avatar-badge {
    position: absolute; bottom: 2px; right: 2px;
    width: 22px; height: 22px;
    background: #22c55e;
    border-radius: 50%;
    border: 2px solid #0d1117;
    display: flex; align-items: center; justify-content: center;
}
.profile-meta h2 { color: #fff; font-size: 20px; font-weight: 700; margin: 0 0 4px; font-family: 'Outfit', sans-serif; }
.profile-meta .profile-email { color: #94a3b8; font-size: 13px; }
.profile-meta .profile-role-badge {
    display: inline-block;
    background: rgba(34,197,94,0.15);
    color: #22c55e;
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid rgba(34,197,94,0.3);
    margin-top: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
/* Tabs */
.profile-tabs { display: flex; gap: 4px; background: #f8fafc; padding: 5px; border-radius: 14px; margin-bottom: 20px; border: 1px solid #e8edf2; }
.profile-tab-btn {
    flex: 1; padding: 10px 16px; border: none;
    background: transparent; border-radius: 10px;
    font-size: 12.5px; font-weight: 600; color: #64748b;
    cursor: pointer; transition: all 0.2s ease;
    display: flex; align-items: center; justify-content: center; gap: 7px;
}
.profile-tab-btn.active { background: #fff; color: #0f172a; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.profile-tab-btn:hover:not(.active) { color: #374151; background: rgba(255,255,255,0.6); }
/* Tab panes */
.profile-pane { display: none; }
.profile-pane.active { display: block; }
/* Form card */
.pf-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8edf2;
    padding: 28px 32px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.pf-field { margin-bottom: 20px; }
.pf-label {
    display: block; font-size: 12px; font-weight: 700;
    color: #374151; text-transform: uppercase;
    letter-spacing: 0.5px; margin-bottom: 7px;
}
.pf-label span { color: #ef4444; margin-left: 2px; }
.pf-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 13.5px;
    color: #0f172a;
    font-family: 'Inter', sans-serif;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #fafafa;
    outline: none;
}
.pf-input:focus { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.1); background: #fff; }
.pf-input[readonly], .pf-input:disabled {
    background: #f1f5f9; color: #64748b; cursor: not-allowed;
}
.pf-input-static {
    padding: 11px 14px; font-size: 13.5px; color: #64748b;
    background: #f8fafc; border: 1.5px solid #f1f5f9;
    border-radius: 10px; display: block;
}
.pf-btn {
    padding: 11px 28px; border-radius: 10px; border: none;
    font-size: 13.5px; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center; gap: 8px;
    transition: all 0.2s ease;
}
.pf-btn-primary { background: linear-gradient(135deg,#22c55e,#16a34a); color: #fff; }
.pf-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(34,197,94,0.3); }
.pf-btn-blue { background: linear-gradient(135deg,#6366f1,#4f46e5); color: #fff; }
.pf-btn-blue:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.3); }
.pf-btn-red { background: linear-gradient(135deg,#ef4444,#dc2626); color: #fff; }
.pf-btn-red:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(239,68,68,0.3); }
/* Photo preview */
.photo-preview-wrap { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
.photo-preview-current {
    width: 80px; height: 80px; border-radius: 50%;
    object-fit: cover; border: 3px solid #e2e8f0;
    flex-shrink: 0;
}
.photo-drop-zone {
    flex: 1; border: 2px dashed #cbd5e0; border-radius: 12px;
    padding: 20px; text-align: center; cursor: pointer;
    transition: all 0.2s ease; background: #fafafa;
}
.photo-drop-zone:hover, .photo-drop-zone.dragover { border-color: #22c55e; background: #f0fdf4; }
.photo-drop-zone input[type=file] { display: none; }
/* Alert boxes */
.pf-alert {
    padding: 13px 18px; border-radius: 12px; font-size: 13px;
    font-weight: 600; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 20px;
}
.pf-alert-success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.pf-alert-error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
/* Password strength */
.pw-strength { height: 4px; border-radius: 4px; margin-top: 6px; transition: all 0.3s; background: #e2e8f0; }
.pw-strength-bar { height: 100%; border-radius: 4px; transition: all 0.3s; }
.pw-toggle { position: relative; }
.pw-toggle .pf-input { padding-right: 42px; }
.pw-eye {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; cursor: pointer; background: none; border: none; padding: 0;
}
.pw-eye:hover { color: #374151; }
</style>

<section class="content-header">
    <div class="content-header-left">
        <h1><i class="fa fa-user-circle" style="color:#22c55e;margin-right:8px;font-size:20px;"></i> My Profile</h1>
    </div>
</section>

<section class="content">
<div class="profile-wrap">

    <?php
    // ── Alerts ──
    if ($success_message): ?>
    <div class="pf-alert pf-alert-success">
        <i class="fa fa-check-circle" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
        <span><?php echo htmlspecialchars($success_message); ?></span>
    </div>
    <?php endif; ?>
    <?php if ($error_message): ?>
    <div class="pf-alert pf-alert-error">
        <i class="fa fa-exclamation-circle" style="font-size:16px;flex-shrink:0;margin-top:1px;"></i>
        <span><?php echo $error_message; ?></span>
    </div>
    <?php endif; ?>

    <!-- ── Profile Header Card ── -->
    <div class="profile-header-card">
        <div class="profile-avatar-wrap">
            <?php
            $avatarSrc = !empty($photo) && $photo !== 'no_image.png'
                ? ($upload_web . htmlspecialchars($photo))
                : 'no_image.png';
            ?>
            <img src="<?php echo $avatarSrc; ?>"
                 class="profile-avatar"
                 id="headerAvatar"
                 alt="Avatar"
                 onerror="this.onerror=null; this.src='Res_img/no_image.png'">
            <div class="profile-avatar-badge">
                <i class="fa fa-check" style="color:#fff;font-size:9px;"></i>
            </div>
        </div>
        <div class="profile-meta">
            <h2><?php echo htmlspecialchars($full_name ?: 'Admin'); ?></h2>
            <div class="profile-email">
                <i class="fa fa-envelope-o" style="margin-right:5px;"></i>
                <?php echo htmlspecialchars($email ?: 'No email'); ?>
            </div>
            <?php if ($phone): ?>
            <div class="profile-email" style="margin-top:3px;">
                <i class="fa fa-phone" style="margin-right:5px;"></i>
                <?php echo htmlspecialchars($phone); ?>
            </div>
            <?php endif; ?>
            <div class="profile-role-badge">
                <i class="fa fa-shield" style="margin-right:4px;"></i>
                <?php echo htmlspecialchars($role_label ?: 'Admin'); ?>
            </div>
        </div>
    </div>

    <!-- ── Tabs ── -->
    <div class="profile-tabs" role="tablist">
        <button class="profile-tab-btn active" onclick="switchTab('info', this)">
            <i class="fa fa-pencil-square-o"></i> Edit Info
        </button>
        <button class="profile-tab-btn" onclick="switchTab('photo', this)">
            <i class="fa fa-camera"></i> Change Photo
        </button>
        <button class="profile-tab-btn" onclick="switchTab('password', this)">
            <i class="fa fa-lock"></i> Change Password
        </button>
    </div>

    <!-- ── TAB 1: Edit Info ── -->
    <div class="profile-pane active" id="pane-info">
        <div class="pf-card">
            <form method="post" action="">
                <div class="pf-field">
                    <label class="pf-label">Full Name <span>*</span></label>
                    <input type="text" name="full_name" class="pf-input"
                           value="<?php echo htmlspecialchars($full_name); ?>"
                           placeholder="Your full name" required>
                </div>

                <div class="pf-field">
                    <label class="pf-label">Email Address <span>*</span></label>
                    <?php if ($is_tbluser): ?>
                    <input type="email" name="email" class="pf-input"
                           value="<?php echo htmlspecialchars($email); ?>"
                           placeholder="Email address" required>
                    <?php else: ?>
                    <span class="pf-input-static">
                        <i class="fa fa-lock" style="margin-right:5px;color:#94a3b8;"></i>
                        <?php echo htmlspecialchars($email); ?>
                    </span>
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <?php endif; ?>
                </div>

                <div class="pf-field">
                    <label class="pf-label">Phone Number</label>
                    <input type="tel" name="phone" class="pf-input"
                           value="<?php echo htmlspecialchars($phone); ?>"
                           placeholder="10-digit mobile number"
                           maxlength="10" pattern="^[6-9][0-9]{9}$">
                </div>

                <div class="pf-field">
                    <label class="pf-label">Role</label>
                    <span class="pf-input-static">
                        <i class="fa fa-shield" style="margin-right:5px;color:#6366f1;"></i>
                        <?php echo htmlspecialchars($role_label ?: 'Admin'); ?>
                    </span>
                </div>

                <button type="submit" name="form1" class="pf-btn pf-btn-primary">
                    <i class="fa fa-save"></i> Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- ── TAB 2: Change Photo ── -->
    <div class="profile-pane" id="pane-photo">
        <div class="pf-card">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="photo-preview-wrap">
                    <img src="<?php echo $avatarSrc; ?>"
                         class="photo-preview-current"
                         id="photoPreview"
                         onerror="this.onerror=null; this.src='Res_img/no_image.png'"
                         alt="Current photo">
                    <div style="flex:1;">
                        <div style="font-size:13px;font-weight:600;color:#0f172a;margin-bottom:4px;">Current Photo</div>
                        <div style="font-size:11.5px;color:#94a3b8;">Click below to upload a new photo (max 3MB)</div>
                    </div>
                </div>

                <label class="photo-drop-zone" id="dropZone" for="photoInput">
                    <input type="file" name="photo" id="photoInput" accept="image/*"
                           onchange="previewPhoto(this)">
                    <i class="fa fa-cloud-upload" style="font-size:28px;color:#94a3b8;margin-bottom:8px;display:block;"></i>
                    <div id="dropZoneText" style="font-size:13px;font-weight:600;color:#374151;">
                        Click to choose photo
                    </div>
                    <div style="font-size:11.5px;color:#94a3b8;margin-top:4px;">
                        JPG, PNG, GIF, WebP supported
                    </div>
                </label>

                <div style="margin-top:20px;">
                    <button type="submit" name="form2" class="pf-btn pf-btn-blue">
                        <i class="fa fa-upload"></i> Upload Photo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── TAB 3: Change Password ── -->
    <div class="profile-pane" id="pane-password">
        <div class="pf-card">
            <form method="post" action="">
                <div class="pf-field">
                    <label class="pf-label">New Password <span>*</span></label>
                    <div class="pw-toggle">
                        <input type="password" name="password" id="pw1" class="pf-input"
                               placeholder="Minimum 6 characters"
                               oninput="checkStrength(this.value)">
                        <button type="button" class="pw-eye" onclick="togglePw('pw1',this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div class="pw-strength" style="margin-top:6px;">
                        <div class="pw-strength-bar" id="pwStrengthBar" style="width:0%;"></div>
                    </div>
                    <div id="pwStrengthLabel" style="font-size:11px;color:#94a3b8;margin-top:3px;"></div>
                </div>

                <div class="pf-field">
                    <label class="pf-label">Confirm Password <span>*</span></label>
                    <div class="pw-toggle">
                        <input type="password" name="re_password" id="pw2" class="pf-input"
                               placeholder="Re-enter new password"
                               oninput="checkMatch()">
                        <button type="button" class="pw-eye" onclick="togglePw('pw2',this)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <div id="matchLabel" style="font-size:11px;margin-top:3px;"></div>
                </div>

                <button type="submit" name="form3" class="pf-btn pf-btn-red">
                    <i class="fa fa-lock"></i> Update Password
                </button>
            </form>
        </div>
    </div>

</div>
</section>

<script>
/* ── Tab Switcher ── */
function switchTab(id, btn) {
    document.querySelectorAll('.profile-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.profile-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('pane-' + id).classList.add('active');
    btn.classList.add('active');
}

/* ── Photo preview before upload ── */
function previewPhoto(input) {
    if (!input.files?.length) return;
    const file = input.files[0];
    if (file.size > 3 * 1024 * 1024) {
        alert('File too large (max 3 MB)');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('photoPreview').src = e.target.result;
        document.getElementById('headerAvatar').src = e.target.result;
        document.getElementById('dropZoneText').textContent = file.name;
        document.getElementById('dropZone').style.borderColor = '#22c55e';
        document.getElementById('dropZone').style.background  = '#f0fdf4';
    };
    reader.readAsDataURL(file);
}

/* ── Password strength ── */
function checkStrength(val) {
    const bar   = document.getElementById('pwStrengthBar');
    const label = document.getElementById('pwStrengthLabel');
    let score   = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const pct    = (score / 5) * 100;
    const colors = ['#ef4444','#f59e0b','#eab308','#22c55e','#16a34a'];
    const labels = ['Very Weak','Weak','Fair','Strong','Very Strong'];
    bar.style.width = pct + '%';
    bar.style.background = colors[score - 1] || '#e2e8f0';
    label.textContent = val.length ? (labels[score - 1] || '') : '';
    label.style.color = colors[score - 1] || '#94a3b8';
}

/* ── Password match indicator ── */
function checkMatch() {
    const p1 = document.getElementById('pw1').value;
    const p2 = document.getElementById('pw2').value;
    const ml = document.getElementById('matchLabel');
    if (!p2) { ml.textContent = ''; return; }
    if (p1 === p2) {
        ml.textContent = '✓ Passwords match';
        ml.style.color = '#16a34a';
    } else {
        ml.textContent = '✗ Passwords do not match';
        ml.style.color = '#ef4444';
    }
}

/* ── Show/hide password ── */
function togglePw(id, btn) {
    const inp = document.getElementById(id);
    const eye = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        eye.className = 'fa fa-eye-slash';
    } else {
        inp.type = 'password';
        eye.className = 'fa fa-eye';
    }
}

/* ── Auto-open correct tab after form submit ── */
<?php
$open = 'info';
if (isset($_POST['form2'])) $open = 'photo';
if (isset($_POST['form3'])) $open = 'password';
echo "document.addEventListener('DOMContentLoaded', () => {
    const btn = document.querySelector('.profile-tab-btn[onclick*=\"$open\"]');
    if (btn) btn.click();
});";
?>
</script>

<?php require_once('footer.php'); ?>