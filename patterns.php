<?php

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
        
        // ========== SINGLE CANDLESTICK PATTERNS ==========
        
        // Doji
        if ($body < ($range * 0.1) && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Doji"];
        }
        
        // Dragonfly Doji
        if ($body < 0.1 && $upperShadow < 0.1 && $lowerShadow > 2 * $body && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Dragonfly Doji"];
        }
        
        // Gravestone Doji
        if ($body < 0.1 && $lowerShadow < 0.1 && $upperShadow > 2 * $body && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Gravestone Doji"];
        }
        
        // Long-Legged Doji
        if ($body < ($range * 0.1) && $upperShadow > ($range * 0.4) && $lowerShadow > ($range * 0.4)) {
            $results[] = ["date" => $date, "pattern" => "Long-Legged Doji"];
        }
        
        // Hammer (Bullish)
        if ($lowerShadow > 2 * $body && $upperShadow < $body && $isBullish && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Hammer"];
        }
        
        // Hanging Man (Bearish - same shape as hammer but in uptrend context)
        if ($lowerShadow > 2 * $body && $upperShadow < $body && $isBearish && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Hanging Man"];
        }
        
        // Inverted Hammer (Bullish)
        if ($upperShadow > 2 * $body && $lowerShadow < $body && $isBullish && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Inverted Hammer"];
        }
        
        // Shooting Star (Bearish)
        if ($upperShadow > 2 * $body && $lowerShadow < $body && $isBearish && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Shooting Star"];
        }
        
        // Marubozu (Bullish)
        if ($upperShadow < 0.1 && $lowerShadow < 0.1 && $isBullish) {
            $results[] = ["date" => $date, "pattern" => "White Marubozu"];
        }
        
        // Marubozu (Bearish)
        if ($upperShadow < 0.1 && $lowerShadow < 0.1 && $isBearish) {
            $results[] = ["date" => $date, "pattern" => "Black Marubozu"];
        }
        
        // Spinning Top
        if ($body < ($range * 0.3) && $upperShadow > ($range * 0.3) && $lowerShadow > ($range * 0.3) && $range > 0) {
            $results[] = ["date" => $date, "pattern" => "Spinning Top"];
        }
        
        // ========== TWO-CANDLE PATTERNS ==========
        if ($i > 0) {
            $prev = $candles[$i - 1];
            $prevOpen = $prev['open'];
            $prevClose = $prev['close'];
            $prevHigh = $prev['high'];
            $prevLow = $prev['low'];
            $prevBody = abs($prevClose - $prevOpen);
            $prevIsBullish = $prevClose > $prevOpen;
            $prevIsBearish = $prevClose < $prevOpen;
            
            // Bullish Engulfing
            if ($prevIsBearish && $isBullish && $close > $prevOpen && $open < $prevClose) {
                $results[] = ["date" => $date, "pattern" => "Bullish Engulfing"];
            }
            
            // Bearish Engulfing
            if ($prevIsBullish && $isBearish && $open > $prevClose && $close < $prevOpen) {
                $results[] = ["date" => $date, "pattern" => "Bearish Engulfing"];
            }
            
            // Bullish Harami
            if ($prevIsBearish && $isBullish && $open > $prevClose && $close < $prevOpen) {
                $results[] = ["date" => $date, "pattern" => "Bullish Harami"];
            }
            
            // Bearish Harami
            if ($prevIsBullish && $isBearish && $open < $prevClose && $close > $prevOpen) {
                $results[] = ["date" => $date, "pattern" => "Bearish Harami"];
            }
            
            // Bullish Harami Cross (Doji inside)
            if ($prevIsBearish && abs($body) < 0.1 && $open > $prevClose && $close < $prevOpen) {
                $results[] = ["date" => $date, "pattern" => "Bullish Harami Cross"];
            }
            
            // Bearish Harami Cross (Doji inside)
            if ($prevIsBullish && abs($body) < 0.1 && $open < $prevClose && $close > $prevOpen) {
                $results[] = ["date" => $date, "pattern" => "Bearish Harami Cross"];
            }
            
            // Piercing Line
            $prevMidpoint = ($prevOpen + $prevClose) / 2;
            if ($prevIsBearish && $isBullish && $close > $prevMidpoint && $open < $prevClose) {
                $results[] = ["date" => $date, "pattern" => "Piercing Line"];
            }
            
            // Dark Cloud Cover
            if ($prevIsBullish && $isBearish && $close < $prevMidpoint && $open > $prevClose) {
                $results[] = ["date" => $date, "pattern" => "Dark Cloud Cover"];
            }
            
            // Inside Bar
            if ($high < $prevHigh && $low > $prevLow) {
                $results[] = ["date" => $date, "pattern" => "Inside Bar"];
            }
            
            // Outside Bar
            if ($high > $prevHigh && $low < $prevLow) {
                $results[] = ["date" => $date, "pattern" => "Outside Bar"];
            }
            
            // Tweezer Bottom
            if (abs($low - $prevLow) < 0.01 && $prevIsBearish && $isBullish) {
                $results[] = ["date" => $date, "pattern" => "Tweezer Bottom"];
            }
            
            // Tweezer Top
            if (abs($high - $prevHigh) < 0.01 && $prevIsBullish && $isBearish) {
                $results[] = ["date" => $date, "pattern" => "Tweezer Top"];
            }
            
            // Meeting Lines (Bullish)
            if ($prevIsBearish && $isBullish && abs($prevClose - $close) < 0.01) {
                $results[] = ["date" => $date, "pattern" => "Meeting Lines (Bullish)"];
            }
            
            // Meeting Lines (Bearish)
            if ($prevIsBullish && $isBearish && abs($prevClose - $close) < 0.01) {
                $results[] = ["date" => $date, "pattern" => "Meeting Lines (Bearish)"];
            }
            
            // Separating Lines (Bullish Continuation)
            if ($prevIsBearish && $isBullish && abs($prevOpen - $open) < 0.01 && $close > $prevHigh) {
                $results[] = ["date" => $date, "pattern" => "Separating Lines (Bullish)"];
            }
            
            // Separating Lines (Bearish Continuation)
            if ($prevIsBullish && $isBearish && abs($prevOpen - $open) < 0.01 && $close < $prevLow) {
                $results[] = ["date" => $date, "pattern" => "Separating Lines (Bearish)"];
            }
            
            // Kicking (Bullish)
            if ($prevIsBearish && $isBullish && $open > $prevHigh && abs($prevBody) < 0.1 && $body < 0.1) {
                $results[] = ["date" => $date, "pattern" => "Kicking (Bullish)"];
            }
            
            // Kicking (Bearish)
            if ($prevIsBullish && $isBearish && $open < $prevLow && abs($prevBody) < 0.1 && $body < 0.1) {
                $results[] = ["date" => $date, "pattern" => "Kicking (Bearish)"];
            }
        }
        
        // ========== THREE-CANDLE PATTERNS ==========
        if ($i >= 2) {
            $c1 = $candles[$i - 2];
            $c2 = $candles[$i - 1];
            $c3 = $candles[$i];
            
            $c1Bullish = $c1['close'] > $c1['open'];
            $c1Bearish = $c1['close'] < $c1['open'];
            $c2Body = abs($c2['close'] - $c2['open']);
            $c2Range = $c2['high'] - $c2['low'];
            $c3Bullish = $c3['close'] > $c3['open'];
            $c3Bearish = $c3['close'] < $c3['open'];
            
            // Morning Star
            if ($c1Bearish && $c2Body < ($c2Range * 0.1) && $c3Bullish && $c3['close'] > $c1['open']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Morning Star"];
            }
            
            // Evening Star
            if ($c1Bullish && $c2Body < ($c2Range * 0.1) && $c3Bearish && $c3['close'] < $c1['open']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Evening Star"];
            }
            
            // Three White Soldiers
            if ($c1Bullish && $c2Bullish && $c3Bullish && 
                $c2['open'] > $c1['open'] && $c3['open'] > $c2['open'] &&
                $c2['close'] > $c1['close'] && $c3['close'] > $c2['close']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three White Soldiers"];
            }
            
            // Three Black Crows
            if ($c1Bearish && $c2Bearish && $c3Bearish && 
                $c2['open'] < $c1['open'] && $c3['open'] < $c2['open'] &&
                $c2['close'] < $c1['close'] && $c3['close'] < $c2['close']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Black Crows"];
            }
            
            // Three Inside Up
            if ($c1Bearish && $c2Bullish && $c3Bullish && 
                $c2['open'] > $c1['close'] && $c2['close'] < $c1['open'] &&
                $c3['close'] > $c2['close'] && $c3['close'] > $c1['open']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Inside Up"];
            }
            
            // Three Inside Down
            if ($c1Bullish && $c2Bearish && $c3Bearish && 
                $c2['open'] < $c1['close'] && $c2['close'] > $c1['open'] &&
                $c3['close'] < $c2['close'] && $c3['close'] < $c1['open']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Inside Down"];
            }
            
            // Three Outside Up
            if ($c1Bearish && $c2Bullish && $c2['close'] > $c1['open'] && $c2['open'] < $c1['close'] &&
                $c3Bullish && $c3['close'] > $c2['close']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Outside Up"];
            }
            
            // Three Outside Down
            if ($c1Bullish && $c2Bearish && $c2['close'] < $c1['open'] && $c2['open'] > $c1['close'] &&
                $c3Bearish && $c3['close'] < $c2['close']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Outside Down"];
            }
            
            // Abandoned Baby (Bullish)
            if ($c1Bearish && $c2Body < ($c2Range * 0.1) && $c3Bullish &&
                $c2['low'] < min($c1['low'], $c3['low']) &&
                $c2['high'] < min($c1['close'], $c3['open'])) {
                $results[] = ["date" => $c3['date'], "pattern" => "Abandoned Baby (Bullish)"];
            }
            
            // Abandoned Baby (Bearish)
            if ($c1Bullish && $c2Body < ($c2Range * 0.1) && $c3Bearish &&
                $c2['high'] > max($c1['high'], $c3['high']) &&
                $c2['low'] > max($c1['close'], $c3['open'])) {
                $results[] = ["date" => $c3['date'], "pattern" => "Abandoned Baby (Bearish)"];
            }
            
            // Three Stars in the South (Bullish)
            if ($c1Bearish && $c1['low'] == $c1['close'] &&
                $c2Bearish && $c2['close'] < $c1['close'] && $c2['high'] < $c1['high'] &&
                $c3Bullish && $c3['close'] < $c1['close'] && $c3['close'] > $c2['close']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Stars in the South"];
            }
            
            // Upside Tasuki Gap
            if ($c1Bullish && $c2Bullish && $c2['low'] > $c1['high'] &&
                $c3Bearish && $c3['open'] > $c1['high'] && $c3['close'] < $c2['close'] && $c3['close'] > $c1['high']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Upside Tasuki Gap"];
            }
            
            // Downside Tasuki Gap
            if ($c1Bearish && $c2Bearish && $c2['high'] < $c1['low'] &&
                $c3Bullish && $c3['open'] < $c1['low'] && $c3['close'] > $c2['close'] && $c3['close'] < $c1['low']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Downside Tasuki Gap"];
            }
        }
        
        // ========== FOUR+ CANDLE PATTERNS ==========
        if ($i >= 3) {
            $c1 = $candles[$i - 3];
            $c2 = $candles[$i - 2];
            $c3 = $candles[$i - 1];
            $c4 = $candles[$i];
            
            // Rising Three Methods
            if ($c1['close'] > $c1['open'] && $c4['close'] > $c4['open'] &&
                $c4['close'] > $c1['close'] && $c4['open'] > $c1['open'] &&
                $c2['high'] < $c1['high'] && $c2['low'] > $c1['low'] &&
                $c3['high'] < $c1['high'] && $c3['low'] > $c1['low']) {
                $results[] = ["date" => $c4['date'], "pattern" => "Rising Three Methods"];
            }
            
            // Falling Three Methods
            if ($c1['close'] < $c1['open'] && $c4['close'] < $c4['open'] &&
                $c4['close'] < $c1['close'] && $c4['open'] < $c1['open'] &&
                $c2['high'] < $c1['high'] && $c2['low'] > $c1['low'] &&
                $c3['high'] < $c1['high'] && $c3['low'] > $c1['low']) {
                $results[] = ["date" => $c4['date'], "pattern" => "Falling Three Methods"];
            }
        }
        
        // ========== GAP PATTERNS ==========
        if ($i > 0) {
            $prev = $candles[$i - 1];
            
            // Breakaway Gap (Up)
            if ($low > $prev['high'] && $isBullish && $body > $prev['body']) {
                $results[] = ["date" => $date, "pattern" => "Breakaway Gap (Up)"];
            }
            
            // Breakaway Gap (Down)
            if ($high < $prev['low'] && $isBearish && $body > $prev['body']) {
                $results[] = ["date" => $date, "pattern" => "Breakaway Gap (Down)"];
            }
            
            // Island Reversal (Bullish)
            if ($i >= 2) {
                $prev2 = $candles[$i - 2];
                if ($prev2['close'] < $prev2['open'] && $prev['low'] > $prev2['high'] &&
                    $isBullish && $high < $prev['low']) {
                    $results[] = ["date" => $date, "pattern" => "Island Reversal (Bullish)"];
                }
                
                // Island Reversal (Bearish)
                if ($prev2['close'] > $prev2['open'] && $prev['high'] < $prev2['low'] &&
                    $isBearish && $low > $prev['high']) {
                    $results[] = ["date" => $date, "pattern" => "Island Reversal (Bearish)"];
                }
            }
        }
    }
    
    // Remove duplicates (same pattern on same date)
    $uniqueResults = [];
    foreach ($results as $result) {
        $key = $result['date'] . '|' . $result['pattern'];
        if (!isset($uniqueResults[$key])) {
            $uniqueResults[$key] = $result;
        }
    }
    
    return array_values($uniqueResults);
}
?>