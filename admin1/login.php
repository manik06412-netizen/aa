<?php
/**
 * admin1/login.php
 * Combined login page for both admin (admin table) and avadmin (tbl_user) panels.
 * Supports MD5, password_verify (bcrypt/argon), and plain-text fallback.
 */
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/inc/config.php';

$error_message = '';
$success_message = '';

// Already logged in → redirect to dashboard
if (isset($_SESSION['admin1_user'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['login_submit'])) {
    $username_email = trim($_POST['username_email'] ?? '');
    $password_raw   = trim($_POST['password'] ?? '');

    if (empty($username_email) || empty($password_raw)) {
        $error_message = 'Username/Email and Password are required.';
    } else {
        $logged_in = false;
        $escaped_input = mysqli_real_escape_string($con, $username_email);

        // ── Try 1: admin table (by username or email) ──
        $admin_q = mysqli_query($con,
            "SELECT * FROM admin WHERE username = '$escaped_input' OR email = '$escaped_input' LIMIT 1"
        );
        if ($admin_q && mysqli_num_rows($admin_q) > 0) {
            $admin_row = mysqli_fetch_assoc($admin_q);
            $db_pass = $admin_row['password'];

            // Match MD5, bcrypt, or plain-text
            if ($db_pass === md5($password_raw) || $db_pass === $password_raw || password_verify($password_raw, $db_pass)) {
                $_SESSION['admin1_user'] = [
                    'id'        => $admin_row['adm_id'],
                    'name'      => $admin_row['username'],
                    'email'     => $admin_row['email'] ?? '',
                    'photo'     => 'no_image.png',
                    'role'      => 'admin',
                    'full_name' => $admin_row['username'],
                ];
                $_SESSION['adm_id'] = $admin_row['adm_id'];
                $logged_in = true;
            }
        }

        // ── Try 2: tbl_user table (email, status=Active) ──
        if (!$logged_in && isset($pdo)) {
            try {
                $user_stmt = $pdo->prepare(
                    "SELECT * FROM tbl_user WHERE (email = ? OR full_name = ?) AND status = 'Active' LIMIT 1"
                );
                $user_stmt->execute([$username_email, $username_email]);
                $user_row = $user_stmt->fetch();

                if ($user_row) {
                    $db_pass = $user_row['password'];
                    if ($db_pass === md5($password_raw) || $db_pass === $password_raw || password_verify($password_raw, $db_pass)) {
                        $_SESSION['admin1_user'] = [
                            'id'        => $user_row['id'],
                            'name'      => $user_row['full_name'] ?? $user_row['email'],
                            'email'     => $user_row['email'],
                            'photo'     => $user_row['photo'] ?? 'no_image.png',
                            'role'      => 'avadmin',
                            'full_name' => $user_row['full_name'] ?? 'Admin',
                        ];
                        $_SESSION['user'] = $user_row;
                        $_SESSION['adm_id'] = $user_row['id'];
                        $logged_in = true;
                    }
                }
            } catch (Exception $e) {
                // Fallback to mysqli for tbl_user if PDO query fails
                $tbl_q = mysqli_query($con, "SELECT * FROM tbl_user WHERE (email = '$escaped_input' OR full_name = '$escaped_input') AND status = 'Active' LIMIT 1");
                if ($tbl_q && mysqli_num_rows($tbl_q) > 0) {
                    $user_row = mysqli_fetch_assoc($tbl_q);
                    $db_pass = $user_row['password'];
                    if ($db_pass === md5($password_raw) || $db_pass === $password_raw || password_verify($password_raw, $db_pass)) {
                        $_SESSION['admin1_user'] = [
                            'id'        => $user_row['id'],
                            'name'      => $user_row['full_name'] ?? $user_row['email'],
                            'email'     => $user_row['email'],
                            'photo'     => $user_row['photo'] ?? 'no_image.png',
                            'role'      => 'avadmin',
                            'full_name' => $user_row['full_name'] ?? 'Admin',
                        ];
                        $_SESSION['user'] = $user_row;
                        $_SESSION['adm_id'] = $user_row['id'];
                        $logged_in = true;
                    }
                }
            }
        }

        if ($logged_in) {
            header('Location: index.php');
            exit;
        } else {
            $error_message = 'Invalid username/email or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Admin Login – Karuda Computers</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../img/karuda_logo.png">

    <!-- CSS Assets (direct local paths) -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body.login-page {
            background: linear-gradient(135deg, #0b132b 0%, #1c2541 40%, #0f3460 70%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative background glow circles */
        body.login-page::before {
            content: '';
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, rgba(0,0,0,0) 70%);
            top: 10%;
            left: 15%;
            filter: blur(40px);
            z-index: 0;
        }

        body.login-page::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, rgba(0,0,0,0) 70%);
            bottom: 10%;
            right: 15%;
            filter: blur(40px);
            z-index: 0;
        }

        .login-box {
            width: 420px;
            max-width: 100%;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
            padding: 42px 36px;
            position: relative;
            z-index: 1;
            animation: cardAppear 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(25px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-logo .icon-badge {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 28px;
            margin-bottom: 14px;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.35);
        }

        .login-logo h2 {
            font-family: 'Outfit', sans-serif;
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0 0 4px 0;
        }

        .login-logo p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13.5px;
            margin: 0;
        }

        .form-group-custom {
            margin-bottom: 18px;
        }

        .form-group-custom label {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
            display: block;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.45);
            font-size: 15px;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.16) !important;
            border-radius: 12px !important;
            color: #ffffff !important;
            padding: 12px 16px 12px 42px !important;
            font-size: 14px !important;
            width: 100% !important;
            transition: all 0.25s ease !important;
            height: auto !important;
        }

        .form-control-custom:focus {
            outline: none !important;
            border-color: #3b82f6 !important;
            background: rgba(255, 255, 255, 0.14) !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.25) !important;
        }

        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .btn-login-submit {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
            margin-top: 8px;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login-submit:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(37, 99, 235, 0.45);
        }

        .btn-login-submit:active {
            transform: translateY(0);
        }

        .error-alert {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.5);
            border-radius: 12px;
            color: #fca5a5;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-footer-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-footer-info span {
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.7);
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 30px 22px;
                border-radius: 20px;
            }
            .login-logo h2 { font-size: 20px; }
        }
    </style>
