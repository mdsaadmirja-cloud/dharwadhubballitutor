<?php
session_start();

// Create random string
$captcha_code = '';
$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
for ($i = 0; $i < 6; $i++) {
    $captcha_code .= $chars[rand(0, strlen($chars) - 1)];
}
$_SESSION['captcha'] = $captcha_code;

// Create image
$width = 120;
$height = 40;
$image = imagecreate($width, $height);

// Colors
$background_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);

// Add noise
for ($i = 0; $i < 50; $i++) {
    $noise_color = imagecolorallocate($image, rand(100,255), rand(100,255), rand(100,255));
    imagefilledellipse($image, rand(0,$width), rand(0,$height), 2, 3, $noise_color);
}

// Add text
imagestring($image, 5, 20, 10, $captcha_code, $text_color);

// Output image
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
