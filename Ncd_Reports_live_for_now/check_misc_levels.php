<?php
date_default_timezone_set('Asia/Kolkata');

define('API_KEY', '84950dfe63c3a294f83e8e656763475c50625dc8c577c84f479785b6d00e4e31');

function callAPI($url)
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false, // avoid SSL issue (local dev)
        CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['error' => curl_error($ch)];
    }

    curl_close($ch);
    return $response;
}

echo "<h2>🔍 Miscellaneous API Level Checker (cURL)</h2>";
echo "<pre>";

for ($i = 1; $i <= 25; $i++) {

    $url = "https://api.cooperatives.gov.in/en/Api/apimiscellanousdata?" . http_build_query([
            'key'   => API_KEY,
            'state' => 9,
            'level' => $i
        ]);

    echo "Checking Level $i ... ";

    $response = callAPI($url);

    // Handle cURL error
    if (is_array($response) && isset($response['error'])) {
        echo "❌ cURL Error: " . $response['error'] . "\n";
        continue;
    }

    if (!$response) {
        echo "❌ Empty response\n";
        continue;
    }

    $data = json_decode($response, true);

    if (!$data) {
        echo "❌ Invalid JSON\n";
        continue;
    }

    if (!empty($data['api_type'])) {
        echo "✅ " . $data['api_type'] . "\n";
    } else {
        echo "⚠️ No api_type\n";
    }

    // 🔍 Detect Board of Directors
    if (!empty($data['api_type']) && stripos($data['api_type'], 'Director') !== false) {
        echo "👉 FOUND POSSIBLE MATCH AT LEVEL $i\n";
    }

    echo "\n";
}

echo "</pre>";