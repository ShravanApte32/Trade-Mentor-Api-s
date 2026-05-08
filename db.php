<?php
// Check if running on Railway (production) or local
if (getenv('RAILWAY_ENVIRONMENT')) {
    // Railway MySQL connection
    $host = getenv('MYSQLHOST');
    $port = getenv('MYSQLPORT');
    $user = getenv('MYSQLUSER');
    $pass = getenv('MYSQLPASSWORD');
    $dbname = getenv('MYSQLDATABASE');
    
    // Use host:port format
    $host = $host . ':' . $port;
} else {
    // Local XAMPP
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "candle_db";
}

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$conn->set_charset("utf8");
?>