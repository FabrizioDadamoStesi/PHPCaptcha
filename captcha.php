<?php
session_start();

// Generazione del token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Generazione del captcha
$captcha_num = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
$_SESSION["captcha"] = $captcha_num;

// Creazione dell'immagine captcha
$width = 150;
$height = 40;
$image = imagecreatetruecolor($width, $height);
$background_color = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $background_color);
$text_color = imagecolorallocate($image, 0, 0, 0);
$font = 'fonts/arial.ttf';
imagettftext($image, 20, 0, 30, 30, $text_color, $font, $captcha_num);

// Aggiunta di linee di disturbo all'immagine
for ($i = 0; $i < 5; $i++) {
  $line_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
  imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
}

// Aggiunta di punti di disturbo all'immagine
for ($i = 0; $i < 50; $i++) {
  $pixel_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
  imagesetpixel($image, rand(0, $width), rand(0, $height), $pixel_color);
}

// Impostazione dell'header per l'immagine
header('Content-Type: image/jpeg');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");

// Output dell'immagine captcha
imagejpeg($image);

// Eliminazione dell'immagine dalla memoria
imagedestroy($image);
?>
