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
        $lowerShadow = min($open, $close) - $low;
        $upperShadow = $high - max($open, $close);

        // Doji
        if ($body < 0.5 && ($high - $low) > 2 * $body) {
            $results[] = ["date" => $date, "pattern" => "Doji"];
        }

        // Dragonfly Doji
        if ($body < 0.1 && $upperShadow < 0.1 && $lowerShadow > 2 * $body) {
            $results[] = ["date" => $date, "pattern" => "Dragonfly Doji"];
        }

        // Gravestone Doji
        if ($body < 0.1 && $lowerShadow < 0.1 && $upperShadow > 2 * $body) {
            $results[] = ["date" => $date, "pattern" => "Gravestone Doji"];
        }

        // Hammer
        if ($lowerShadow > 2 * $body && $upperShadow < $body && $close > $open) {
            $results[] = ["date" => $date, "pattern" => "Hammer"];
        }

        // Inverted Hammer
        if ($upperShadow > 2 * $body && $lowerShadow < $body && $close > $open) {
            $results[] = ["date" => $date, "pattern" => "Inverted Hammer"];
        }

        // Shooting Star
        if ($upperShadow > 2 * $body && $lowerShadow < $body && $close < $open) {
            $results[] = ["date" => $date, "pattern" => "Shooting Star"];
        }

        // Marubozu
        if ($upperShadow < 0.1 && $lowerShadow < 0.1) {
            $results[] = ["date" => $date, "pattern" => ($close > $open ? "Bullish Marubozu" : "Bearish Marubozu")];
        }

        // Spinning Top
        if ($body < ($high - $low) * 0.3 && $upperShadow > 0.3 && $lowerShadow > 0.3) {
            $results[] = ["date" => $date, "pattern" => "Spinning Top"];
        }

        // Engulfing (requires previous candle)
        if ($i > 0) {
            $prev = $candles[$i - 1];

            // Bullish Engulfing
            if ($prev['close'] < $prev['open'] && $close > $open && $close > $prev['open'] && $open < $prev['close']) {
                $results[] = ["date" => $date, "pattern" => "Bullish Engulfing"];
            }

            // Bearish Engulfing
            if ($prev['close'] > $prev['open'] && $close < $open && $open > $prev['close'] && $close < $prev['open']) {
                $results[] = ["date" => $date, "pattern" => "Bearish Engulfing"];
            }

            // Bullish Harami
            if ($prev['close'] < $prev['open'] && $open > $close && $open < $prev['close'] && $close > $prev['open']) {
                $results[] = ["date" => $date, "pattern" => "Bullish Harami"];
            }

            // Bearish Harami
            if ($prev['close'] > $prev['open'] && $open < $close && $close < $prev['open'] && $open > $prev['close']) {
                $results[] = ["date" => $date, "pattern" => "Bearish Harami"];
            }

            // Inside Bar
            if ($high < $prev['high'] && $low > $prev['low']) {
                $results[] = ["date" => $date, "pattern" => "Inside Bar"];
            }

            // Outside Bar
            if ($high > $prev['high'] && $low < $prev['low']) {
                $results[] = ["date" => $date, "pattern" => "Outside Bar"];
            }

            // Piercing Line
            if ($prev['close'] < $prev['open'] && $close > $open && $close > (($prev['open'] + $prev['close']) / 2) && $open < $prev['close']) {
                $results[] = ["date" => $date, "pattern" => "Piercing Line"];
            }

            // Dark Cloud Cover
            if ($prev['close'] > $prev['open'] && $close < $open && $close < (($prev['open'] + $prev['close']) / 2) && $open > $prev['close']) {
                $results[] = ["date" => $date, "pattern" => "Dark Cloud Cover"];
            }

            // Tweezer Bottom
            if (abs($low - $prev['low']) < 0.2 && $prev['close'] < $prev['open'] && $close > $open) {
                $results[] = ["date" => $date, "pattern" => "Tweezer Bottom"];
            }

            // Tweezer Top
            if (abs($high - $prev['high']) < 0.2 && $prev['close'] > $prev['open'] && $close < $open) {
                $results[] = ["date" => $date, "pattern" => "Tweezer Top"];
            }
        }

        // Three Candle Patterns
        if ($i >= 2) {
            $c1 = $candles[$i - 2];
            $c2 = $candles[$i - 1];
            $c3 = $candles[$i];

            if ($c1['close'] < $c1['open'] && abs($c2['close'] - $c2['open']) < 0.5 && $c3['close'] > $c3['open'] && $c3['close'] > $c1['open']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Morning Star"];
            }

            if ($c1['close'] > $c1['open'] && abs($c2['close'] - $c2['open']) < 0.5 && $c3['close'] < $c3['open'] && $c3['close'] < $c1['open']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Evening Star"];
            }

            if ($c1['close'] < $c1['open'] && $c2['close'] > $c2['open'] && $c3['close'] > $c3['open'] && $c2['open'] > $c1['close'] && $c3['open'] > $c2['close']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Inside Up"];
            }

            if ($c1['close'] > $c1['open'] && $c2['close'] < $c2['open'] && $c3['close'] < $c3['open'] && $c2['open'] < $c1['close'] && $c3['open'] < $c2['close']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Inside Down"];
            }

            if ($c1['close'] < $c1['open'] && abs($c2['open'] - $c2['close']) < 0.1 && $c3['close'] > $c3['open'] && $c2['low'] < min($c1['low'], $c3['low'])) {
                $results[] = ["date" => $c3['date'], "pattern" => "Abandoned Baby (Bullish)"];
            }

            if ($c1['close'] > $c1['open'] && abs($c2['open'] - $c2['close']) < 0.1 && $c3['close'] < $c3['open'] && $c2['high'] > max($c1['high'], $c3['high'])) {
                $results[] = ["date" => $c3['date'], "pattern" => "Abandoned Baby (Bearish)"];
            }

            if ($c1['close'] > $c1['open'] && $c2['close'] > $c2['open'] && $c3['close'] > $c3['open'] && $c2['open'] > $c1['open'] && $c3['open'] > $c2['open']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three White Soldiers"];
            }

            if ($c1['close'] < $c1['open'] && $c2['close'] < $c2['open'] && $c3['close'] < $c3['open'] && $c2['open'] < $c1['open'] && $c3['open'] < $c2['open']) {
                $results[] = ["date" => $c3['date'], "pattern" => "Three Black Crows"];
            }
        }
    }

    return $results;
}
