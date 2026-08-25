<?php

// BD configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'mini_drive');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application
define('BASE_URL', 'http://localhost/mini-drive');
define(
    'UPLOAD_DIR',
    __DIR__ . '/../public/uploads/'
);
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50 Mo

// LDAP
define('LDAP_HOST', '127.0.0.1');
define('LDAP_PORT', 389);
<<<<<<< HEAD
define('LDAP_USER_FILTER', '(&(objectClass=inetOrgPerson)(mail=%s))');
=======
define('LDAP_BASE_DN', 'ou=users,dc=l2eni,dc=mg');

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
>>>>>>> bb97655 (Project architecture reorganized for LDAP auth - final)

?>