</head>
<body class="login-page">

<div class="login-box">
    <div class="login-logo">
        <div class="icon-badge">
            <i class="fa fa-leaf"></i>
        </div>
        <h2>Karuda Computers Admin</h2>
        <p>Unified Control Portal</p>
    </div>

    <?php if (!empty($error_message)): ?>
    <div class="error-alert">
        <i class="fa fa-exclamation-triangle"></i>
        <span><?php echo htmlspecialchars($error_message); ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="off">
        <div class="form-group-custom">
            <label for="username_email">Username or Email</label>
            <div class="input-wrapper">
                <i class="fa fa-user"></i>
                <input class="form-control-custom" type="text" id="username_email" name="username_email"
                       placeholder="Enter username or email"
                       value="<?php echo htmlspecialchars($_POST['username_email'] ?? ''); ?>" required autofocus>
            </div>
        </div>

        <div class="form-group-custom">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <i class="fa fa-lock"></i>
                <input class="form-control-custom" type="password" id="password" name="password"
                       placeholder="Enter password" required>
            </div>
        </div>

        <button type="submit" name="login_submit" class="btn-login-submit">
            <i class="fa fa-sign-in"></i> Sign In to Admin Panel
        </button>
    </form>

    <div class="login-footer-info">
        <span><i class="fa fa-shield"></i> System Admin</span>
        <span><i class="fa fa-shopping-bag"></i> Product Admin</span>
    </div>
</div>

<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
