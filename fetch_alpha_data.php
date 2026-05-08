<?php
header('Content-Type: application/json');
require_once 'db.php';

// Configuration
$apiKey = getenv('ALPHA_VANTAGE_API_KEY') ?: "5C1DIBOKZL4T8DWR";
$enableMockMode = false; // FALSE for real data
$symbols = explode(',', $_GET['symbol'] ?? 'RELIANCE.BSE');
$requestedTimeframe = $_GET['timeframe'] ?? '1D';
$cacheTTL = 86400; // 24 hours in seconds

// Timeframe mapping
$function = 'TIME_SERIES_DAILY';
$interval = '';
switch ($requestedTimeframe) {
    case '1D': $function = 'TIME_SERIES_DAILY'; break;
    case '1W': $function = 'TIME_SERIES_WEEKLY'; break;
    case '1M': $function = 'TIME_SERIES_MONTHLY'; break;
    case '5min': $function = 'TIME_SERIES_INTRADAY'; $interval = '5min'; break;
}

$results = [];

foreach ($symbols as $rawSymbol) {
    $symbol = strtoupper(trim($rawSymbol));
    if (!str_ends_with($symbol, '.BSE')) {
        $symbol .= '.BSE';
    }
    
    // Create cache key
    $cacheKey = md5($symbol . $requestedTimeframe);
    
    // CHECK CACHE FIRST
    $cachedData = getCachedData($conn, $cacheKey);
    
    if ($cachedData) {
        // Data found in cache - use it
        $storedCount = storeCandlesFromData($conn, $cachedData, $symbol, $requestedTimeframe);
        $results[] = [
            "symbol" => $symbol, 
            "status" => "success", 
            "source" => "cache",  // ← Indicates cached data
            "candles" => count($cachedData),
            "stored" => $storedCount
        ];
        continue; // Skip API call
    }
    
    // No cache - make real API call
    $url = "https://www.alphavantage.co/query?function=$function&symbol=$symbol&apikey=$apiKey";
    if ($function === 'TIME_SERIES_INTRADAY') {
        $url .= "&interval=$interval&outputsize=compact";
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200 || !$response) {
        $results[] = ["symbol" => $symbol, "status" => "error", "message" => "HTTP $httpCode"];
        continue;
    }
    
    $data = json_decode($response, true);
    
    // Check for API error messages
    if (isset($data['Error Message'])) {
        $results[] = ["symbol" => $symbol, "status" => "error", "message" => $data['Error Message']];
        continue;
    }
    
    if (isset($data['Note'])) {
        $results[] = ["symbol" => $symbol, "status" => "rate_limit", "message" => $data['Note']];
        continue;
    }
    
    $seriesKey = match ($function) {
        'TIME_SERIES_DAILY' => "Time Series (Daily)",
        'TIME_SERIES_WEEKLY' => "Weekly Time Series", 
        'TIME_SERIES_MONTHLY' => "Monthly Time Series",
        'TIME_SERIES_INTRADAY' => "Time Series ($interval)",
        default => ""
    };
    
    if (!isset($data[$seriesKey])) {
        $results[] = [
            "symbol" => $symbol, 
            "status" => "error", 
            "message" => "No time series data",
            "available_keys" => array_keys($data)
        ];
        continue;
    }
    
    // Store in cache
    cacheData($conn, $cacheKey, $data[$seriesKey], $cacheTTL);
    
    // Store in database
    $storedCount = storeCandlesFromData($conn, $data[$seriesKey], $symbol, $requestedTimeframe);
    $results[] = [
        "symbol" => $symbol, 
        "status" => "success", 
        "source" => "api",  // ← Indicates fresh API call
        "candles" => count($data[$seriesKey]),
        "stored" => $storedCount
    ];
    
    // Rate limiting
    sleep(1);
}

echo json_encode(["status" => "success", "results" => $results]);

// ========== HELPER FUNCTIONS ==========

function getCachedData($conn, $cacheKey) {
    $stmt = $conn->prepare("SELECT response_data FROM api_cache WHERE cache_key = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $cacheKey);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        error_log("Cache HIT for key: $cacheKey");
        return json_decode($row['response_data'], true);
    }
    error_log("Cache MISS for key: $cacheKey");
    return null;
}

function cacheData($conn, $cacheKey, $data, $ttl) {
    $responseJson = json_encode($data);
    $expiresAt = date('Y-m-d H:i:s', time() + $ttl);
    
    $stmt = $conn->prepare("INSERT INTO api_cache (cache_key, response_data, expires_at) VALUES (?, ?, ?) 
                            ON DUPLICATE KEY UPDATE 
                            response_data = VALUES(response_data), 
                            expires_at = VALUES(expires_at)");
    $stmt->bind_param("sss", $cacheKey, $responseJson, $expiresAt);
    $stmt->execute();
    $stmt->close();
    
    error_log("Cached data for key: $cacheKey, expires at: $expiresAt");
}

function storeCandlesFromData($conn, $data, $symbol, $timeframe) {
    $count = 0;
    
    // First, clear existing data for this symbol/timeframe
    $clearStmt = $conn->prepare("DELETE FROM candles WHERE stock_symbol = ? AND timeframe = ?");
    $clearStmt->bind_param("ss", $symbol, $timeframe);
    $clearStmt->execute();
    $clearStmt->close();
    
    foreach ($data as $date => $candle) {
        if (!is_array($candle)) continue;
        
        $open = floatval($candle["1. open"] ?? 0);
        $high = floatval($candle["2. high"] ?? 0);
        $low = floatval($candle["3. low"] ?? 0);
        $close = floatval($candle["4. close"] ?? 0);
        $volume = intval($candle["5. volume"] ?? 0);
        
        $stmt = $conn->prepare("INSERT INTO candles (stock_symbol, date, open, high, low, close, volume, timeframe) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddddds", $symbol, $date, $open, $high, $low, $close, $volume, $timeframe);
        
        if ($stmt->execute()) {
            $count++;
        } else {
            error_log("Insert failed for $symbol on $date: " . $stmt->error);
        }
        $stmt->close();
    }
    
    error_log("Stored $count candles for $symbol ($timeframe)");
    return $count;
}
?>