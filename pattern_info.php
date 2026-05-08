<?php
header('Content-Type: application/json');

function generateCandleImage($type)
{
    $width = 200;
    $height = 150;
    $im = imagecreatetruecolor($width, $height);

    // Colors
    $white = imagecolorallocate($im, 255, 255, 255);
    $green = imagecolorallocate($im, 0, 200, 0);
    $red = imagecolorallocate($im, 255, 0, 0);
    $black = imagecolorallocate($im, 0, 0, 0);
    $gray = imagecolorallocate($im, 200, 200, 200);

    // Background and grid
    imagefill($im, 0, 0, $white);
    for ($i = 0; $i < $width; $i += 20) imageline($im, $i, 0, $i, $height, $gray);
    for ($i = 0; $i < $height; $i += 20) imageline($im, 0, $i, $width, $i, $gray);

    // Pattern rendering
    switch ($type) {
        // 1-Candle Patterns
        case 'doji':
            // Long wick, body centered and thin
            imageline($im, 100, 30, 100, 120, $black); // wick
            imageline($im, 90, 75, 110, 75, $black);   // tiny body (horizontal line)
            break;

        case 'dragonfly_doji':
            // Open, high, and close are at top; long lower shadow
            imageline($im, 100, 30, 100, 120, $black); // long shadow
            imageline($im, 90, 30, 110, 30, $black);   // body at top
            break;

        case 'gravestone_doji':
            // Open, low, and close are at bottom; long upper shadow
            imageline($im, 100, 30, 100, 120, $black); // long shadow
            imageline($im, 90, 120, 110, 120, $black); // body at bottom
            break;

        case 'hammer':
            // Small body at top, long lower shadow
            imagefilledrectangle($im, 90, 50, 110, 70, $green); // body
            imageline($im, 100, 70, 100, 110, $black); // long lower shadow
            imageline($im, 100, 30, 100, 50, $black); // short upper shadow
            break;

        case 'inverted_hammer':
            // Small body at bottom, long upper shadow
            imagefilledrectangle($im, 90, 80, 110, 100, $green); // body
            imageline($im, 100, 40, 100, 80, $black); // long upper shadow
            imageline($im, 100, 100, 100, 120, $black); // short lower shadow
            break;

        case 'shooting_star':
            // Small body near bottom, long upper shadow
            imagefilledrectangle($im, 90, 90, 110, 110, $red); // body
            imageline($im, 100, 40, 100, 90, $black); // long upper shadow
            imageline($im, 100, 110, 100, 120, $black); // short lower shadow
            break;

        case 'bullish_marubozu':
            // Full green body, no wicks
            imagefilledrectangle($im, 80, 30, 120, 120, $green);
            break;

        case 'bearish_marubozu':
            // Full red body, no wicks
            imagefilledrectangle($im, 80, 30, 120, 120, $red);
            break;

        case 'spinning_top':
            // Small real body with upper & lower shadows
            imagefilledrectangle($im, 90, 75, 110, 95, $black); // body
            imageline($im, 100, 45, 100, 75, $black); // upper shadow
            imageline($im, 100, 95, 100, 120, $black); // lower shadow
            break;


        // 2-Candle Patterns
        case 'bullish_engulfing':
            // First red candle (small)
            imagefilledrectangle($im, 50, 80, 70, 110, $red);
            // Second green candle (engulfs the first)
            imagefilledrectangle($im, 80, 60, 110, 120, $green);
            break;

        case 'bearish_engulfing':
            // First green candle (small)
            imagefilledrectangle($im, 50, 80, 70, 110, $green);
            // Second red candle (engulfs the first)
            imagefilledrectangle($im, 80, 60, 110, 120, $red);
            break;

        case 'bullish_harami':
            // Large red candle
            imagefilledrectangle($im, 50, 60, 90, 120, $red);
            // Small green candle inside the body
            imagefilledrectangle($im, 100, 80, 120, 100, $green);
            break;

        case 'bearish_harami':
            // Large green candle
            imagefilledrectangle($im, 50, 60, 90, 120, $green);
            // Small red candle inside the body
            imagefilledrectangle($im, 100, 80, 120, 100, $red);
            break;

        case 'inside_bar':
            // Larger green candle
            imagefilledrectangle($im, 50, 60, 90, 120, $green);
            // Smaller black candle inside
            imagefilledrectangle($im, 100, 80, 120, 100, $black);
            break;

        case 'outside_bar':
            // Smaller red candle first
            imagefilledrectangle($im, 50, 80, 70, 100, $red);
            // Larger green candle covering it
            imagefilledrectangle($im, 80, 60, 120, 120, $green);
            break;

        case 'piercing_line':
            // First red candle
            imagefilledrectangle($im, 50, 60, 80, 110, $red);
            // Second green candle opens below close, closes above midpoint
            imagefilledrectangle($im, 90, 90, 120, 50, $green);
            break;

        case 'dark_cloud_cover':
            // First green candle
            imagefilledrectangle($im, 50, 110, 80, 60, $green);
            // Second red candle opens higher, closes below midpoint
            imagefilledrectangle($im, 90, 50, 120, 100, $red);
            break;

        case 'tweezer_bottom':
            // Red candle
            imagefilledrectangle($im, 50, 80, 80, 120, $red);
            // Green candle with same low
            imagefilledrectangle($im, 90, 90, 120, 120, $green);
            break;

        case 'tweezer_top':
            // Green candle
            imagefilledrectangle($im, 50, 60, 80, 100, $green);
            // Red candle with same high
            imagefilledrectangle($im, 90, 60, 120, 90, $red);
            break;

        case 'morning_star':
            // First red candle
            imagefilledrectangle($im, 30, 80, 60, 120, $red);
            // Second candle (small - indecision)
            imagefilledrectangle($im, 70, 90, 90, 110, $black);
            // Third green candle
            imagefilledrectangle($im, 100, 60, 130, 100, $green);
            break;

        case 'evening_star':
            // First green candle
            imagefilledrectangle($im, 30, 60, 60, 100, $green);
            // Second candle (small - indecision)
            imagefilledrectangle($im, 70, 70, 90, 90, $black);
            // Third red candle
            imagefilledrectangle($im, 100, 80, 130, 120, $red);
            break;

        case 'three_inside_up':
            // First large red candle
            imagefilledrectangle($im, 30, 60, 70, 120, $red);
            // Second small green inside body
            imagefilledrectangle($im, 75, 80, 95, 110, $green);
            // Third confirming green candle
            imagefilledrectangle($im, 100, 50, 140, 90, $green);
            break;

        case 'three_inside_down':
            // First large green candle
            imagefilledrectangle($im, 30, 60, 70, 120, $green);
            // Second small red inside body
            imagefilledrectangle($im, 75, 80, 95, 110, $red);
            // Third confirming red candle
            imagefilledrectangle($im, 100, 90, 140, 130, $red);
            break;

        case 'abandoned_baby_bullish':
            // Red candle
            imagefilledrectangle($im, 30, 80, 60, 120, $red);
            // Doji with gap
            imageline($im, 85, 95, 85, 95, $black);
            imageline($im, 75, 95, 95, 95, $black);
            // Green candle after gap
            imagefilledrectangle($im, 100, 60, 130, 100, $green);
            break;

        case 'abandoned_baby_bearish':
            // Green candle
            imagefilledrectangle($im, 30, 60, 60, 100, $green);
            // Doji with gap
            imageline($im, 85, 80, 85, 80, $black);
            imageline($im, 75, 80, 95, 80, $black);
            // Red candle after gap
            imagefilledrectangle($im, 100, 90, 130, 130, $red);
            break;

        case 'three_white_soldiers':
            // Three green candles stepping upward
            imagefilledrectangle($im, 20, 100, 50, 130, $green);
            imagefilledrectangle($im, 60, 80, 90, 110, $green);
            imagefilledrectangle($im, 100, 60, 130, 90, $green);
            break;

        case 'three_black_crows':
            // Three red candles stepping downward
            imagefilledrectangle($im, 20, 60, 50, 90, $red);
            imagefilledrectangle($im, 60, 80, 90, 110, $red);
            imagefilledrectangle($im, 100, 100, 130, 130, $red);
            break;

        default:
            imagefilledrectangle($im, 90, 50, 110, 100, $green);
            imageline($im, 100, 30, 100, 50, $black);
            imageline($im, 100, 100, 100, 120, $black);
    }

    ob_start();
    imagepng($im);
    $base64 = base64_encode(ob_get_clean());
    imagedestroy($im);

    return 'data:image/png;base64,' . $base64;
}

