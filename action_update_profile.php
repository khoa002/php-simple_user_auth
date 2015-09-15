<?php
define("allow_inc", true);
require_once("_func.global.php");
if (!isPosted()) die("You can't access this file directly.");
if (!$user) die("You're not logged in.");

$username = $user["uid"];
$email = $dbc->escape_string($_POST["email"]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) die("invalid_email_address");
$first_name = $dbc->escape_string($_POST["first_name"]);
$last_name = $dbc->escape_string($_POST["last_name"]);
$address = $dbc->escape_string($_POST["address"]);
$city = $dbc->escape_string($_POST["city"]);
$state = $dbc->escape_string($_POST["state"]);
$zip_code = $dbc->escape_string($_POST["zip_code"]);

$stmt = $dbc->stmt_init();
if (!$stmt->prepare("UPDATE `users` SET `email` = ? WHERE `uid` = ?")) die("profiles_update_error [" . $stmt->error . "]");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
if ($stmt->affected_rows === -1) { die("users_update_query_error [" . $stmt->error . "]"); }

if (!$stmt->prepare("SELECT `uid` FROM `profiles` WHERE `uid` = ?")) die("profiles_select_error");
$stmt->bind_param("s", $user["uid"]);
$stmt->execute();
if ($stmt->fetch() === NULL){
	// user profile doesn't exist, insert new one
	if (!$stmt->prepare("INSERT INTO `profiles` (`uid`, `first_name`, `last_name`, `address`, `city`, `state`, `zip_code`) VALUES (?,?,?,?,?,?,?)")) die("profiles_insert_error [" . $stmt->error . "]");
	$stmt->bind_param("sssssss", $username, $first_name, $last_name, $address, $city, $state, $zip_code);
	$stmt->execute();
	if ($stmt->affected_rows === -1) { die("profiles_insert_query_error [" . $stmt->error . "]"); }
} else {
	// user profile exists, update it
	if (!$stmt->prepare("UPDATE `profiles` SET `first_name` = ?, `last_name` = ?, `address` = ?, `city` = ?, `state` = ?, `zip_code` = ? WHERE `uid` = ?")) die("profiles_update_error [" . $stmt->error . "]");
	$stmt->bind_param("sssssss", $first_name, $last_name, $address, $city, $state, $zip_code, $username);
	$stmt->execute();
	if ($stmt->affected_rows === -1) { die("profiles_update_query_error [" . $stmt->error . "]"); }
}
die("success");
?>