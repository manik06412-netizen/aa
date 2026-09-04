<?php
/**
 * admin1/logout.php
 * Clears all admin1 (and compatible avadmin/admin) sessions and redirects to login.
 */
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
