<?php
define("allow_inc", true);
require_once("_func.global.php");
if (!isPosted()) { die("You can't access this file directly."); }
require_once("scr/securimage/securimage.php");
$securimage = new Securimage();
if (!$securimage->check($_POST['captcha_code'])) { die("incorrect_captcha"); }

$username = $dbc->escape_string($_POST["username"]);
$user_salt = generate_salt();
$password = $dbc->escape_string($_POST["password1"]);
$password = sha1($user_salt.$password);
$email = $dbc->escape_string($_POST["email"]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) die("invalid_email_address");

$stmt = $dbc->stmt_init();
if(!$stmt->prepare("SELECT `uid` FROM `users` WHERE `uid` = ?")) die("users_select_error [" . $stmt->error . "]");
$stmt->bind_param("s", $username);
$stmt->execute();
if ($stmt->fetch() !== NULL) die("username_already_exists");
if(!$stmt->prepare("SELECT `email` FROM `users` WHERE `email` = ?")) die("users_select_error [" . $stmt->error . "]");
$stmt->bind_param("s", $email);
$stmt->execute();
if ($stmt->fetch() !== NULL) die("email_already_exists");
if (!$stmt->prepare("INSERT INTO `users` (`uid`, `upw`, `salt`, `email`) VALUES (?,?,?,?)")) die("users_insert_error [" . $stmt->error . "]");
$stmt->bind_param("ssss", $username, $password, $user_salt, $email);
$stmt->execute();
if ($stmt->affected_rows === -1) die("users_insert_query_error");

$email_validation_salt = generate_salt(32);
if (!$stmt->prepare("INSERT INTO `system_settings` (`name`, `value`, `note`) VALUES (?, ?, ?)")) die("system_settings_insert_error [" . $stmt->error . "]");
$stmt->bind_param("sss", $str = "email_validation", $email_validation_salt, $username);
$stmt->execute();
if ($stmt->affected_rows === -1) die("system_settings_insert_query_error");

$mail_msg = "Thank you for registering with our site, please use the following link to validate your email address: http://yzm.atsuu.com/validate_email/" . $email_validation_salt;
$mail_headers = "From: khoa002@gmail.com" . "\r\n" .
	"Reply-To: khoa002@gmail.com" . "\r\n" .
	"X-Mailer: PHP/" . phpversion();
if (!mail($email, 'Please validate your account', $mail_msg, $mail_headers)) die("email_error");
die("success");
?>