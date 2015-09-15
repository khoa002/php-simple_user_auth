<?php
define("allow_inc", true);
require_once("_func.global.php");
if (!$user) header("Location: login/");
else header("Location: home/");
?>