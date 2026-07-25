<?php
// ==========================================
// Site Configuration
// ==========================================

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'lost_found_oop');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL of the project (change if your folder name is different)
define('BASE_URL', '/lost-found-oop');

// Absolute path to the uploads folder on disk
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Max upload size for item images (bytes)
define('MAX_IMAGE_SIZE', 2 * 1024 * 1024); // 2MB
