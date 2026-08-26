<?php

// BD configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'mini_drive');
define('DB_USER', 'mini_drive_user');
define('DB_PASS', 'your-user-db-password');

// Application
define('BASE_URL', 'http://appli.l2eni.mg');

define(
    'UPLOAD_DIR',
    __DIR__ . '/../public/uploads/'
);

define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50 Mo

// LDAP
define('LDAP_HOST', '127.0.0.1');
define('LDAP_PORT', 389);
define('LDAP_BASE_DN', 'ou=users,dc=l2eni,dc=mg');

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>