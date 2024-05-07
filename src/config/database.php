<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
$host = getenv('DB_HOST');
$name = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dsn = "mysql:host=$host;port=3306;dbname=$name";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $connection = new PDO($dsn, $user, $password, $opt);
    
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}