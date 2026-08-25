<?php

// BD configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'mini_drive');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', 'http://localhost/mini-drive');
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50 Mo

session_start();

// LDAP Authentication
define('LDAP_HOST', '127.0.0.1');
define('LDAP_PORT', 389);
define('LDAP_USER_FILTER', '(&(objectClass=inetOrgPerson)(mail=%s))');

?>