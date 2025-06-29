<?php
require_once 'db.php';
header('Content-Type: application/json');

try {
    $query = "SELECT DISTINCT stock_symbol FROM candles ORDER BY stock_symbol ASC";
    $result = $conn->query($query);

    $symbols = [];
    while ($row = $result->fetch_assoc()) {
        $symbols[] = $row['stock_symbol'];
    }

    echo json_encode([
        "status" => "success",
        "symbols" => $symbols
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
