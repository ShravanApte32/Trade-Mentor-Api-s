<?php
$apiKey = "O0C218J148Z244X6"; //1I54MQDPP5STHEWF  //O0C218J148Z244X6
$symbols = explode(',', $_GET['symbol'] ?? 'RELIANCE.BSE,TCS.BSE,INFY.BSE');

require_once 'db.php';

// Reset the candles table (optional, remove if you want to preserve data)
$conn->query("DELETE FROM candles;");
$conn->query("ALTER TABLE candles AUTO_INCREMENT = 1;");

// Timeframe
$requestedTimeframe = $_GET['timeframe'] ?? '1D';
$function = '';
$interval = '';

switch ($requestedTimeframe) {
    case '1D':
        $function = 'TIME_SERIES_DAILY';
        break;
    case '1W':
        $function = 'TIME_SERIES_WEEKLY';
        break;
    case '1M':
        $function = 'TIME_SERIES_MONTHLY';
        break;
    case '5min':
        $function = 'TIME_SERIES_INTRADAY';
        $interval = '5min';
        break;
    default:
        $function = 'TIME_SERIES_DAILY';
        $requestedTimeframe = '1D';
}

foreach ($symbols as $rawSymbol) {
    $originalSymbol = strtoupper(trim($rawSymbol));

    // Normalize the symbol to always end with .BSE
    $normalizedSymbol = preg_replace('/\.b[a-z]*$/i', '', $originalSymbol);
    $normalizedSymbol .= '.BSE';

    $symbol = $normalizedSymbol;

    $url = "https://www.alphavantage.co/query?function=$function&symbol=$symbol&apikey=$apiKey";
    if ($function === 'TIME_SERIES_INTRADAY') {
        $url .= "&interval=$interval&outputsize=compact";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    $seriesKey = match ($function) {
        'TIME_SERIES_DAILY' => "Time Series (Daily)",
        'TIME_SERIES_WEEKLY' => "Weekly Time Series",
        'TIME_SERIES_MONTHLY' => "Monthly Time Series",
        'TIME_SERIES_INTRADAY' => "Time Series ($interval)",
        default => ""
    };

    if (!isset($data[$seriesKey])) {
        echo json_encode([
            "status" => "error",
            "message" => "No data for $symbol",
            "debug" => $data
        ]);
        continue;
    }

    foreach ($data[$seriesKey] as $date => $candle) {
        $open = (float)$candle["1. open"];
        $high = (float)$candle["2. high"];
        $low = (float)$candle["3. low"];
        $close = (float)$candle["4. close"];
        $volume = (int)$candle["5. volume"];
        $timeframe = $requestedTimeframe;

        // First try INSERT
        $insertStmt = $conn->prepare("INSERT INTO candles 
    (stock_symbol, date, open, high, low, close, volume, timeframe)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $insertStmt->bind_param(
            "ssddddds",
            $symbol,
            $date,
            $open,
            $high,
            $low,
            $close,
            $volume,
            $timeframe
        );





        if (!$insertStmt->execute()) {

            if ($conn->errno == 1062) {
                // Duplicate entry – run UPDATE
                $updateStmt = $conn->prepare("UPDATE candles SET 
    open=?, high=?, low=?, close=?, volume=?, timeframe=?, stock_symbol=?
    WHERE stock_symbol=? AND date=?");

                $updateStmt->bind_param(
                    "ddddsssss",
                    $open,
                    $high,
                    $low,
                    $close,
                    $volume,
                    $timeframe,
                    $symbol,
                    $symbol,
                    $date
                );


                if (!$updateStmt->execute()) {
                    error_log("Update error: " . $updateStmt->error);
                }

                $updateStmt->close();
            } else {
                error_log("Insert error: " . $insertStmt->error);
            }
        }

        $insertStmt->close();
    }
}

echo json_encode(["status" => "success", "message" => "All data inserted/updated for timeframe $requestedTimeframe."]);
