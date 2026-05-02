<?php
set_time_limit(0); // 🔥 prevent timeout
ini_set('memory_limit', '512M');

include("../scripts/settings.php");

$apiUrl = "https://api.cooperatives.gov.in/en/Api/sectorchildtablerawdatastatewise";
$key = "84950dfe63c3a294f83e8e656763475c50625dc8c577c84f479785b6d00e4e31";
$state_code = "9";

echo "<pre>";

for ($sector = 101; $sector <= 200; $sector++) {

    echo "\n🔍 Checking Sector: $sector\n";

    $payload = json_encode([
        "key" => $key,
        "state_code" => $state_code,
        "sector_code" => (string)$sector
    ]);

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json",
            "User-Agent: Mozilla/5.0"
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($ch);

    // ❌ cURL error
    if (curl_errno($ch)) {
        echo "❌ cURL Error: " . curl_error($ch) . "\n";
        curl_close($ch);
        continue;
    }

    curl_close($ch);

    $data = json_decode($response, true);

    // ❌ Invalid JSON
    if (!$data) {
        echo "❌ Invalid JSON response\n";
        continue;
    }

    // ❌ API failure
    if (($data['status'] ?? '') !== 'Success') {
        echo "❌ API Failed → " . ($data['message'] ?? 'No message') . "\n";
        continue;
    }

    // ✅ Extract sector name
    $sectorName = $data['sector'] ?? 'N/A';

    // ✅ Extract dynamic key
    if (!empty($data['result'][0])) {

        $dynamicKey = array_key_first($data['result'][0]);

        echo "✅ Sector Code: $sector\n";
        echo "   Sector Name: $sectorName\n";
        echo "   Dynamic Key: $dynamicKey\n";

        // 🔥 OPTIONAL: Save mapping in DB
        /*
        $sectorCodeEsc = intval($sector);
        $sectorNameEsc = mysqli_real_escape_string($db, $sectorName);
        $dynamicKeyEsc = mysqli_real_escape_string($db, $dynamicKey);

        execute_query("
            INSERT INTO sector_dynamic_mapping (sector_code, sector_name, dynamic_key)
            VALUES ($sectorCodeEsc, '$sectorNameEsc', '$dynamicKeyEsc')
            ON DUPLICATE KEY UPDATE
                sector_name = '$sectorNameEsc',
                dynamic_key = '$dynamicKeyEsc'
        ");
        */

    } else {
        echo "⚠ No data found for this sector\n";
    }

    // 🔥 IMPORTANT: prevent API blocking
    usleep(200000); // 0.2 sec
}

echo "\n\n🎯 DONE";