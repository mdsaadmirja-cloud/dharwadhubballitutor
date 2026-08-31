<?php
$apiKey = "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";

$ch = curl_init("https://api.openai.com/v1/models");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $apiKey
    ]
]);

$response = curl_exec($ch);

if ($response === false) {
    echo curl_error($ch);
} else {
    echo "API KEY IS VALID";
}
curl_close($ch);
