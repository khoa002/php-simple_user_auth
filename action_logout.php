<?php
define("allow_inc", true);
require_once("_func.global.php");
if (!isPosted()) die("You can't access this file directly.");
if (!$user) die("You're not logged in.");
unset($_SESSION["user"]);
die("success");
?>