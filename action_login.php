<?php
define("allow_inc", true);
require_once("_func.global.php");
if (!isPosted()) die("You can't access this file directly.");
require_once("scr/securimage/securimage.php");
$securimage = new Securimage();
if (!$securimage->check($_POST['captcha_code'])) die("incorrect_captcha");
$username = $dbc->escape_string($_POST["username"]);
$password = $dbc->escape_string($_POST["password"]);

$stmt = $dbc->stmt_init();
if(!$stmt->prepare("SELECT `uid`,`upw`,`salt`,`status` FROM `users` WHERE `uid` = ?")) die("users_select_error [" . $stmt->error . "]");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($username, $current_password, $salt, $status);
if ($stmt->fetch() === NULL) die("username_not_found");
if (sha1($salt.$password) !== $current_password) die("incorrect_password");
if ($status === "inactive") die("user_not_active");
// Login success, set session variables
$_SESSION["user"] = $username;
die("success");
?>