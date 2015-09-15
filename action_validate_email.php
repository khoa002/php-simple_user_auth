<?php
define("allow_inc", true);
require_once("_func.global.php");
if (!isPosted()) { die("You can't access this file directly."); }
$salt = $dbc->escape_string($_POST["token"]);

$stmt = $dbc->stmt_init();
if (!$stmt->prepare("SELECT `note` FROM `system_settings` WHERE `name` = 'email_validation' AND `value` = ?")) die("system_settings_select_error [" . $stmt->error . "]");
$stmt->bind_param("s", $salt);
$stmt->execute();
$stmt->bind_result($username);
if ($stmt->fetch() === NULL) die("invalid_token");
if (!$stmt->prepare("UPDATE `users` SET `status` = ? WHERE `uid` = ?")) die("users_update_error");
$stmt->bind_param("ss", $str = "active", $username);
$stmt->execute();
if ($stmt->affected_rows === -1) die("users_update_query_error");
if (!$stmt->prepare("DELETE FROM `system_settings` WHERE `name` = 'email_validation' AND `value` = ? AND `note` = ?")) die("system_settings_delete_error [" . $stmt->error . "]");
$stmt->bind_param("ss", $salt, $username);
$stmt->execute();
if ($stmt->affected_rows === -1) die("system_settings_delete_query_error [" . $stmt->error . "]");
echo "success";
?>