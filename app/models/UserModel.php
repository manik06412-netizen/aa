<?php
namespace App\Models;

use App\Core\Model;

class UserModel extends Model {
    /**
     * Check if current session user is authenticated/logged-in
     */
    public static function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            return true;
        }
        if (isset($_SESSION['user_login']) && $_SESSION['user_login'] == 1) {
            return true;
        }
        if (!empty($_SESSION['uemail']) && !empty($_SESSION['uid']) && empty($_SESSION['is_guest'])) {
            return true;
        }
        return false;
    }

    /**
     * Get or Initialize Guest User ID
     */
    public static function getOrCreateSessionUser() {
        if (!isset($_SESSION['uid']) || empty($_SESSION['uid'])) {
            $db = \App\Core\Database::getInstance();
            $con = $db->getConnection();
            $tmpid = mysqli_query($con, "SELECT max(id) as tmpid FROM user");
            $rtem = mysqli_fetch_array($tmpid);
            $tid = $rtem ? (int)$rtem['tmpid'] : 0;
            $date = date("d/m/Y");
            $tpuser = rand(100, 200) . "" . (time() + $tid);
            $_SESSION['uid'] = $tpuser;
            $_SESSION['is_guest'] = true;
            mysqli_query($con, "INSERT INTO user VALUES(null, '$tpuser', 'Guest', 'Guest', '-', '-', '-', '$date', '1')");
        }
        return $_SESSION['uid'];
    }

    /**
     * Authenticate Customer Login with Bcrypt & Backward Compatibility
     */
    public function login($email, $password) {
        $email = trim($email);
        $user = $this->fetchOnePrepared("SELECT * FROM user WHERE email = ? AND status = '1' LIMIT 1", [$email]);

        if (!$user) {
            return false;
        }

        $storedPassword = $user['pwd'] ?? '';
        $authenticated = false;

        // 1. Check if Bcrypt hash
        if (password_verify($password, $storedPassword)) {
            $authenticated = true;
            if (password_needs_rehash($storedPassword, PASSWORD_BCRYPT)) {
                $newHash = password_hash($password, PASSWORD_BCRYPT);
                $this->execute("UPDATE user SET pwd = ? WHERE id = ?", [$newHash, $user['id']]);
            }
        } 
        // 2. Backward compatibility: MD5 hash
        elseif ($storedPassword === md5($password)) {
            $authenticated = true;
            // Auto-upgrade to Bcrypt
            $newHash = password_hash($password, PASSWORD_BCRYPT);
            $this->execute("UPDATE user SET pwd = ? WHERE id = ?", [$newHash, $user['id']]);
        } 
        // 3. Backward compatibility: Plain text
        elseif ($storedPassword === $password) {
            $authenticated = true;
            // Auto-upgrade to Bcrypt
            $newHash = password_hash($password, PASSWORD_BCRYPT);
            $this->execute("UPDATE user SET pwd = ? WHERE id = ?", [$newHash, $user['id']]);
        }

        return $authenticated ? $user : false;
    }

    /**
     * Register New Customer with Bcrypt
     */
    public function register($data) {
        $fname = trim($data['fname'] ?? ($data['name'] ?? ''));
        $lname = trim($data['lname'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['mobile'] ?? ($data['phone'] ?? ''));
        $password = $data['password'] ?? ($data['pwd'] ?? '');
        
        $check = $this->fetchOnePrepared("SELECT id FROM user WHERE email = ? LIMIT 1", [$email]);
        if ($check) {
            return ['success' => false, 'message' => 'Email already registered.'];
        }

        $bcryptHash = password_hash($password, PASSWORD_BCRYPT);
        $date = date('d/m/Y');
        $uCode = 'USR' . rand(1000, 9999) . time();

        $sql = "INSERT INTO user (user_id, fname, lname, email, pwd, mobile, date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, '1')";
        $stmt = $this->execute($sql, [$uCode, $fname, $lname, $email, $bcryptHash, $phone, $date]);

        if ($stmt) {
            return ['success' => true, 'uid' => $uCode, 'fname' => $fname, 'lname' => $lname, 'email' => $email];
        }
        return ['success' => false, 'message' => 'Registration failed.'];
    }

    /**
     * Get User Profile by User ID
     */
    public function getUserById($userId) {
        return $this->fetchOnePrepared("SELECT * FROM user WHERE user_id = ? OR id = ? LIMIT 1", [$userId, $userId]);
    }
}
