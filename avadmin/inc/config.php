<?php
/**
 * avadmin/inc/config.php
 * Redirects to master DB config. Edit credentials in /db_config.php only.
 * Provides both $pdo and $con connections (both set in db_config.php).
 */
require_once __DIR__ . '/../../db_config.php';

// CSRF class path may vary - load functions from local inc folder
// (These are specific to avadmin and stay here)