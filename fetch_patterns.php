<?php
header('Content-Type: application/json');
include 'db.php';

$symbol = isset($_GET['symbol']) ? $_GET['symbol'] : 'RELIANCE.BSE';
$timeframe = isset($_GET['timeframe']) ? $_GET['timeframe'] : '1D';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 500; // Increased limit

// Escape inputs to prevent SQL injection
$symbol = $conn->real_escape_string($symbol);
$timeframe = $conn->real_escape_string($timeframe);

$sql = "SELECT * FROM candles 
        WHERE stock_symbol = '$symbol' 
        AND timeframe = '$timeframe'
        ORDER BY date ASC
        LIMIT $limit";

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

// Detect candlestick patterns
$candlestickPatterns = detectPatterns($candles);

// Detect chart patterns (trend-based patterns)
$chartPatterns = detectChartPatterns($candles);

// Merge both types of patterns
$allPatterns = array_merge($candlestickPatterns, $chartPatterns);

// Sort by date
usort($allPatterns, function($a, $b) {
    return strtotime($a['date']) - strtotime($b['date']);
});

echo json_encode([
    'status' => 'success',
    'stock' => $symbol,
    'timeframe' => $timeframe,
    'patterns' => $allPatterns,
    'total_patterns' => count($allPatterns),
    'candles' => $candles,
    'total_candles' => count($candles)
]);

$conn->close();

// ========== PATTERN DETECTION FUNCTIONS ==========

function detectPatterns($candles) {
    $results = [];

    for ($i = 0; $i < count($candles); $i++) {
        $c = $candles[$i];
        $open = $c['open'];
        $close = $c['close'];
        $high = $c['high'];
        $low = $c['low'];
        $date = $c['date'];
        
        $body = abs($close - $open);
        $range = $high - $low;
        $lowerShadow = min($open, $close) - $low;
        $upperShadow = $high - max($open, $close);
        $isBullish = $close > $open;
        $isBearish = $close < $open;
        
        // Doji
        if ($body < ($range * 0.1) && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Doji"];
        }
        
        // Hammer (Bullish)
        if ($lowerShadow > 2 * $body && $upperShadow < $body && $isBullish && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Hammer"];
        }
        
        // Shooting Star (Bearish)
        if ($upperShadow > 2 * $body && $lowerShadow < $body && $isBearish && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Shooting Star"];
        }
        
        // Bullish Engulfing (2-candle pattern)
        if ($i > 0) {
            $prev = $candles[$i - 1];
            $prevIsBearish = $prev['close'] < $prev['open'];
            
            if ($prevIsBearish && $isBullish && $close > $prev['open'] && $open < $prev['close']) {
                $results[] = ["date" => $date, "pattern" => "Bullish Engulfing"];
            }
        }
        
        // Bearish Engulfing (2-candle pattern)
        if ($i > 0) {
            $prev = $candles[$i - 1];
            $prevIsBullish = $prev['close'] > $prev['open'];
            
            if ($prevIsBullish && $isBearish && $open > $prev['close'] && $close < $prev['open']) {
                $results[] = ["date" => $date, "pattern" => "Bearish Engulfing"];
            }
        }
        
        // Morning Star (3-candle pattern)
        if ($i >= 2) {
            $c1 = $candles[$i - 2];
            $c2 = $candles[$i - 1];
            $c1Bearish = $c1['close'] < $c1['open'];
            $c2Body = abs($c2['close'] - $c2['open']);
            $c2Range = $c2['high'] - $c2['low'];
            
            if ($c1Bearish && $c2Body < ($c2Range * 0.1) && $isBullish && $close > $c1['open']) {
                $results[] = ["date" => $date, "pattern" => "Morning Star"];
            }
        }
        
        // Evening Star (3-candle pattern)
        if ($i >= 2) {
            $c1 = $candles[$i - 2];
            $c2 = $candles[$i - 1];
            $c1Bullish = $c1['close'] > $c1['open'];
            $c2Body = abs($c2['close'] - $c2['open']);
            $c2Range = $c2['high'] - $c2['low'];
            
            if ($c1Bullish && $c2Body < ($c2Range * 0.1) && $isBearish && $close < $c1['open']) {
                $results[] = ["date" => $date, "pattern" => "Evening Star"];
            }
        }
        
        // Three White Soldiers
        if ($i >= 2) {
            $c1 = $candles[$i - 2];
            $c2 = $candles[$i - 1];
            $c1Bullish = $c1['close'] > $c1['open'];
            $c2Bullish = $c2['close'] > $c2['open'];
            
            if ($c1Bullish && $c2Bullish && $isBullish && 
                $c2['open'] > $c1['open'] && $open > $c2['open'] &&
                $c2['close'] > $c1['close'] && $close > $c2['close']) {
                $results[] = ["date" => $date, "pattern" => "Three White Soldiers"];
            }
        }
        
        // Three Black Crows
        if ($i >= 2) {
            $c1 = $candles[$i - 2];
            $c2 = $candles[$i - 1];
            $c1Bearish = $c1['close'] < $c1['open'];
            $c2Bearish = $c2['close'] < $c2['open'];
            
            if ($c1Bearish && $c2Bearish && $isBearish && 
                $c2['open'] < $c1['open'] && $open < $c2['open'] &&
                $c2['close'] < $c1['close'] && $close < $c2['close']) {
                $results[] = ["date" => $date, "pattern" => "Three Black Crows"];
            }
        }
    }
    
    // Remove duplicates
    $uniqueResults = [];
    foreach ($results as $result) {
        $key = $result['date'] . '|' . $result['pattern'];
        if (!isset($uniqueResults[$key])) {
            $uniqueResults[$key] = $result;
        }
    }
    
    return array_values($uniqueResults);
}