$patternInfo = [
    // 1-Candle Patterns
    "Doji" => [
        "title" => "Doji",
        "type" => "Indecision",
        "description" => "Open and close are nearly equal. Indicates indecision or potential reversal.",
        "trade_tip" => "Wait for confirmation candle. Avoid trading Doji alone.",
        "image" => generateCandleImage('doji')
    ],
    "Dragonfly Doji" => [
        "title" => "Dragonfly Doji",
        "type" => "Bullish Reversal",
        "description" => "Appears after downtrend with long lower shadow and little to no upper shadow.",
        "trade_tip" => "Wait for bullish confirmation before entering.",
        "image" => generateCandleImage('dragonfly_doji')
    ],
    "Gravestone Doji" => [
        "title" => "Gravestone Doji",
        "type" => "Bearish Reversal",
        "description" => "Appears after uptrend with long upper shadow and little to no lower shadow.",
        "trade_tip" => "Watch for confirmation with red candle.",
        "image" => generateCandleImage('gravestone_doji')
    ],
    "Hammer" => [
        "title" => "Hammer",
        "type" => "Bullish Reversal",
        "description" => "Small body and long lower shadow. Appears after downtrend.",
        "trade_tip" => "Confirmation needed with green candle. Stop-loss below the wick.",
        "image" => generateCandleImage('hammer')
    ],
    "Inverted Hammer" => [
        "title" => "Inverted Hammer",
        "type" => "Bullish Reversal",
        "description" => "Small body with long upper shadow. Appears after downtrend.",
        "trade_tip" => "Look for bullish follow-up candle.",
        "image" => generateCandleImage('inverted_hammer')
    ],
    "Shooting Star" => [
        "title" => "Shooting Star",
        "type" => "Bearish Reversal",
        "description" => "Small body near session low with long upper shadow. Appears after uptrend.",
        "trade_tip" => "Confirmation with red candle needed.",
        "image" => generateCandleImage('shooting_star')
    ],
    "Bullish Marubozu" => [
        "title" => "Bullish Marubozu",
        "type" => "Bullish Continuation",
        "description" => "Full green candle without shadows. Strong buying pressure.",
        "trade_tip" => "Can signal strong trend continuation.",
        "image" => generateCandleImage('bullish_marubozu')
    ],
    "Bearish Marubozu" => [
        "title" => "Bearish Marubozu",
        "type" => "Bearish Continuation",
        "description" => "Full red candle without shadows. Strong selling pressure.",
        "trade_tip" => "May signal continuation of downtrend.",
        "image" => generateCandleImage('bearish_marubozu')
    ],
    "Spinning Top" => [
        "title" => "Spinning Top",
        "type" => "Indecision",
        "description" => "Small body with upper and lower shadows. Shows indecision.",
        "trade_tip" => "Avoid trading without confirmation.",
        "image" => generateCandleImage('spinning_top')
    ],

    // 2-Candle Patterns
    "Bullish Engulfing" => [
        "title" => "Bullish Engulfing",
        "type" => "Bullish Reversal",
        "description" => "Green candle engulfs previous red candle. Signals buying interest.",
        "trade_tip" => "Buy above the engulfing high. SL below both candles.",
        "image" => generateCandleImage('bullish_engulfing')
    ],
    "Bearish Engulfing" => [
        "title" => "Bearish Engulfing",
        "type" => "Bearish Reversal",
        "description" => "Red candle engulfs previous green candle. Signals selling pressure.",
        "trade_tip" => "Sell below the engulfing low. SL above both candles.",
        "image" => generateCandleImage('bearish_engulfing')
    ],
    "Bullish Harami" => [
        "title" => "Bullish Harami",
        "type" => "Bullish Reversal",
        "description" => "Small green body inside previous red candle body.",
        "trade_tip" => "Confirmation candle needed to enter.",
        "image" => generateCandleImage('bullish_harami')
    ],
    "Bearish Harami" => [
        "title" => "Bearish Harami",
        "type" => "Bearish Reversal",
        "description" => "Small red body inside previous green candle body.",
        "trade_tip" => "Wait for confirmation with red candle.",
        "image" => generateCandleImage('bearish_harami')
    ],
    "Inside Bar" => [
        "title" => "Inside Bar",
        "type" => "Indecision / Breakout",
        "description" => "Current candle is fully inside previous candle's high-low range.",
        "trade_tip" => "Breakout in either direction possible. Use stop orders.",
        "image" => generateCandleImage('inside_bar')
    ],
    "Outside Bar" => [
        "title" => "Outside Bar",
        "type" => "Volatility",
        "description" => "Current candle engulfs previous candle's range.",
        "trade_tip" => "Indicates strength in direction of breakout.",
        "image" => generateCandleImage('outside_bar')
    ],
    "Piercing Line" => [
        "title" => "Piercing Line",
        "type" => "Bullish Reversal",
        "description" => "Bullish candle opens below prior red candle and closes above mid-point.",
        "trade_tip" => "Enter on continuation green candle.",
        "image" => generateCandleImage('piercing_line')
    ],
    "Dark Cloud Cover" => [
        "title" => "Dark Cloud Cover",
        "type" => "Bearish Reversal",
        "description" => "Bearish candle opens above and closes below mid-point of previous green candle.",
        "trade_tip" => "Sell on confirmation red candle.",
        "image" => generateCandleImage('dark_cloud_cover')
    ],
    "Tweezer Bottom" => [
        "title" => "Tweezer Bottom",
        "type" => "Bullish Reversal",
        "description" => "Two candles with similar lows, showing buying support.",
        "trade_tip" => "Confirm with bullish follow-up candle.",
        "image" => generateCandleImage('tweezer_bottom')
    ],
    "Tweezer Top" => [
        "title" => "Tweezer Top",
        "type" => "Bearish Reversal",
        "description" => "Two candles with similar highs, showing resistance.",
        "trade_tip" => "Watch for breakdown confirmation.",
        "image" => generateCandleImage('tweezer_top')
    ],

    // 3-Candle Patterns
    "Morning Star" => [
        "title" => "Morning Star",
        "type" => "Bullish Reversal",
        "description" => "Red candle → indecision → green candle breaking above. Strong reversal signal.",
        "trade_tip" => "Enter on green breakout. Stop-loss below middle candle.",
        "image" => generateCandleImage('morning_star')
    ],
    "Evening Star" => [
        "title" => "Evening Star",
        "type" => "Bearish Reversal",
        "description" => "Green candle → indecision → red candle breaking below. Strong bearish signal.",
        "trade_tip" => "Enter on red breakdown. Stop-loss above middle candle.",
        "image" => generateCandleImage('evening_star')
    ],
    "Three Inside Up" => [
        "title" => "Three Inside Up",
        "type" => "Bullish Reversal",
        "description" => "Bearish candle, followed by a small green candle, and then a strong green candle confirming reversal.",
        "trade_tip" => "Buy on breakout above third candle.",
        "image" => generateCandleImage('three_inside_up')
    ],
    "Three Inside Down" => [
        "title" => "Three Inside Down",
        "type" => "Bearish Reversal",
        "description" => "Bullish candle, then small red candle, then strong red confirmation candle.",
        "trade_tip" => "Sell on breakdown below third candle.",
        "image" => generateCandleImage('three_inside_down')
    ],
    "Abandoned Baby (Bullish)" => [
        "title" => "Abandoned Baby (Bullish)",
        "type" => "Bullish Reversal",
        "description" => "Gap down Doji between two opposite candles. Rare but strong signal.",
        "trade_tip" => "Wait for confirmation with large green candle.",
        "image" => generateCandleImage('abandoned_baby_bullish')
    ],
    "Abandoned Baby (Bearish)" => [
        "title" => "Abandoned Baby (Bearish)",
        "type" => "Bearish Reversal",
        "description" => "Gap up Doji between two opposite candles. Rare but strong signal.",
        "trade_tip" => "Confirmation with red candle is critical.",
        "image" => generateCandleImage('abandoned_baby_bearish')
    ],
    "Three White Soldiers" => [
        "title" => "Three White Soldiers",
        "type" => "Bullish Reversal",
        "description" => "Three consecutive bullish candles with higher closes. Strong uptrend continuation.",
        "trade_tip" => "Enter with SL below first candle's low.",
        "image" => generateCandleImage('three_white_soldiers')
    ],
    "Three Black Crows" => [
        "title" => "Three Black Crows",
        "type" => "Bearish Reversal",
        "description" => "Three consecutive bearish candles. Indicates strong selling pressure.",
        "trade_tip" => "Sell with SL above first candle's high.",
        "image" => generateCandleImage('three_black_crows')
    ]
];

$pattern = $_GET['pattern'] ?? '';
$pattern = ucwords(strtolower(str_replace(['_', '-'], ' ', $pattern)));



if (isset($patternInfo[$pattern])) {
    echo json_encode([
        "status" => "success",
        "pattern" => $patternInfo[$pattern]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Pattern not found."
    ]);
}