<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'mini_drive');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', 'http://localhost/mini-drive');
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50 Mo

session_start();
