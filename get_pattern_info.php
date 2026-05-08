<?php
header('Content-Type: application/json');

$patternInfo = [
    // ========== SINGLE CANDLESTICK PATTERNS ==========
    "Hammer" => [
        "title" => "Hammer",
        "type" => "Bullish Reversal",
        "description" => "A small body near the top with a long lower shadow (at least 2x the body). Appears after a downtrend.",
        "trade_tip" => "Wait for a green confirmation candle. Stop-loss below the hammer's low.",
        "image" => "https://example.com/images/hammer.png"
    ],
    "Inverted Hammer" => [
        "title" => "Inverted Hammer",
        "type" => "Bullish Reversal",
        "description" => "Small body at the bottom with a long upper shadow. Appears after a downtrend.",
        "trade_tip" => "Wait for the next day's open to confirm buying pressure. Stop-loss below the low.",
        "image" => "https://example.com/images/inverted_hammer.png"
    ],
    "Shooting Star" => [
        "title" => "Shooting Star",
        "type" => "Bearish Reversal",
        "description" => "Small body near the low with a long upper shadow. Appears after an uptrend.",
        "trade_tip" => "Confirmation needed with a red candle. Stop-loss above the upper wick.",
        "image" => "https://example.com/images/shooting_star.png"
    ],
    "Hanging Man" => [
        "title" => "Hanging Man",
        "type" => "Bearish Reversal",
        "description" => "Looks like a hammer but appears at the top of an uptrend. Small body, long lower shadow.",
        "trade_tip" => "Requires bearish confirmation the next day. Stop-loss above the high.",
        "image" => "https://example.com/images/hanging_man.png"
    ],
    "Doji" => [
        "title" => "Doji",
        "type" => "Indecision",
        "description" => "Open and close are almost equal. Signals market uncertainty or potential reversal.",
        "trade_tip" => "Wait for confirmation. Avoid trading Doji alone.",
        "image" => "https://example.com/images/doji.png"
    ],
    "Long-Legged Doji" => [
        "title" => "Long-Legged Doji",
        "type" => "Indecision",
        "description" => "Long upper and lower shadows with a small body. Indicates extreme volatility and indecision.",
        "trade_tip" => "Wait for the next candle to determine direction.",
        "image" => "https://example.com/images/long_legged_doji.png"
    ],
    "Dragonfly Doji" => [
        "title" => "Dragonfly Doji",
        "type" => "Bullish Reversal",
        "description" => "Open and close at the high of the day with a long lower shadow.",
        "trade_tip" => "Bullish signal when found at support levels. Stop-loss below the low.",
        "image" => "https://example.com/images/dragonfly_doji.png"
    ],
    "Gravestone Doji" => [
        "title" => "Gravestone Doji",
        "type" => "Bearish Reversal",
        "description" => "Open and close at the low of the day with a long upper shadow.",
        "trade_tip" => "Bearish signal when found at resistance levels. Stop-loss above the high.",
        "image" => "https://example.com/images/gravestone_doji.png"
    ],
    "Spinning Top" => [
        "title" => "Spinning Top",
        "type" => "Indecision",
        "description" => "Small body with upper and lower shadows. Indicates consolidation.",
        "trade_tip" => "Neutral pattern. Wait for breakout confirmation.",
        "image" => "https://example.com/images/spinning_top.png"
    ],
    "Marubozu" => [
        "title" => "Marubozu",
        "type" => "Strong Continuation",
        "description" => "A candle with no wicks (shadows). All-white (bullish) or all-black (bearish).",
        "trade_tip" => "Indicates strong momentum. Trade in the direction of the candle.",
        "image" => "https://example.com/images/marubozu.png"
    ],
    "White Marubozu" => [
        "title" => "White Marubozu",
        "type" => "Bullish Continuation",
        "description" => "Long green candle with no shadows. Open = low, close = high.",
        "trade_tip" => "Very strong buying pressure. Buy on breakout.",
        "image" => "https://example.com/images/white_marubozu.png"
    ],
    "Black Marubozu" => [
        "title" => "Black Marubozu",
        "type" => "Bearish Continuation",
        "description" => "Long red candle with no shadows. Open = high, close = low.",
        "trade_tip" => "Very strong selling pressure. Sell on breakdown.",
        "image" => "https://example.com/images/black_marubozu.png"
    ],

    // ========== TWO-CANDLE PATTERNS ==========
    "Bullish Engulfing" => [
        "title" => "Bullish Engulfing",
        "type" => "Bullish Reversal",
        "description" => "A large green candle fully engulfs the previous red candle, signaling buying strength.",
        "trade_tip" => "Enter after breakout of the engulfing high. Stop-loss below the low of both candles.",
        "image" => "https://example.com/images/bullish_engulfing.png"
    ],
    "Bearish Engulfing" => [
        "title" => "Bearish Engulfing",
        "type" => "Bearish Reversal",
        "description" => "A large red candle fully engulfs the previous green candle, signaling selling pressure.",
        "trade_tip" => "Short-selling opportunity. Stop-loss above the high of both candles.",
        "image" => "https://example.com/images/bearish_engulfing.png"
    ],
    "Piercing Line" => [
        "title" => "Piercing Line",
        "type" => "Bullish Reversal",
        "description" => "A green candle closes above the midpoint of the previous red candle.",
        "trade_tip" => "Stronger than hammer. Shows immediate buying pressure. Stop-loss below the pattern low.",
        "image" => "https://example.com/images/piercing_line.png"
    ],
    "Dark Cloud Cover" => [
        "title" => "Dark Cloud Cover",
        "type" => "Bearish Reversal",
        "description" => "A red candle closes below the midpoint of the previous green candle.",
        "trade_tip" => "Strong sell signal. Stop-loss above the pattern high.",
        "image" => "https://example.com/images/dark_cloud_cover.png"
    ],
    "Harami" => [
        "title" => "Harami",
        "type" => "Neutral/Reversal",
        "description" => "A small candle (Doji or Spinning Top) contained within the previous large candle.",
        "trade_tip" => "Indicates loss of momentum. Wait for breakout direction.",
        "image" => "https://example.com/images/harami.png"
    ],
    "Bullish Harami" => [
        "title" => "Bullish Harami",
        "type" => "Bullish Reversal",
        "description" => "A small green candle inside a previous large red candle after a downtrend.",
        "trade_tip" => "Potential reversal. Wait for confirmation with next candle.",
        "image" => "https://example.com/images/bullish_harami.png"
    ],
    "Bearish Harami" => [
        "title" => "Bearish Harami",
        "type" => "Bearish Reversal",
        "description" => "A small red candle inside a previous large green candle after an uptrend.",
        "trade_tip" => "Potential reversal. Wait for confirmation with next candle.",
        "image" => "https://example.com/images/bearish_harami.png"
    ],
    "Inside Bar" => [
        "title" => "Inside Bar",
        "type" => "Continuation/Breakout",
        "description" => "A candle that forms entirely within the range of the previous candle. Indicates consolidation.",
        "trade_tip" => "Enter on breakout above/below the mother bar. Stop-loss at opposite end of mother bar.",
        "image" => "https://example.com/images/inside_bar.png"
    ],
    "Outside Bar" => [
        "title" => "Outside Bar",
        "type" => "Volatility/Reversal",
        "description" => "A candle that completely engulfs the previous candle's range (engulfing pattern).",
        "trade_tip" => "Trade in the direction of the engulfing candle after confirmation.",
        "image" => "https://example.com/images/outside_bar.png"
    ],
    "Tweezer Bottom" => [
        "title" => "Tweezer Bottom",
        "type" => "Bullish Reversal",
        "description" => "Two candles with matching lows, usually a red followed by a green.",
        "trade_tip" => "Support level hold. Buy on green candle close. Stop-loss below the shared low.",
        "image" => "https://example.com/images/tweezer_bottom.png"
    ],
    "Tweezer Top" => [
        "title" => "Tweezer Top",
        "type" => "Bearish Reversal",
        "description" => "Two candles with matching highs, usually a green followed by a red.",
        "trade_tip" => "Resistance level hold. Sell on red candle close. Stop-loss above the shared high.",
        "image" => "https://example.com/images/tweezer_top.png"
    ],

    // ========== THREE-CANDLE PATTERNS ==========
    "Morning Star" => [
        "title" => "Morning Star",
        "type" => "Bullish Reversal",
        "description" => "A 3-candle pattern: long red, small body (Doji), long green. Marks a bottom.",
        "trade_tip" => "Reliable reversal signal. Enter when the 3rd candle closes. Stop-loss below the pattern low.",
        "image" => "https://example.com/images/morning_star.png"
    ],
    "Evening Star" => [
        "title" => "Evening Star",
        "type" => "Bearish Reversal",
        "description" => "A 3-candle pattern: long green, small body (Doji), long red. Marks a top.",
        "trade_tip" => "Reliable reversal signal. Enter short on 3rd candle close. Stop-loss above pattern high.",
        "image" => "https://example.com/images/evening_star.png"
    ],
    "Three White Soldiers" => [
        "title" => "Three White Soldiers",
        "type" => "Bullish Continuation",
        "description" => "Three consecutive long green candles closing near their highs. Strong buying pressure.",
        "trade_tip" => "Very strong bullish momentum. Enter on minor pullbacks.",
        "image" => "https://example.com/images/three_white_soldiers.png"
    ],
    "Three Black Crows" => [
        "title" => "Three Black Crows",
        "type" => "Bearish Continuation",
        "description" => "Three consecutive long red candles closing near their lows. Strong selling pressure.",
        "trade_tip" => "Very strong bearish momentum. Short on minor rallies.",
        "image" => "https://example.com/images/three_black_crows.png"
    ],
    "Three Inside Up" => [
        "title" => "Three Inside Up",
        "type" => "Bullish Reversal",
        "description" => "A Bullish Harami followed by a green candle closing above the first candle's high.",
        "trade_tip" => "Confirmation of reversal. Buy on third candle close.",
        "image" => "https://example.com/images/three_inside_up.png"
    ],
    "Three Inside Down" => [
        "title" => "Three Inside Down",
        "type" => "Bearish Reversal",
        "description" => "A Bearish Harami followed by a red candle closing below the first candle's low.",
        "trade_tip" => "Confirmation of reversal. Sell on third candle close.",
        "image" => "https://example.com/images/three_inside_down.png"
    ],
    "Three Outside Up" => [
        "title" => "Three Outside Up",
        "type" => "Bullish Reversal",
        "description" => "A Bullish Engulfing followed by a green candle closing higher.",
        "trade_tip" => "Strong bullish reversal. Buy on third candle close.",
        "image" => "https://example.com/images/three_outside_up.png"
    ],
    "Three Outside Down" => [
        "title" => "Three Outside Down",
        "type" => "Bearish Reversal",
        "description" => "A Bearish Engulfing followed by a red candle closing lower.",
        "trade_tip" => "Strong bearish reversal. Sell on third candle close.",
        "image" => "https://example.com/images/three_outside_down.png"
    ],
    "Three Stars in the South" => [
        "title" => "Three Stars in the South",
        "type" => "Bullish Reversal",
        "description" => "A rare bullish pattern: Long red (no lower shadow), smaller red, small green within previous range.",
        "trade_tip" => "Reversal signal. Buy on breakout above the pattern.",
        "image" => "https://example.com/images/three_stars_south.png"
    ],
    "Abandoned Baby" => [
        "title" => "Abandoned Baby",
        "type" => "Reversal",
        "description" => "A Doji gap above/below the previous candle, then a gap in opposite direction. Very rare.",
        "trade_tip" => "Very strong reversal signal. Enter on confirmation.",
        "image" => "https://example.com/images/abandoned_baby.png"
    ],

    // ========== FOUR+ CANDLE PATTERNS ==========
    "Rising Three Methods" => [
        "title" => "Rising Three Methods",
        "type" => "Bullish Continuation",
        "description" => "Long green candle, 3 small candles (red/green), then another long green candle.",
        "trade_tip" => "Consolidation before continuation. Buy on breakout of the 4th candle high.",
        "image" => "https://example.com/images/rising_three_methods.png"
    ],
    "Falling Three Methods" => [
        "title" => "Falling Three Methods",
        "type" => "Bearish Continuation",
        "description" => "Long red candle, 3 small candles (green/red), then another long red candle.",
        "trade_tip" => "Consolidation before continuation. Sell on breakdown of the 4th candle low.",
        "image" => "https://example.com/images/falling_three_methods.png"
    ],
    "Three River Bottom" => [
        "title" => "Three River Bottom",
        "type" => "Bullish Reversal",
        "description" => "Long red, Hammer (inside range), green closing above hammer's close.",
        "trade_tip" => "Reliable bottom reversal. Buy on third candle close.",
        "image" => "https://example.com/images/three_river_bottom.png"
    ],
    "Three Advancing White Soldiers" => [
        "title" => "Three Advancing White Soldiers",
        "type" => "Bullish Continuation",
        "description" => "Three green candles with each opening within the previous body and closing near the high.",
        "trade_tip" => "Extremely strong bullish momentum. Buy on pullbacks.",
        "image" => "https://example.com/images/advancing_soldiers.png"
    ],

    // ========== CHART PATTERNS (TREND/BREAKOUT) ==========
    // Head & Shoulders Family
    "Head and Shoulders" => [
        "title" => "Head and Shoulders",
        "type" => "Bearish Reversal",
        "description" => "Three peaks: Left shoulder (smaller), Head (highest), Right shoulder (smaller).",
        "trade_tip" => "Sell when price breaks below the 'Neckline' (support line). Stop-loss above right shoulder.",
        "image" => "https://example.com/images/head_shoulders.png"
    ],
    "Inverse Head and Shoulders" => [
        "title" => "Inverse Head and Shoulders",
        "type" => "Bullish Reversal",
        "description" => "Three troughs: Left shoulder (higher), Head (lowest), Right shoulder (higher).",
        "trade_tip" => "Buy when price breaks above the neckline. Stop-loss below right shoulder.",
        "image" => "https://example.com/images/inverse_head_shoulders.png"
    ],
    
    // Bottom/Top Patterns
    "Double Bottom" => [
        "title" => "Double Bottom",
        "type" => "Bullish Reversal",
        "description" => "'W' shape. Price tests a support level twice and bounces up both times.",
        "trade_tip" => "Buy when price breaks above the resistance (middle peak). Stop-loss below the bottom.",
        "image" => "https://example.com/images/double_bottom.png"
    ],
    "Double Top" => [
        "title" => "Double Top",
        "type" => "Bearish Reversal",
        "description" => "'M' shape. Price tests a resistance level twice and drops both times.",
        "trade_tip" => "Sell when price breaks below the support (middle valley). Stop-loss above the top.",
        "image" => "https://example.com/images/double_top.png"
    ],
    "Triple Bottom" => [
        "title" => "Triple Bottom",
        "type" => "Bullish Reversal",
        "description" => "Price tests support level three times and bounces up each time.",
        "trade_tip" => "Stronger than double bottom. Buy on breakout above resistance.",
        "image" => "https://example.com/images/triple_bottom.png"
    ],
    "Triple Top" => [
        "title" => "Triple Top",
        "type" => "Bearish Reversal",
        "description" => "Price tests resistance level three times and drops each time.",
        "trade_tip" => "Stronger than double top. Sell on breakdown below support.",
        "image" => "https://example.com/images/triple_top.png"
    ],
    "Rounding Bottom" => [
        "title" => "Rounding Bottom",
        "type" => "Bullish Reversal",
        "description" => "A gradual 'U' shape formation over time. Also called a saucer bottom.",
        "trade_tip" => "Buy when price breaks above the resistance of the rim.",
        "image" => "https://example.com/images/rounding_bottom.png"
    ],
    "Rounding Top" => [
        "title" => "Rounding Top",
        "type" => "Bearish Reversal",
        "description" => "A gradual inverted 'U' shape formation over time.",
        "trade_tip" => "Sell when price breaks below the support of the rim.",
        "image" => "https://example.com/images/rounding_top.png"
    ],
    "V Bottom" => [
        "title" => "V Bottom",
        "type" => "Bullish Reversal",
        "description" => "A sharp decline followed by an equally sharp rise.",
        "trade_tip" => "Very aggressive reversal. Enter on strong close and volume confirmation.",
        "image" => "https://example.com/images/v_bottom.png"
    ],

    // Triangle Patterns
    "Ascending Triangle" => [
        "title" => "Ascending Triangle",
        "type" => "Bullish Continuation",
        "description" => "Horizontal resistance line (flat top) and rising support line (higher lows).",
        "trade_tip" => "Anticipate an upside breakout. Place buy stop at resistance level.",
        "image" => "https://example.com/images/ascending_triangle.png"
    ],
    "Descending Triangle" => [
        "title" => "Descending Triangle",
        "type" => "Bearish Continuation",
        "description" => "Flat support level and descending resistance (lower highs).",
        "trade_tip" => "Anticipate a downside breakdown. Place sell stop at support level.",
        "image" => "https://example.com/images/descending_triangle.png"
    ],
    "Symmetrical Triangle" => [
        "title" => "Symmetrical Triangle",
        "type" => "Continuation/Neutral",
        "description" => "Converging trendlines where price makes lower highs and higher lows.",
        "trade_tip" => "Wait for breakout direction. Place stops above resistance or below support.",
        "image" => "https://example.com/images/symmetrical_triangle.png"
    ],
    "Expanding Triangle" => [
        "title" => "Expanding Triangle",
        "type" => "Volatility/Reversal",
        "description" => "Diverging trendlines where price makes higher highs and lower lows.",
        "trade_tip" => "High volatility pattern. Wait for confirmed breakout.",
        "image" => "https://example.com/images/expanding_triangle.png"
    ],
    "Wedge (Rising)" => [
        "title" => "Rising Wedge",
        "type" => "Bearish Reversal",
        "description" => "Price consolidates between two upward-sloping lines that converge.",
        "trade_tip" => "Prices usually break downwards. Wait for close below support.",
        "image" => "https://example.com/images/rising_wedge.png"
    ],
    "Wedge (Falling)" => [
        "title" => "Falling Wedge",
        "type" => "Bullish Reversal",
        "description" => "Price consolidates between two downward-sloping lines that converge.",
        "trade_tip" => "Prices usually break upwards. Wait for close above resistance.",
        "image" => "https://example.com/images/falling_wedge.png"
    ],

    // Flag & Pennant Patterns
    "Bull Flag" => [
        "title" => "Bull Flag",
        "type" => "Bullish Continuation",
        "description" => "A sharp price jump (pole) followed by a rectangular pullback (flag) against the trend.",
        "trade_tip" => "Buy on breakout above the flag's resistance. Stop-loss below the flag's low.",
        "image" => "https://example.com/images/bull_flag.png"
    ],
    "Bear Flag" => [
        "title" => "Bear Flag",
        "type" => "Bearish Continuation",
        "description" => "A sharp price drop (pole) followed by a rectangular pullback (flag) against the trend.",
        "trade_tip" => "Sell on breakdown below the flag's support. Stop-loss above the flag's high.",
        "image" => "https://example.com/images/bear_flag.png"
    ],
    "Bull Pennant" => [
        "title" => "Bull Pennant",
        "type" => "Bullish Continuation",
        "description" => "Sharp price move followed by a small symmetrical triangle consolidation.",
        "trade_tip" => "Buy on breakout above the pennant. Stop-loss below the pennant low.",
        "image" => "https://example.com/images/bull_pennant.png"
    ],
    "Bear Pennant" => [
        "title" => "Bear Pennant",
        "type" => "Bearish Continuation",
        "description" => "Sharp price drop followed by a small symmetrical triangle consolidation.",
        "trade_tip" => "Sell on breakdown below the pennant. Stop-loss above the pennant high.",
        "image" => "https://example.com/images/bear_pennant.png"
    ],

    // Cup & Handle Patterns
    "Cup and Handle" => [
        "title" => "Cup and Handle",
        "type" => "Bullish Continuation",
        "description" => "A 'U' shaped recovery (cup) followed by a small dip (handle).",
        "trade_tip" => "Very reliable buy signal when price breaks above the handle.",
        "image" => "https://example.com/images/cup_handle.png"
    ],
    "Cup and Handle Inverted" => [
        "title" => "Inverted Cup and Handle",
        "type" => "Bearish Reversal",
        "description" => "An inverted 'U' shaped top followed by a small rally (handle).",
        "trade_tip" => "Sell when price breaks below the handle.",
        "image" => "https://example.com/images/inverted_cup_handle.png"
    ],

    // Gap Patterns
    "Breakaway Gap" => [
        "title" => "Breakaway Gap",
        "type" => "Trend Start",
        "description" => "A gap that occurs at the end of a consolidation pattern, signaling the start of a new trend.",
        "trade_tip" => "Trade in the direction of the gap. Stop-loss just behind the gap.",
        "image" => "https://example.com/images/breakaway_gap.png"
    ],
    "Runaway Gap" => [
        "title" => "Runaway Gap",
        "type" => "Continuation",
        "description" => "A gap that occurs in the middle of a strong trend, also called measuring gap.",
        "trade_tip" => "Continuation signal. Add to position in the trend direction.",
        "image" => "https://example.com/images/runaway_gap.png"
    ],
    "Exhaustion Gap" => [
        "title" => "Exhaustion Gap",
        "type" => "Reversal Signal",
        "description" => "A gap near the end of a strong trend, often filled quickly.",
        "trade_tip" => "Warning sign of trend reversal. Take profits and wait for direction.",
        "image" => "https://example.com/images/exhaustion_gap.png"
    ],
    "Island Reversal" => [
        "title" => "Island Reversal",
        "type" => "Strong Reversal",
        "description" => "A gap up, a few candles, then a gap down (or vice versa), forming an 'island'.",
        "trade_tip" => "Very strong reversal signal. Enter immediately after the second gap.",
        "image" => "https://example.com/images/island_reversal.png"
    ],

    // Additional Important Patterns
    "Bullish Harami Cross" => [
        "title" => "Bullish Harami Cross",
        "type" => "Bullish Reversal",
        "description" => "A Doji inside a previous large red candle after a downtrend.",
        "trade_tip" => "Strong reversal signal. Buy on next green candle.",
        "image" => "https://example.com/images/bullish_harami_cross.png"
    ],
    "Bearish Harami Cross" => [
        "title" => "Bearish Harami Cross",
        "type" => "Bearish Reversal",
        "description" => "A Doji inside a previous large green candle after an uptrend.",
        "trade_tip" => "Strong reversal signal. Sell on next red candle.",
        "image" => "https://example.com/images/bearish_harami_cross.png"
    ],
    "Meeting Lines" => [
        "title" => "Meeting Lines",
        "type" => "Reversal",
        "description" => "Green and red candles with the same closings (bullish or bearish version).",
        "trade_tip" => "Reversal signal. Trade in the direction of the second candle.",
        "image" => "https://example.com/images/meeting_lines.png"
    ],
    "Separating Lines" => [
        "title" => "Separating Lines",
        "type" => "Continuation",
        "description" => "Red then green candles with same opening prices (bullish continuation).",
        "trade_tip" => "Confirms trend continuation. Trade in the trend direction.",
        "image" => "https://example.com/images/separating_lines.png"
    ],
    "Kicking" => [
        "title" => "Kicking",
        "type" => "Strong Reversal",
        "description" => "A Marubozu gap in the opposite direction of the previous Marubozu.",
        "trade_tip" => "Very strong reversal. Enter immediately on the gap.",
        "image" => "https://example.com/images/kicking.png"
    ],
    "Ladder Bottom" => [
        "title" => "Ladder Bottom",
        "type" => "Bullish Reversal",
        "description" => "A series of lower closes followed by a gap up and strong green candle.",
        "trade_tip" => "Reversal after a downtrend. Buy on confirmation.",
        "image" => "https://example.com/images/ladder_bottom.png"
    ],
    "Ladder Top" => [
        "title" => "Ladder Top",
        "type" => "Bearish Reversal",
        "description" => "A series of higher closes followed by a gap down and strong red candle.",
        "trade_tip" => "Reversal after an uptrend. Sell on confirmation.",
        "image" => "https://example.com/images/ladder_top.png"
    ],
    "Upside Tasuki Gap" => [
        "title" => "Upside Tasuki Gap",
        "type" => "Bullish Continuation",
        "description" => "A gap up, green candle, then a red candle that opens in the gap but doesn't close it.",
        "trade_tip" => "Continuation signal. Buy on pullback.",
        "image" => "https://example.com/images/upside_tasuki.png"
    ],
    "Downside Tasuki Gap" => [
        "title" => "Downside Tasuki Gap",
        "type" => "Bearish Continuation",
        "description" => "A gap down, red candle, then a green candle that opens in the gap but doesn't close it.",
        "trade_tip" => "Continuation signal. Sell on rally.",
        "image" => "https://example.com/images/downside_tasuki.png"
    ]
];

$pattern = isset($_GET['pattern']) ? trim($_GET['pattern']) : '';

// Normalize for case-insensitive matching including spaces
$normalizedPatterns = [];
foreach ($patternInfo as $key => $value) {
    $normalizedPatterns[strtolower($key)] = $value;
}

$normalizedInput = strtolower($pattern);

if (array_key_exists($normalizedInput, $normalizedPatterns)) {
    echo json_encode([
        "status" => "success",
        "pattern" => $normalizedPatterns[$normalizedInput]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Pattern not found: " . $pattern
    ]);
}
?>