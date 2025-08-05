<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "museum";
$db_port = "3307";


$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {

    $dsn = "mysql:host=$db_server;port=$db_port;dbname=$db_name;charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    
    /* testing connection
    if ($pdo) {
        echo "Database connected successfully!";
    } */
    
} catch(PDOException $e) {

    die("Connection failed: " . $e->getMessage());
}


function getConnection() {
    global $pdo;
    return $pdo;
}
?>