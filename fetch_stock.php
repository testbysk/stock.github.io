<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Get symbol from query parameter
$symbol = isset($_GET['symbol']) ? $_GET['symbol'] : 'SBIN.NS';

// Fetch current data (1d range for latest price)
$url1d = "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}?interval=1d&range=1d";

// Fetch 20-day data for average volume calculation
$url20d = "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}?interval=1d&range=20d";

// Function to fetch data via cURL (OPTIMIZED)
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Reduced from 10 to 5 seconds
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // Connection timeout
    curl_setopt($ch, CURLOPT_ENCODING, ''); // Enable compression
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0); // Use HTTP/2 if available
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && $response) {
        return json_decode($response, true);
    }
    return null;
}

// Fetch both datasets
$currentData = fetchData($url1d);
$historicalData = fetchData($url20d);

if ($currentData && isset($currentData['chart']['result'][0])) {
    $result = $currentData['chart']['result'][0];
    
    // Calculate 20-day average volume if historical data available
    $avgVolume20d = 0;
    if ($historicalData && isset($historicalData['chart']['result'][0]['indicators']['quote'][0]['volume'])) {
        $volumes = $historicalData['chart']['result'][0]['indicators']['quote'][0]['volume'];
        // Filter out null values
        $validVolumes = array_filter($volumes, function($v) { return $v !== null && $v > 0; });
        
        if (count($validVolumes) > 0) {
            $avgVolume20d = round(array_sum($validVolumes) / count($validVolumes));
        }
    }
    
    // Add avgVolume20d to the meta data
    if (isset($result['meta'])) {
        $result['meta']['avgVolume20d'] = $avgVolume20d;
    }
    
    // Return modified response
    $currentData['chart']['result'][0] = $result;
    echo json_encode($currentData);
} else {
    echo json_encode(['error' => 'Failed to fetch data', 'symbol' => $symbol]);
}
?>

