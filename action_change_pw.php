<?php
define("allow_inc", true);
require_once("_func.global.php");
if (!isPosted()) die("You can't access this file directly.");
if (!$user) die("You're not logged in.");
require_once("scr/securimage/securimage.php");
$securimage = new Securimage();
if (!$securimage->check($_POST['captcha_code'])) die("incorrect_captcha");
$username = $user["uid"];
$password = $dbc->escape_string($_POST["password"]);
$stmt = $dbc->stmt_init();
if(!$stmt->prepare("SELECT `uid`,`upw`,`salt`,`status` FROM `users` WHERE `uid` = ?")) die("users_select_error [" . $stmt->error . "]");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($username, $current_password, $salt, $status);
if ($stmt->fetch() === NULL) die("user_not_found");
if (sha1($salt.$password) !== $current_password) die("incorrect_password");

$new_password = sha1($salt.$_POST["password1"]);
if (!$stmt->prepare("UPDATE `users` SET `upw` = ? WHERE `uid` = ?")) die("users_update_error");
$stmt->bind_param("ss", $new_password, $username);
$stmt->execute();
if ($stmt->affected_rows === -1) die("users_update_query_error");
die("success");
?>