function detectChartPatterns($candles) {
    $patterns = [];
    $count = count($candles);
    
    if ($count < 20) {
        return $patterns;
    }
    
    $highs = array_column($candles, 'high');
    $lows = array_column($candles, 'low');
    $closes = array_column($candles, 'close');
    
    // Find local peaks and troughs
    $peaks = [];
    $troughs = [];
    
    for ($i = 5; $i < $count - 5; $i++) {
        $isPeak = true;
        $isTrough = true;
        
        for ($j = -5; $j <= 5; $j++) {
            if ($j == 0) continue;
            if ($highs[$i] <= $highs[$i + $j]) $isPeak = false;
            if ($lows[$i] >= $lows[$i + $j]) $isTrough = false;
        }
        
        if ($isPeak) {
            $peaks[] = ['index' => $i, 'price' => $highs[$i], 'date' => $candles[$i]['date']];
        }
        if ($isTrough) {
            $troughs[] = ['index' => $i, 'price' => $lows[$i], 'date' => $candles[$i]['date']];
        }
    }
    
    // Double Top
    if (count($peaks) >= 2) {
        $lastPeak = $peaks[count($peaks) - 1];
        $secondLastPeak = $peaks[count($peaks) - 2];
        
        if (abs($lastPeak['price'] - $secondLastPeak['price']) / $lastPeak['price'] < 0.03) {
            $troughBetween = null;
            foreach ($troughs as $trough) {
                if ($trough['index'] > $secondLastPeak['index'] && $trough['index'] < $lastPeak['index']) {
                    $troughBetween = $trough;
                    break;
                }
            }
            
            if ($troughBetween && $closes[$count - 1] < $troughBetween['price']) {
                $patterns[] = [
                    'date' => $candles[$count - 1]['date'],
                    'pattern' => 'Double Top (Bearish Reversal)'
                ];
            }
        }
    }
    
    // Double Bottom
    if (count($troughs) >= 2) {
        $lastTrough = $troughs[count($troughs) - 1];
        $secondLastTrough = $troughs[count($troughs) - 2];
        
        if (abs($lastTrough['price'] - $secondLastTrough['price']) / $lastTrough['price'] < 0.03) {
            $peakBetween = null;
            foreach ($peaks as $peak) {
                if ($peak['index'] > $secondLastTrough['index'] && $peak['index'] < $lastTrough['index']) {
                    $peakBetween = $peak;
                    break;
                }
            }
            
            if ($peakBetween && $closes[$count - 1] > $peakBetween['price']) {
                $patterns[] = [
                    'date' => $candles[$count - 1]['date'],
                    'pattern' => 'Double Bottom (Bullish Reversal)'
                ];
            }
        }
    }
    
    return $patterns;
}
?>