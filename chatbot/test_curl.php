<?php
$ch = curl_init("https://api.openai.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if ($response === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    echo "cURL is WORKING";
}
curl_close($ch);
