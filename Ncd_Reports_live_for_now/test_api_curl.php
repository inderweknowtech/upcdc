<?php

echo "<h3>Testing API (POST JSON)...</h3>";

$url = "https://api.cooperatives.gov.in/Api/findStateLgCode";

$params = [
    "key" => "84950dfe63c3a294f83e8e656763475c50625dc8c577c84f479785b6d00e4e31",
    "state_code" => 9
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,

    // ✅ POST
    CURLOPT_POST => true,

    // 🔥 JSON BODY
    CURLOPT_POSTFIELDS => json_encode($params),

    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 5,

    // SSL fix
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,

    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Accept: application/json"
    ]
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "<b>CURL Error:</b> " . curl_error($ch);
} else {
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    echo "<b>HTTP Code:</b> $http_code<br><br>";

    echo "<b>Response:</b><br><pre>";
    print_r($response);
    echo "</pre>";
}

curl_close($ch);

echo "<br>✅ Done";
?>
