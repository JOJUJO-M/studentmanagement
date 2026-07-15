<?php
// public/logout.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
session_start();
session_unset();
session_destroy();
header('Location: ' . BASE_URL . '/login.php');
exit();
