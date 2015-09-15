<?php
define("allow_inc", true);
session_id($_POST["session"]);
require_once("_func.global.php");
if (!$user) die("You're not logged in.");

$targetFolder = "/img/profiles";
if (!empty($_FILES)) {
	$fileParts = pathinfo($_FILES["Filedata"]["name"]);
	
	$targetPath = $_SERVER["DOCUMENT_ROOT"] . $targetFolder;
	$targetFile = rtrim($targetPath,"/") . "/" . $user["uid"] . "." . $fileParts["extension"];
	$relativeTargetFile = rtrim($targetFolder,"/") . "/" . $user["uid"] . "." . $fileParts["extension"];

	// Validate the file type
	$fileTypes = array("jpg","jpeg","gif","png"); // File extensions

	if (!in_array($fileParts["extension"], $fileTypes)) die("Invalid file type.");
	move_uploaded_file($_FILES["Filedata"]["tmp_name"], $targetFile);
	
	$stmt = $dbc->stmt_init();
	if (!$stmt->prepare("UPDATE `profiles` SET `profile_pic` = ? WHERE `uid` = ?")) die("Profiles update error. Please contact the site admin.");
	$stmt->bind_param("ss", $relativeTargetFile, $user["uid"]);
	$stmt->execute();
	if ($stmt->affected_rows === -1) die("Profiles update error. Please contact the site admin.");
	die("success");
}
?>