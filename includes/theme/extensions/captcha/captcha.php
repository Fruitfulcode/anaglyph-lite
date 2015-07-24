<?php
	/*generate cptcha*/
	session_start(); // Staring Session
	$captchanumber = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; // Initializing PHP variable with string
	$captchanumber = substr(str_shuffle($captchanumber), 0, 6); // Getting first 6 word after shuffle.
	$_SESSION["cf_code"] = $captchanumber; // Initializing session variable with above generated sub-string
	$image 		= imagecreatefrompng("captcha.png"); // Generating CAPTCHA
	$foreground = imagecolorallocate($image, 254, 254, 254); // Font Color
	$transparent_color = imagecolorat($image, 1, 1);
	imagestring($image, 6, 25, 15, $captchanumber, $foreground);
	imagecolortransparent($image, $transparent_color);  
	header('Content-type: image/png');
	imagepng($image);