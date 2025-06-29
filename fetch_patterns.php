<?php
header('Content-Type: application/json');
include 'db.php';
include 'patterns.php';

$symbol = isset($_GET['symbol']) ? $_GET['symbol'] : 'TCS';
$timeframe = isset($_GET['timeframe']) ? $_GET['timeframe'] : '1D';

$sql = "SELECT * FROM candles 
        WHERE stock_symbol = '$symbol' 
        AND timeframe = '$timeframe'
        ORDER BY date ASC";

$result = $conn->query($sql);

$candles = [];
while ($row = $result->fetch_assoc()) {
    $candles[] = [
        'date' => $row['date'],
        'open' => (float)$row['open'],
        'high' => (float)$row['high'],
        'low' => (float)$row['low'],
        'close' => (float)$row['close'],
        'volume' => (float)$row['volume'] 
    ];
}

$patterns = detectPatterns($candles);

echo json_encode([
    'status' => 'success',
    'stock' => $symbol,
    'patterns' => $patterns,
    'candles' => $candles
]);
?>
