<?php
/**
 * Customer Profile Update Handler (edit1.php)
 * Updates name, mobile, address, or password in `user` table
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db_config.php';

$userId = $_SESSION['uid'] ?? '';
if (empty($userId)) {
    header("Location: login.php");
    exit;
}

$esc_user_id = mysqli_real_escape_string($con, $userId);

// 1. Update Profile Info (First Name, Last Name, Mobile)
if (isset($_POST['reg1']) || isset($_POST['update_profile'])) {
    $fname = trim($_POST['fname1'] ?? $_POST['fname'] ?? '');
    $lname = trim($_POST['lname1'] ?? $_POST['lname'] ?? '');
    $mobile = trim($_POST['mobile1'] ?? $_POST['mobile'] ?? '');

    $esc_fname = mysqli_real_escape_string($con, $fname);
    $esc_lname = mysqli_real_escape_string($con, $lname);
    $esc_mobile = mysqli_real_escape_string($con, $mobile);

    $sql = "UPDATE user SET fname='$esc_fname', lname='$esc_lname', mobile='$esc_mobile' 
            WHERE user_id='$esc_user_id' OR id='$esc_user_id' OR email='" . mysqli_real_escape_string($con, $_SESSION['uemail'] ?? '') . "'";
    
    if (mysqli_query($con, $sql)) {
        $_SESSION['uname'] = $fname;
        $_SESSION['mgs'] = 'Profile information updated successfully!';
    } else {
        $_SESSION['err_mgs'] = 'Failed to update profile: ' . mysqli_error($con);
    }
    
    header("Location: userprofile.php");
    exit;
}

// 2. Update Password
if (isset($_POST['update_password']) || isset($_POST['cur'])) {
    $cur = trim($_POST['cur'] ?? '');
    $new = trim($_POST['new'] ?? '');
    $conf = trim($_POST['con'] ?? $_POST['conf'] ?? '');

    if (empty($cur) || empty($new)) {
        $_SESSION['err_mgs'] = 'Please provide all password fields.';
        header("Location: userprofile.php?tab=password");
        exit;
    }

    if ($new !== $conf) {
        $_SESSION['err_mgs'] = 'New password and confirmation password do not match.';
        header("Location: userprofile.php?tab=password");
        exit;
    }

    // Fetch existing user password
    $q = mysqli_query($con, "SELECT * FROM user WHERE user_id='$esc_user_id' OR id='$esc_user_id' OR email='" . mysqli_real_escape_string($con, $_SESSION['uemail'] ?? '') . "' LIMIT 1");
    $user = mysqli_fetch_assoc($q);

    if (!$user) {
        $_SESSION['err_mgs'] = 'User not found.';
        header("Location: userprofile.php");
        exit;
    }

    $storedPwd = $user['pwd'];
    $valid = false;

    if (password_verify($cur, $storedPwd) || md5($cur) === $storedPwd || $cur === $storedPwd) {
        $valid = true;
    }

    if (!$valid) {
        $_SESSION['err_mgs'] = 'Incorrect current password.';
        header("Location: userprofile.php?tab=password");
        exit;
    }

    $hashed = password_hash($new, PASSWORD_BCRYPT);
    $uId = $user['id'];
    if (mysqli_query($con, "UPDATE user SET pwd='$hashed' WHERE id=$uId")) {
        $_SESSION['mgs'] = 'Password changed successfully!';
    } else {
        $_SESSION['err_mgs'] = 'Failed to update password.';
    }

    header("Location: userprofile.php?tab=password");
    exit;
}

header("Location: userprofile.php");
exit;
