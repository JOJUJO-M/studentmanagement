<?php
// config/database.php

// Dynamic BASE_URL Detection
if (!defined('BASE_URL')) {
    $doc_root = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $proj_root = str_replace('\\', '/', realpath(dirname(__DIR__)));
    
    $base_url = '';
    if (!empty($doc_root) && !empty($proj_root)) {
        if (stripos($proj_root, $doc_root) === 0) {
            $base_url = substr($proj_root, strlen($doc_root));
        }
    }
    $base_url = str_replace('\\', '/', $base_url);
    $base_url = '/' . trim($base_url, '/');
    if ($base_url === '/') {
        $base_url = '';
    }
    define('BASE_URL', $base_url);
}


$host = 'localhost';
$db = 'school_db';
$user = 'schooluser';
$pass = 'School@12345'; // Default for local installations, update as needed
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::ATTR_EMULATE_PREPARES => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

// Global Settings Cache
$settings = [];
try {
     $stmt = $pdo->query("SELECT * FROM settings");
     while ($row = $stmt->fetch()) {
          $settings[$row['setting_key']] = $row['setting_value'];
     }
} catch (Exception $e) {
}

function get_setting($key, $default = '')
{
     global $settings;
     return $settings[$key] ?? $default;
}

// Autoload or include classes
require_once __DIR__ . '/../includes/classes/Student.php';
require_once __DIR__ . '/../includes/classes/Course.php';
require_once __DIR__ . '/../includes/classes/Enrollment.php';
require_once __DIR__ . '/../includes/classes/Fee.php';

$studentObj = new Student($pdo);
$courseObj = new Course($pdo);
$enrollmentObj = new Enrollment($pdo);
$feeObj = new Fee($pdo);

 
